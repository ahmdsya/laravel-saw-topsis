<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function Index()
    {
        return view('karyawan.index', [
            'data' => Karyawan::orderBy('id', 'desc')->get()
        ]);
    }

    public function save(Request $request)
    {
        $karyawan = new Karyawan;
        $karyawan->name = $request->name;
        $karyawan->save();

        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }

    public function update(Request $request)
    {
        $karyawan = Karyawan::find($request->id);
        $karyawan->name = $request->name;
        $karyawan->save();

        return redirect()->back()->with('success', 'Data berhasil diubah.');
    }

    public function delete($id)
    {
        $karyawan = Karyawan::find($id);
        $karyawan->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}
