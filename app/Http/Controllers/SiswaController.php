<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::with(['user', 'kelas', 'jurusan'])->whereHas('user', function ($query) {
            $query->where('role', 'siswa');
        })->get();
        // dd($siswas);
        return view('siswa.index', compact('siswas'));
    }

    // public function create()
    // {
    //     $kelas = Kelas::all();
    //     $jurusan = Jurusan::all();
    //     $users = User::where('role', 'siswa')->get();

    //     return view('siswa.create', compact('kelas', 'jurusan', 'users'));
    // }

    public function store(Request $request)
    {
        Siswa::create([
            'user_id' => $request->user_id,
            'kelas_id' => $request->kelas_id,
            'jurusan_id' => $request->jurusan_id,
            'nis' => $request->nis,
            'no_hp' => $request->no_hp,
            'nik' => $request->nik,
            'email' => $request->email
        ]);

        return redirect()->route('dashboard')->with('success', 'Data siswa berhasil disimpan!');
    }
    public function exportCsv()
    {
        // 1. Ambil semua data siswa beserta relasinya
        $siswas = Siswa::with(['user', 'kelas', 'jurusan'])->whereHas('user', function ($query) {
            $query->where('role', 'siswa');
        })->get();

        // 2. Tentukan nama file yang akan diunduh
        $fileName = 'data_siswa_' . date('Y-m-d_H-i-s') . '.csv';

        // 3. Atur Header HTTP agar browser mengenalnya sebagai file download CSV
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // 4. Definisikan judul kolom di dalam baris pertama file CSV
        $columns = ['No', 'Nama Siswa', 'NIS', 'Kelas', 'Jurusan', 'No HP', 'Email', 'NIK'];

        // 5. Gunakan StreamedResponse agar hemat memori saat data sangat banyak
        $callback = function () use ($siswas, $columns) {
            $file = fopen('php://output', 'w');

            // Tambahkan BOM (Byte Order Mark) agar Microsoft Excel membaca karakter UTF-8 dengan benar (tidak berantakan)
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Tulis baris header kolom
            fputcsv($file, $columns, ';'); // Menggunakan separator semicolon (;) agar langsung rapi di Excel regional Indonesia

            // Tulis baris data siswa
            foreach ($siswas as $key => $siswa) {
                fputcsv($file, [
                    $key + 1,
                    $siswa->nama_siswa ?? ($siswa->user->name ?? ''), // Fallback ke relasi user jika perlu
                    $siswa->nis ?? '',
                    $siswa->kelas->nama_kelas ?? '',
                    $siswa->jurusan->nama_jurusan ?? '',
                    $siswa->no_hp ?? '',
                    $siswa->email ?? '',
                    $siswa->nik ?? ''
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}
