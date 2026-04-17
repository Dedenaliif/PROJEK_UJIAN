<?php

namespace App\Http\Controllers;

use App\Models\Pertanyaan;
use App\Models\Ujian;
use Illuminate\Http\Request;

class BuatSoalController extends Controller
{
    public function create($ujian)
    {

        return view('ujian.createsoal', compact('ujian'));
    }

    // public function createsoal($ujian)
    // {
    //     return view('ujian.index', compact('ujian'));
    // }

    public function store(Request $request, $ujian)
    {
        // dd($ujian);
        // dd($request->all(), 'ini ujian id'.$ujianId);
        $request->validate([
            'text_pertanyaan' => 'required',
            'opsi_a' => 'required',
            'opsi_b' => 'required',
            'opsi_c' => 'required',
            'opsi_d' => 'required',
            'jawaban_benar' => 'required',
            'skor' => 'required|integer'
        ]);

        Pertanyaan::create([
            'ujian_id' => $ujian,
            'text_pertanyaan' => $request->text_pertanyaan,
            'opsi_a' => $request->opsi_a,
            'opsi_b' => $request->opsi_b,
            'opsi_c' => $request->opsi_c,
            'opsi_d' => $request->opsi_d,
            'jawaban_benar' => $request->jawaban_benar,
            'skor' => $request->skor
        ]);

        return back()->with('success', 'Soal berhasil ditambahkan');
    }
}
