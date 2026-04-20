<?php

namespace App\Http\Controllers;

use App\Models\PercobaanUjian;
use App\Models\Siswa;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HalamanHistoryController extends Controller
{
    public function index()
    {
        return view('ujian.halaman-history');
    }
    public function history($ujian_id)
    {
        $userId = Auth::id();

        // Mengambil detail ujian dan data siswa
        $ujian = Ujian::findOrFail($ujian_id);
        $siswa = Siswa::where('user_id', $userId)->first();

        // Mengambil semua percobaan milik user ini untuk ujian tertentu
        $attempts = PercobaanUjian::where('user_id', $userId)
            ->where('ujian_id', $ujian_id)
            ->orderBy('percobaan_ke', 'desc')
            ->get();

        // Mencari skor tertinggi
        $maxScore = $attempts->max('skor');

        return view('ujian.halaman-history', compact('ujian', 'siswa', 'attempts', 'maxScore'));
    }
}
