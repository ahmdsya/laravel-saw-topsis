<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Kriteria;
use App\Models\HasilAkhir;
use Illuminate\Http\Request;
use App\Models\NilaiAlternatifKriteria;
use App\Http\Controllers\Metode\SawController;
use App\Http\Controllers\Metode\TopsisController;

class HasilPerhitunganController extends Controller
{
    public function index($id_riwayat)
    {
        $karyawans = NilaiAlternatifKriteria::select('karyawans.*')
                    ->join('karyawans', 'karyawans.id', '=', 'nilai_alternatif_kriterias.id_karyawan')
                    ->where('nilai_alternatif_kriterias.id_riwayat_perhitungan', '=', $id_riwayat)
                    ->distinct()
                    ->get();

        $kriterias = NilaiAlternatifKriteria::select('kriterias.*')
                    ->join('kriterias', 'kriterias.id', '=', 'nilai_alternatif_kriterias.id_kriteria')
                    ->where('nilai_alternatif_kriterias.id_riwayat_perhitungan', '=', $id_riwayat)
                    ->distinct()
                    ->get();

        $matrixNilai = $this->setMatriksKeputusan($id_riwayat);

        $saw = $this->getSawMethode($id_riwayat);
        $topsis = $this->getTopsisMethode($id_riwayat);
        $penggabungan = $this->getHasilPenggabungan($karyawans, $saw['nilaiPreferensi'], $topsis['nilaiPreferensi']);
        $ranking = $this->getRanking($karyawans, $id_riwayat, $penggabungan);

        // return $ranking;

        return view('perhitungan.hasil', compact('karyawans','kriterias','matrixNilai','topsis','saw', 'penggabungan','ranking'));
    }

    protected function setMatriksKeputusan($id_riwayat)
    {
        $data = NilaiAlternatifKriteria::select('karyawans.name as alternatif', 'kriterias.kriteria', 'nilai_alternatif_kriterias.nilai')
                ->join('karyawans', 'karyawans.id', '=', 'nilai_alternatif_kriterias.id_karyawan')
                ->join('kriterias', 'kriterias.id', '=', 'nilai_alternatif_kriterias.id_kriteria')
                ->where('nilai_alternatif_kriterias.id_riwayat_perhitungan', '=', $id_riwayat)
                ->get();
        
        $outputArray = [];

        foreach ($data as $item) {
            $alternatif = $item->alternatif;
            $kriteria = $item->kriteria;
            $nilai = $item->nilai;
        
            if (!isset($outputArray[$alternatif])) {
                $outputArray[$alternatif] = [
                    "alternatif" => $alternatif,
                    "details" => []
                ];
            }
        
            $outputArray[$alternatif]["details"][] = [
                "kriteria" => $kriteria,
                "nilai" => $nilai
            ];
        }
        
        $outputArray = array_values($outputArray);

        return $outputArray;
    }

    protected function getHasilPenggabungan($karyawans, $saw, $topsis)
    {
        $data = array();

        foreach($karyawans as $karyawan)
        {
            $data[$karyawan->name] = ($saw[$karyawan->name] + $topsis[$karyawan->name]) / 2;
        }

        return $data;
    }

    protected function getRanking($alternatif, $id_riwayat, $penggabungan)
    {
        $count = HasilAkhir::where('id_riwayat_perhitungan', $id_riwayat)->count();
        $alternatif = NilaiAlternatifKriteria::where('id_riwayat_perhitungan', $id_riwayat)->select('id_riwayat_perhitungan', 'id_karyawan')->distinct('id_karyawan')->get();

        if($count == 0)
        {
            foreach($alternatif as $row)
            {

                $karyawan = Karyawan::where('id', $row->id_karyawan)->first();

                $insert = new HasilAkhir;
                $insert->id_riwayat_perhitungan = $id_riwayat;
                $insert->id_karyawan = $karyawan->id;
                $insert->nilai = $penggabungan[$karyawan->name];
                $insert->save();
            }
        }

        $data = HasilAkhir::select('karyawans.name as alternatif', 'hasil_akhirs.nilai')
                    ->join('karyawans', 'karyawans.id', '=', 'hasil_akhirs.id_karyawan')
                    ->where('hasil_akhirs.id_riwayat_perhitungan', '=', $id_riwayat)
                    ->orderBy('nilai', 'desc')
                    ->get();

        return $data;
    }

    protected function getTopsisMethode($id_riwayat)
    {
        $topsis = new TopsisController($id_riwayat);
        $result = $topsis->result();
        return $result;
    }

    protected function getSawMethode($id_riwayat)
    {
        $saw = new SawController($id_riwayat);
        $result = $saw->result();
        return $result;
    }
}
