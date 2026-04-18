<?php

namespace App\Http\Controllers;

use App\Models\Pertanyaan;
use App\Models\Ujian;
use Illuminate\Http\Request;

class UjianTipeController extends Controller
{
    public function index($id)
    {
        $ujian = Ujian::withCount('pertanyaans')->findOrFail($id);
        $tipeSoal = [(object) ['tipe' => $ujian->tipe, 'total' => $ujian->pertanyaans_count]];
        return view('ujian.halaman-tipe', compact('ujian', 'tipeSoal'));
    }
}
