<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('user.index', compact('users'));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role' => $request->role
        ]);

        return redirect()->route('user.index')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('kelas'));
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'username' => $request->username,
            'role' => $request->role,
            'password' => $request->password
        ]);

        return redirect()->route('user.index')->with('success', 'Data Berhasil Diubah');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }
    public function importCsv(Request $request)
    {
        // 1. Validasi file
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        // 2. Buka file
        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');

        // 3. Lewati baris pertama (header: nama, jurusan)
        fgetcsv($handle, 1000, ";");

        $successCount = 0;

        // 4. Looping isi file
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

            // Proteksi: Pastikan baris ini memiliki minimal 2 kolom agar tidak error Undefined Key 1
            if (count($data) < 3) continue;

            $namaLengkap = trim($data[0]);
            $nis = trim($data[1]);
            $jurusan = trim($data[2]);

            if (empty($namaLengkap) || empty($nis) || empty($jurusan)) continue;
            // 5. 🔥 LOGIKA SINGKATAN JURUSAN (Ambil huruf kapital awal)
            // Mencari semua huruf besar (A-Z) di dalam string kalimat jurusan
            preg_match_all('/[A-Z]/', $jurusan, $matches);
            // Menggabungkan huruf-huruf besar tersebut menjadi satu kata (Misal: R, P, L menjadi RPL)
            $jurusanSingkat = implode('', $matches[0]);

            // Jika karena alasan tertentu tidak ada huruf kapital, gunakan 3 huruf pertama sebagai cadangan
            if (empty($jurusanSingkat)) {
                $jurusanSingkat = substr(str_replace(' ', '', $jurusan), 0, 3);
            }
            // 5. Pecah nama lengkap berdasarkan spasi untuk mengambil nama depan
            $pecahNama = explode(' ', trim($namaLengkap));
            $namaDepan = $pecahNama[0];

            // 6. Gabungkan Nama Depan + Jurusan dengan pemisah titik (.)
            // Str::slug akan otomatis mengubah huruf besar jadi kecil dan spasi jadi tanda strip (-)
            $username = Str::slug($nis . '.' . $namaDepan . '-' . $jurusanSingkat, '.');

            // 7. Simpan ke Database
            User::updateOrCreate(
                ['username' => $username], // Cek unik berdasarkan kombinasi username baru
                [
                    'password' => Hash::make($username), // Password default sama dengan username
                    'role'     => 'siswa', // Otomatis set sebagai siswa (bukan admin)
                    // 'jurusan'  => $jurusan, // Aktifkan jika tabel users Anda punya kolom jurusan
                ]
            );

            $successCount++;
        }

        fclose($handle);

        return back()->with('success', "Berhasil men-generate $successCount data user dengan format nama depan + jurusan!");
    }

    public function downloadTemplate()
    {
        $filename = "template_siswa.csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $callback = function () {

            $file = fopen('php://output', 'w');

            // 🔥 HEADER
            fputcsv($file, ['nama', 'nis', 'jurusan'], ';');

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
