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
        $kelas = Kelas::all();
        $jurusan = Jurusan::all();
        return view('datadiri.index', compact('kelas', 'jurusan'));
    }
    public function store(Request $request)
    {
        // dd($request->all());
        // Validasi data yang diterima dari form
        $validatedData = $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'nis' => 'required|string|max:20|unique:siswas,nis',
            'kelas' => 'required|exists:kelas,id',
            'jurusan' => 'required|exists:jurusans,id',
        ]);

        Siswa::create([
            'user_id' => Auth::id(),
            'nama_siswa' => $validatedData['nama_siswa'],
            'nis' => $validatedData['nis'],
            'kelas_id' => $validatedData['kelas'],
            'jurusan_id' => $validatedData['jurusan'],
        ]);

        return redirect()->route('dashboard')->with('success', 'Data diri berhasil disimpan!');
    }   
}
