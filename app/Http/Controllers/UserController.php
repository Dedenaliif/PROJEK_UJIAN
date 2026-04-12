<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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

        return redirect()->route('user.index')->with('success','Data Berhasil Ditambahkan');
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

        return redirect()->route('user.index')->with('success','Data Berhasil Diubah');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }
}
