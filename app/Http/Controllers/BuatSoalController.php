<?php

namespace App\Http\Controllers;

use App\Models\Pertanyaan;
use App\Models\Ujian;
use Illuminate\Http\Request;

class BuatSoalController extends Controller
{
    public function index()
    {
        $ujians = Ujian::withCount([
            'pertanyaans as total_word' => function ($q) {
                $q->where('tipe', 'word');
            },
            'pertanyaans as total_excel' => function ($q) {
                $q->where('tipe', 'excel');
            }
        ])->get();

        return view('ujian.index', compact('ujians'));
    }

    public function create($ujianId)
    {
        $ujian = Ujian::findOrFail($ujianId);

        $soals = Pertanyaan::where('ujian_id', $ujianId)->get();

        return view('ujian.soal', compact('ujian', 'soals'));
    }

    public function store(Request $request, $ujianId)
    {
        $ujian = Ujian::findOrFail($ujianId);

        // hitung soal yang sudah ada
        $jumlahSoalSekarang = Pertanyaan::where('ujian_id', $ujianId)->count();

        // jumlah input baru
        $jumlahInput = count($request->text_pertanyaan);

        // cek kalau melebihi 30
        if (($jumlahSoalSekarang + $jumlahInput) > 30) {

            $sisa = 30 - $jumlahSoalSekarang;

            return redirect()->back()->with('error',
                $sisa > 0
                    ? "Soal maksimal 30. Kamu hanya bisa menambah $sisa soal lagi."
                    : "Soal sudah mencapai batas maksimal (30 soal)."
            );
        }

        // simpan soal
        for ($i = 0; $i < $jumlahInput; $i++) {
            \App\Models\Pertanyaan::create([
                'ujian_id' => $ujianId,
                'text_pertanyaan' => $request->text_pertanyaan[$i],
                'opsi_a' => $request->opsi_a[$i],
                'opsi_b' => $request->opsi_b[$i],
                'opsi_c' => $request->opsi_c[$i],
                'opsi_d' => $request->opsi_d[$i],
                'jawaban_benar' => $request->jawaban_benar[$i],
            ]);
        }

        return redirect()->back()->with('success','Soal berhasil disimpan');
    }

    public function edit($ujianId, $id)
    {
        $ujian = Ujian::findOrFail($ujianId);
        $edit = Pertanyaan::findOrFail($id);

        $soals = Pertanyaan::where('ujian_id', $ujianId)->get();

        return view('ujian.soal', compact('ujian','soals','edit'));
    }

    public function update(Request $request, $id)
    {
        $soal = Pertanyaan::findOrFail($id);

        $soal->update([
            'text_pertanyaan' => $request->text_pertanyaan,
            'opsi_a' => $request->opsi_a,
            'opsi_b' => $request->opsi_b,
            'opsi_c' => $request->opsi_c,
            'opsi_d' => $request->opsi_d,
            'jawaban_benar' => $request->jawaban_benar,
        ]);

        return redirect()->route('soal.create', [
            'ujian' => $soal->ujian_id,
            'tipe' => $soal->tipe
        ])->with('success', 'Soal berhasil diupdate');
    }

    public function destroy($ujian, $id)
    {
        $soal = Pertanyaan::findOrFail($id);

        $tipe = $soal->tipe; // simpan dulu sebelum delete

        $soal->delete();

        return redirect()->route('soal.create', [
            'ujian' => $ujian,
            'tipe' => $tipe
        ])->with('success', 'Soal berhasil dihapus');
    }
}
