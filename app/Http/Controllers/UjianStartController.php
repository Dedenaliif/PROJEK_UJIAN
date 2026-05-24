<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use App\Models\Pertanyaan;
use App\Models\LatihanUjian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UjianStartController extends Controller
{
    public function latihanStart($id)
    {
        session()->forget([
            'latihan_soal_' . Auth::id() . '_' . $id,
            'jawaban_latihan_' . Auth::id() . '_' . $id
        ]);

        return redirect()->route('ujian.latihan.show', $id);
    }

    public function latihanShow($ujianId)
    {
        $ujian = Ujian::findOrFail($ujianId);

        $sudah = LatihanUjian::where([
            'user_id'=>Auth::id(),
            'ujian_id'=>$ujianId,
            'selesai'=>true
        ])->exists();

        if($sudah){
            return redirect()->route('siswa.ujian');
        }

        $key='latihan_soal_'.Auth::id().'_'.$ujianId;

        $soalIds=session($key);

        if(!$soalIds){

            $soalIds=Pertanyaan::where('ujian_id',$ujianId)
                ->inRandomOrder()
                ->limit(5)
                ->pluck('id')
                ->toArray();

            session([$key=>$soalIds]);
        }

        $soals=Pertanyaan::whereIn('id',$soalIds)->get();

        $jawabanUser=session(
            'jawaban_latihan_'.Auth::id().'_'.$ujianId,
            []
        );

        $current=1;
        $waktuSelesai=now()->addMinutes(10);

        return view('ujian.latihan',compact(
            'ujian',
            'soals',
            'current',
            'jawabanUser',
            'waktuSelesai'
        ));
    }

    public function latihanSave(Request $request,$ujianId)
    {
        session()->put(
            'jawaban_latihan_'.Auth::id().'_'.$ujianId.'.'.$request->soal_id,
            $request->jawaban
        );

        return response()->json([
            'success'=>true
        ]);
    }

    public function latihanSelesai($id)
    {
        $soalIds=session(
            'latihan_soal_'.Auth::id().'_'.$id
        );

        $jawaban=session(
            'jawaban_latihan_'.Auth::id().'_'.$id,
            []
        );

        $benar=0;

        foreach($soalIds as $sid){

            $soal=Pertanyaan::find($sid);

            if(
                isset($jawaban[$sid]) &&
                strtoupper($jawaban[$sid])==
                strtoupper($soal->jawaban_benar)
            ){
                $benar++;
            }
        }

        $nilai=round(($benar/count($soalIds))*100);

        LatihanUjian::updateOrCreate([
            'user_id'=>Auth::id(),
            'ujian_id'=>$id
        ],[
            'selesai'=>true,
            'nilai'=>$nilai
        ]);

        session()->forget([
            'latihan_soal_'.Auth::id().'_'.$id,
            'jawaban_latihan_'.Auth::id().'_'.$id
        ]);

        return redirect()->route(
            'ujian.latihan.hasil',
            $id
        );
    }

    public function hasilLatihan($id)
    {
        $ujian=Ujian::findOrFail($id);

        $latihan=LatihanUjian::where([
            'user_id'=>Auth::id(),
            'ujian_id'=>$id
        ])->first();

        if(!$latihan){
            return redirect()->route(
                'ujian.latihan.show',
                $id
            );
        }

        $nilai=$latihan->nilai;
        $lulus=true;

        return view('ujian.hasil-latihan',
            compact('ujian','nilai','lulus')
        );
    }
}
