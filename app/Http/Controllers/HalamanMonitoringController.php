<?php

namespace App\Http\Controllers;

use App\Models\Pertanyaan;
use App\Models\Siswa;
use App\Models\Ujian;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HalamanMonitoringController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $ujians = Ujian::withCount([
            'pertanyaans as total',
            'percobaanUjians as jumlah_percobaan' => function ($query) use ($userId) {
                $query->where('user_id', $userId)->where('status', 'selesai');
            }
        ])->get();
        // $percobaanujian = PercobaanUjian::where('user_id', Auth::user()->id)->where('ujian_id', $ujians->id)->where('status', 'selesai')->count();
        return view('ujian.halaman-index-monitoring', compact('ujians'));
    }
    public function monitor($ujian_id)
    {
        $ujian = Ujian::findOrFail($ujian_id);

        // Ambil siswa yang hanya punya akun user valid
        $siswas = Siswa::has('user')
            ->with(['user.percobaanUjians' => function ($query) use ($ujian_id) {
                $query->where('ujian_id', $ujian_id)->latest();
            }, 'user.percobaanUjians.jawabans'])
            ->get();

        $totalSoal = Pertanyaan::where('ujian_id', $ujian_id)->count();

        $stats = [
            'total' => $siswas->count(),
            'mengerjakan' => 0,
            'selesai' => 0,
            'offline' => 0
        ];

        foreach ($siswas as $siswa) {
            // Gunakan null-safe operator ?->
            $percobaan = $siswa->user?->percobaanUjians?->first();

            if (!$percobaan) {
                $stats['offline']++;
            } elseif ($percobaan->status === 'sedang dikerjakan') {
                $stats['mengerjakan']++;
            } else {
                $stats['selesai']++;
            }
        }

        return view('ujian.halaman-monitoring', compact('ujian', 'siswas', 'stats', 'totalSoal'));
    }

    /**
     * Fitur Tambahan: Selesaikan paksa ujian siswa
     */
    public function forceStop($percobaan_id)
    {
        try {
            $percobaan = \App\Models\PercobaanUjian::findOrFail($percobaan_id);
            $percobaan->update([
                'status' => 'selesai',
                'waktu_selesai' => now()
            ]);

            return back()->with('success', 'Ujian siswa berhasil dihentikan paksa.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghentikan ujian: ' . $e->getMessage());
        }
    }
}
