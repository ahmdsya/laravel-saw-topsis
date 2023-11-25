<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiwayatPerhitungan;
use App\Models\NilaiAlternatifKriteria;

class RiwayatPerhitunganController extends Controller
{
    public function index()
    {
        return view('perhitungan.riwayat', [
            'data' => RiwayatPerhitungan::orderBy('id', 'desc')->get()
        ]);
    }

    public function delete($id)
    {
        $deleteNilai = NilaiAlternatifKriteria::where('id_riwayat_perhitungan', $id)->delete();

        if($deleteNilai){
            $riwayat = RiwayatPerhitungan::find($id);
            $riwayat->delete();
        }

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}
