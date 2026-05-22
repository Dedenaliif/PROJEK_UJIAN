<?php

namespace App\Http\Controllers;

use App\Models\Sesi;
use Illuminate\Http\Request;

class SesiController extends Controller
{
    public function index()
    {
        $sesis = Sesi::orderBy('no_sesi')->get();
        return view('sesi.index', compact('sesis'));
    }

    public function store(Request $request)
    {
        Sesi::create($request->all());

        return back()->with('success', 'Sesi berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $sesi = Sesi::findOrFail($id);
        $sesi->update($request->all());

        return back()->with('success', 'Sesi berhasil diupdate');
    }

    public function destroy($id)
    {
        Sesi::findOrFail($id)->delete();

        return back()->with('success', 'Sesi berhasil dihapus');
    }
}
