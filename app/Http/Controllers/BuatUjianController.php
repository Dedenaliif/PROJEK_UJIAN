<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

use App\Models\PercobaanUjian;
use App\Models\Ujian;
use Illuminate\Support\Facades\Auth;

class BuatUjianController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $ujians = Ujian::withCount([
            'pertanyaans as total', 'percobaanUjians as jumlah_percobaan' => function ($query) use ($userId){
                $query->where('user_id', $userId)->where('status','selesai');
            }
        ])->get();
        // $percobaanujian = PercobaanUjian::where('user_id', Auth::user()->id)->where('ujian_id', $ujians->id)->where('status', 'selesai')->count();
        return view('ujian.index', compact('ujians'));
    }



    public function create()
    {
        return view('ujian.create');
    }



    public function store()
    {

        // Validasi data yang diterima dari form
        $validatedData = request()->validate([
            'judul' => 'required',
            'tipe' => 'required',
            'deskripsi' => 'nullable',
            'waktu' => 'required',
            'max_percobaan' => 'required',
            'waktu_mulai' => 'nullable|date',
            'waktu_selesai' => 'nullable|date',
        ]);

        // Simpan data ujian ke database
        $ujian = \App\Models\Ujian::create($validatedData);

        // Redirect ke halaman daftar ujian atau halaman detail ujian
        return redirect()->route('ujian.create')->with('success', 'Ujian berhasil dibuat!');
    }
}
