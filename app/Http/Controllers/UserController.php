<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        // 3. Lewati baris pertama (header)
        fgetcsv($handle, 1000, ";");

        $successCount = 0;

        // 4. Looping isi file
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

            if (count($data) < 3) continue;

            $namaLengkap = trim($data[0]);
            $nis         = trim($data[1]);
            $jurusanRaw  = trim($data[2]); // Contoh: "Teknik Komputer Jaringan"

            if (empty($namaLengkap) || empty($nis) || empty($jurusanRaw)) continue;

            // --- AMBIL NAMA BELAKANG ---
            $pecahNama = explode(' ', preg_replace('/\s+/', ' ', $namaLengkap));
            $namaBelakang = count($pecahNama) > 1 ? end($pecahNama) : $pecahNama[0];

            // --- 🔥 BARU: LOGIKA MENYINGKAT JURUSAN ---
            // Pecah jurusan berdasarkan spasi (misal: ["Teknik", "Komputer", "Jaringan"])
            $pecahJurusan = explode(' ', preg_replace('/\s+/', ' ', $jurusanRaw));
            $singkatanJurusan = '';

            // Daftar kata sambung yang ingin diabaikan/dibuang (gunakan lowercase)
            $kataDiabaikan = ['dan', 'atau', '&', 'of', 'in'];

            foreach ($pecahJurusan as $kataJurusan) {
                // Ubah dulu ke lowercase untuk pengecekan yang akurat
                $kataBersih = strtolower(trim($kataJurusan));

                // Jika kata tersebut ada di dalam daftar diabaikan, lewati (jangan ambil hurufnya)
                if (in_array($kataBersih, $kataDiabaikan) || empty($kataBersih)) {
                    continue;
                }

                // Ambil huruf pertama dari kata yang lolos seleksi
                $singkatanJurusan .= substr($kataBersih, 0, 1);
            }

            // Hasil akhir dipastikan lowercase (misal: "tkj")
            $singkatanJurusan = strtolower($singkatanJurusan);

            // --- FORMAT USERNAME ---
            // Hasilnya akan menjadi: 12345.permana.tkj
            $usernameRaw = $nis . '.' . $namaBelakang . '.' . $singkatanJurusan;
            $username = Str::slug($usernameRaw, '.');

            // Proteksi duplikat username
            $usernameAsli = $username;
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $usernameAsli . $counter;
                $counter++;
            }

            // 6. Simpan ke Database
            DB::transaction(function () use ($username, $namaLengkap, $nis, $jurusanRaw) {

                $user = User::create([
                    'username' => $username,
                    'password' => Hash::make($username),
                    'role'     => 'siswa',
                ]);

                // Di tabel siswas, kita tetap simpan nama jurusan aslinya ("Teknik Komputer Jaringan")
                DB::table('siswas')->insert([
                    'user_id'    => $user->id,
                    'nama_siswa' => $namaLengkap,
                    'nis'        => $nis,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

            $successCount++;
        }

        fclose($handle);

        return back()->with('success', "Berhasil men-generate $successCount data user dengan format nis.namabelakang.singkatan_jurusan!");
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
            fputcsv($file, ['nama'], ',');

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadUserCsv(Request $request)
    {
        $query = User::with('siswa')->where('role', 'siswa');

        // 1. Tentukan suffix nama file berdasarkan jurusan yang dipilih
        $suffixJurusan = 'Semua_Jurusan';

        if ($request->has('jurusan') && !empty($request->jurusan)) {
            $query->where('username', 'LIKE', '%.' . $request->jurusan . '%');

            // Jika ada jurusan terpilih, ubah suffix menjadi huruf kapital (misal: TJKT)
            $suffixJurusan = strtoupper($request->jurusan);
        }

        $users = $query->get();

        // 2. MASUKKAN JURUSAN KE DALAM NAMA FILE
        // Hasilnya akan menjadi: Data_User_Siswa_TJKT.csv atau Data_User_Siswa_Semua_Jurusan.csv
        $filename = "Data_User_Siswa_" . $suffixJurusan . ".csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM agar karakter terbaca dengan benar di Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // 🔥 TRIK UTAMA: Paksa Excel memisahkan kolom menggunakan TITIK KOMA (;)
            // Cara ini otomatis membuat kolom Excel menjadi proporsional (tidak dempet) tanpa pop-up error
            fwrite($file, "sep=;\n");

            // HEADER
            fputcsv($file, [
                'Nama Siswa',
                'Username',
                'Password'
            ], ';');

            // DATA LOOPING
            foreach ($users as $user) {
                fputcsv($file, [
                    optional($user->siswa)->nama_siswa ?? '-',
                    $user->username,
                    $user->username,
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
