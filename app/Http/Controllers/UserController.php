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

    private function generateUsername($nama)
    {
        $nama = trim($nama);
        $parts = explode(' ', $nama);

        $username = strtolower($parts[0]);

        if (count($parts) > 1) {
            for ($i = 1; $i < count($parts); $i++) {
                $username .= '.' . strtolower(substr($parts[$i], 0, 1));
            }
        }

        return $username;
    }

    public function store(Request $request)
    {
       $request->validate([
            'username' => 'required|string'
        ]);

        $generatedUsername = $this->generateUsername($request->username);

        User::create([
            'username' => $generatedUsername,
            'password' => Hash::make($generatedUsername),
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

        $generatedUsername = $this->generateUsername($request->username);

        $user->update([
            'username' => $generatedUsername,
            'role' => $request->role,
            'password' => Hash::make($generatedUsername)
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

    public function downloadUserCsv()
    {
        $filename = "data_user_siswa.csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        // hanya ambil user role siswa
        $users = User::where('role', 'siswa')->get();

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');

            // header csv
            fputcsv($file, ['username', 'password'], ';');

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->username,
                    $user->username // password = username
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}
