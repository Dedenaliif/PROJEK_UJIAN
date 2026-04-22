<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataDiriController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $siswa = \App\Models\Siswa::where('user_id', $userId)->first();

        $kelas = \App\Models\Kelas::all();
        $jurusan = \App\Models\Jurusan::all();

        return view('datadiri.index', compact('siswa', 'kelas', 'jurusan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_siswa' => 'required',
            'nis' => 'required',
            'kelas' => 'required',
            'jurusan' => 'required',
        ]);

        Siswa::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'nama_siswa' => $request->nama_siswa,
                'nis' => $request->nis,
                'kelas_id' => $request->kelas,
                'jurusan_id' => $request->jurusan,
            ]
        );

        return back()->with('success', 'Data berhasil disimpan!');
    }
}
