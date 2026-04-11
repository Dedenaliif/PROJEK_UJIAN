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
            $user = Auth::user();
            switch ($user->role) {
                case 'admin':
                    if (!Siswa::where('user_id', $user->id)->exists()) {

                        return redirect()->route('datadiri.index')->with('warning', 'Silakan lengkapi data diri Anda terlebih dahulu!');
                    }
                    return redirect()->route('dashboard');
                case 'guru':
                    return redirect()->route('dashboard');
                case 'siswa':
                    if (!Siswa::where('user_id', $user->id)->exists()) {

                        return redirect()->route('datadiri.index')->with('warning', 'Silakan lengkapi data diri Anda terlebih dahulu!');
                    }
                    return redirect()->route('dashboard');
                default:
                    Auth::logout();
                    return redirect('/')->withErrors(['username' => 'Unauthorized role.']);
            }
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
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
