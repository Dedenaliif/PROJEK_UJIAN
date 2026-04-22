<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate(); // 🔥 WAJIB di sini

            $user = Auth::user();

            // ================= ROLE REDIRECT =================
            switch ($user->role) {

                // ================= ADMIN =================
                case 'admin':
                    return redirect()->to('/dashboard');

                // ================= PENGUJI =================
                case 'penguji':
                    return redirect()->to('/penguji/ujian');
                case 'pengawas':
                    return redirect()->to('/pengawas/monitoring');

                // ================= SISWA =================
                case 'siswa':

                    // cek data diri
                    // $sudahIsi = Siswa::where('user_id', $user->id)->exists();

                    // if (!$sudahIsi) {
                    //     return redirect()->route('datadiri.index')
                    //         ->with('warning', 'Silakan lengkapi data diri terlebih dahulu!');
                    // }

                    return redirect()->to('/siswa/datadiri');

                // ================= DEFAULT =================
                default:
                    Auth::logout();
                    return redirect('/')
                        ->withErrors(['username' => 'Role tidak dikenali']);
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
