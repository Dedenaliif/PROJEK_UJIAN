<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Models\Ujian;

class BuatUjianController extends Controller
{
    public function index()
    {
        $ujians = Ujian::withCount([
            'pertanyaans as total_word' => function ($q) {
                $q->where('tipe', 'word');
            },
            'pertanyaans as total_excel' => function ($q) {
                $q->where('tipe', 'excel');
            }
        ])->get();

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
