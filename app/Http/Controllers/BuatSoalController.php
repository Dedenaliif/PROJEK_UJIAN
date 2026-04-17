<?php

namespace App\Http\Controllers;

use App\Models\Pertanyaan;
use App\Models\Ujian;
use Illuminate\Http\Request;

class BuatSoalController extends Controller
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

    public function create(Request $request, $ujian)
    {
        $ujian = Ujian::withCount([
            'pertanyaans as total_word' => function ($q) {
                $q->where('tipe', 'word');
            },
            'pertanyaans as total_excel' => function ($q) {
                $q->where('tipe', 'excel');
            }
        ])->findOrFail($ujian);

        $tipe = $request->tipe;

        if (!$tipe) {
            return redirect()->back()->with('error', 'Pilih tipe soal dulu');
        }

        $soals = Pertanyaan::where('ujian_id', $ujian->id)
                    ->where('tipe', $tipe)
                    ->get();

        return view('ujian.soal', compact('ujian', 'soals', 'tipe'));
    }

    public function store(Request $request, $ujian)
    {
        $jumlah = count($request->text_pertanyaan);

        $total = Pertanyaan::where('ujian_id', $ujian)
            ->where('tipe', $request->tipe)
            ->count();

        if ($total + $jumlah > 30) {
            return back()->with('error', 'Melebihi batas 30 soal');
        }

        for ($i = 0; $i < $jumlah; $i++) {
            Pertanyaan::create([
                'ujian_id' => $ujian,
                'text_pertanyaan' => $request->text_pertanyaan[$i],
                'opsi_a' => $request->opsi_a[$i],
                'opsi_b' => $request->opsi_b[$i],
                'opsi_c' => $request->opsi_c[$i],
                'opsi_d' => $request->opsi_d[$i],
                'jawaban_benar' => $request->jawaban_benar[$i],
                'tipe' => $request->tipe,
                'skor' => 100/60
            ]);
        }

        return redirect()->route('soal.create', [
            'ujian' => $ujian,
            'tipe' => $request->tipe
        ])->with('success', 'Semua soal berhasil ditambahkan');
    }

    public function edit(Request $request, $ujian, $id)
    {
        $ujian = Ujian::findOrFail($ujian);
        $edit = Pertanyaan::findOrFail($id);

        $tipe = $edit->tipe ?? $request->tipe;

        $soals = Pertanyaan::where('ujian_id', $ujian->id)
                    ->where('tipe', $tipe)
                    ->get();

        return view('ujian.soal', compact('ujian', 'soals', 'edit', 'tipe'));
    }

    public function update(Request $request, $id)
    {
        $soal = Pertanyaan::findOrFail($id);

        $soal->update([
            'text_pertanyaan' => $request->text_pertanyaan,
            'opsi_a' => $request->opsi_a,
            'opsi_b' => $request->opsi_b,
            'opsi_c' => $request->opsi_c,
            'opsi_d' => $request->opsi_d,
            'jawaban_benar' => $request->jawaban_benar,
        ]);

        return redirect()->route('soal.create', [
            'ujian' => $soal->ujian_id,
            'tipe' => $soal->tipe
        ])->with('success', 'Soal berhasil diupdate');
    }

    public function destroy($ujian, $id)
    {
        $soal = Pertanyaan::findOrFail($id);

        $tipe = $soal->tipe; // simpan dulu sebelum delete

        $soal->delete();

        return redirect()->route('soal.create', [
            'ujian' => $ujian,
            'tipe' => $tipe
        ])->with('success', 'Soal berhasil dihapus');
    }
}
