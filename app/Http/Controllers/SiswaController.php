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
        $siswas = Siswa::with(['user', 'kelas', 'jurusan'])->get();
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
            'nama_siswa' => $request->nama_siswa
        ]);

        return redirect()->route('dashboard')->with('success', 'Data siswa berhasil disimpan!');
    }
}
