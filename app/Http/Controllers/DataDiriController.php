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
        $siswa = Siswa::where('user_id', Auth::id())->first();
        $request->validate(
            [

                'nama_siswa' => 'required',
                'nis' => 'required|unique:siswas,nis,' . Auth::id() . ',user_id',
                'kelas' => $siswa ? 'nullable' : ' required',
                'jurusan' => $siswa? 'nullable': 'required',
                'no_hp' => $siswa? 'nullable': 'required',
                'email' => $siswa? 'nullable': 'required',
                'nik' => $siswa? 'nullable': 'required',
            ],
            [
                'nis.unique' => 'NIS sudah digunakan oleh siswa lain.',
            ]
        );

        Siswa::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'nama_siswa' => $request->nama_siswa,
                'nis' => $request->nis,
                'kelas_id' =>$request->kelas ?? ($siswa->kelas_id),
                'jurusan_id' => $request->jurusan ?? ($siswa->jurusan_id),
                'no_hp' =>  $request->no_hp ?? ($siswa->no_hp),
                'email' => $request->email ?? ($siswa->email),
                'nik' => $request->nik ?? ($siswa->nik),
            ]
        );

        return back()->with('success', 'Data berhasil disimpan!');
    }
}
