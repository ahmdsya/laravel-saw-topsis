<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Karyawan;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use App\Models\RiwayatPerhitungan;
use App\Models\NilaiAlternatifKriteria;

class TambahPerhitunganController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::all();
        $karyawans = Karyawan::all();

        return view('perhitungan.tambah', compact('kriterias', 'karyawans'));
    }

    public function store(Request $request)
    {
        $dataForm = json_decode($request->data);

        $idRiwayat = $this->insertRiawayat();

        foreach($dataForm as $data){
            $id_karyawan = $data->IdKaryawan;

            $arrIdKriteria = $data->IdKriteria;
            $arrNilai = $data->Nilai;

            for ($i=0; $i < count($arrIdKriteria); $i++) { 
                $id_kriteria = $arrIdKriteria[$i];
                $nilai = $arrNilai[$i];

                $insert = new NilaiAlternatifKriteria;
                $insert->id_riwayat_perhitungan = $idRiwayat;
                $insert->id_karyawan = $id_karyawan; 
                $insert->id_kriteria = $id_kriteria; 
                $insert->nilai = $nilai; 
                $insert->save();
            }
        }

        return response()->json(['msg' => 'OK', 'success' => true, 'id_riwayat' => $idRiwayat], 200);
    }

    public function insertRiawayat()
    {
        $dateNow = Carbon::now()->format('Y-m-d H:i:s');

        $riwayat = new RiwayatPerhitungan;
        $riwayat->tanggal_perhitungan = $dateNow;
        $riwayat->save();

        return $riwayat->id;
    }
}
