<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function Index()
    {
        return view('kriteria.index', [
            'data' => Kriteria::orderBy('id', 'desc')->get()
        ]);
    }

    public function save(Request $request)
    {
        $kriteria = new Kriteria;
        $kriteria->kriteria = $request->kriteria;
        $kriteria->bobot = $request->bobot;
        $kriteria->tipe = $request->tipe;
        $kriteria->save();

        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }

    public function update(Request $request)
    {
        $kriteria = Kriteria::find($request->id);
        $kriteria->kriteria = $request->kriteria;
        $kriteria->bobot = $request->bobot;
        $kriteria->tipe = $request->tipe;
        $kriteria->save();

        return redirect()->back()->with('success', 'Data berhasil diubah.');
    }

    public function delete($id)
    {
        $kriteria = Kriteria::find($id);
        $kriteria->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}
