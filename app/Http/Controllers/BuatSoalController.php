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

    public function create($ujianId)
    {
        $ujian = Ujian::findOrFail($ujianId);

        $soals = Pertanyaan::where('ujian_id', $ujianId)->get();

        return view('ujian.soal', compact('ujian', 'soals'));
    }

    public function store(Request $request, $ujianId)
    {
        if (!$request->has('text_pertanyaan') || empty($request->text_pertanyaan)) {
            return back()->with('error', 'Soal tidak boleh kosong!');
        }

        $jumlahInput = count($request->text_pertanyaan);

        $jumlahSoalSekarang = Pertanyaan::where('ujian_id', $ujianId)->count();

        if (($jumlahSoalSekarang + $jumlahInput) > 30) {
            $sisa = 30 - $jumlahSoalSekarang;

            return back()->with('error',
                $sisa > 0
                    ? "Maksimal 30 soal. Sisa $sisa lagi."
                    : "Soal sudah penuh (30)"
            );
        }

        for ($i = 0; $i < $jumlahInput; $i++) {

            if (
                empty($request->text_pertanyaan[$i]) ||
                empty($request->opsi_a[$i]) ||
                empty($request->opsi_b[$i]) ||
                empty($request->opsi_c[$i]) ||
                empty($request->opsi_d[$i]) ||
                !isset($request->jawaban_benar[$i])
            ) {
                return back()->with('error', 'Semua field soal wajib diisi!');
            }

            Pertanyaan::create([
                'ujian_id' => $ujianId,
                'text_pertanyaan' => $request->text_pertanyaan[$i],
                'opsi_a' => $request->opsi_a[$i],
                'opsi_b' => $request->opsi_b[$i],
                'opsi_c' => $request->opsi_c[$i],
                'opsi_d' => $request->opsi_d[$i],
                'jawaban_benar' => $request->jawaban_benar[$i],
            ]);
        }

        return back()->with('success', 'Soal berhasil disimpan');
    }

    public function edit($ujianId, $id)
    {
        $ujian = Ujian::findOrFail($ujianId);

        $edit = Pertanyaan::where('id', $id)
            ->where('ujian_id', $ujianId)
            ->firstOrFail();

        $soals = Pertanyaan::where('ujian_id', $ujianId)->get();

        return view('ujian.soal', compact('ujian', 'soals', 'edit'));
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

        return redirect()
            ->route('soal.create', $soal->ujian_id) // 🔥 FIX
            ->with('success', 'Soal berhasil diupdate');
    }

    public function destroy($ujianId, $id)
    {
        $soal = Pertanyaan::where('id', $id)
            ->where('ujian_id', $ujianId)
            ->firstOrFail();

        $soal->delete();

        return redirect()
            ->route('soal.create', $ujianId)
            ->with('success', 'Soal berhasil dihapus');
    }
}
