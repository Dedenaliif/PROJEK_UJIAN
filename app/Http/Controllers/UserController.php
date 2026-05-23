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
        fgetcsv($handle, 1000, ",");

        $successCount = 0;

        // 4. Looping isi file
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

            // dd($data);
            // Proteksi: Pastikan baris ini memiliki minimal 3 kolom (Nama, NIS, Jurusan)
            if (count($data) < 1) continue;

            $namaLengkap = trim($data[0]);

            if (empty($namaLengkap)) continue;

            // 5. 🔥 LOGIKA FORMAT USERNAME: nama_depan.singkatan_nama_belakang
            // Pecah nama lengkap berdasarkan spasi, hilangkan double space jika ada
            // Pecah nama & bersihkan spasi ganda
            $pecahNama = explode(' ', preg_replace('/\s+/', ' ', trim($namaLengkap)));

            $namaDepan = strtolower($pecahNama[0]);

            // Ambil semua kata setelah nama depan
            $namaBelakangArray = array_slice($pecahNama, 1);

            $inisialBelakang = '';

            foreach ($namaBelakangArray as $kata) {
                $inisialBelakang .= strtolower(substr($kata, 0, 1));
            }

            // Format username
            if ($inisialBelakang != '') {
                $usernameRaw = $namaDepan . '.' . $inisialBelakang;
            } else {
                $usernameRaw = $namaDepan;
            }

            // Bersihkan karakter aneh
            $username = Str::slug($usernameRaw, '.');
            // Tambahan proteksi jika terjadi duplikasi username (misal: Agung Pirdaus dan Agung Permana sama-sama agung.p)
            $usernameAsli = $username;
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $usernameAsli . $counter; // Menjadi agung.p1, agung.p2, dst jika bentrok
                $counter++;
            }

            // 6. Simpan ke Database (Tabel Users)
            User::updateOrCreate(
                ['username' => $username],
                [
                    'password' => Hash::make($username), // Password default disamakan dengan username baru
                    'role'     => 'siswa',
                ]
            );

            // Jika kamu juga ingin memasukkan data tersebut ke tabel 'Siswas', 
            // kamu bisa melanjutkannya di bawah ini menggunakan User ID hasil updateOrCreate.

            $successCount++;
        }

        fclose($handle);

        return back()->with('success', "Berhasil men-generate $successCount data user dengan format nama_depan.inisial!");
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

    public function downloadUserCsv()
    {
        $filename = "Data_User_Siswa.csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $users = User::with('siswa')
            ->where('role', 'siswa')
            ->get();

        $callback = function () use ($users) {

            $file = fopen('php://output', 'w');

            // UTF-8 BOM
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // HEADER
            fputcsv($file, [
                'Nama Siswa',
                'Username',
                'Password'
            ], ';');

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
