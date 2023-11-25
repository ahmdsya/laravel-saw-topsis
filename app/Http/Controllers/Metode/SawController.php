<?php

namespace App\Http\Controllers\Metode;

use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\NilaiAlternatifKriteria;

class SawController extends Controller
{
    private $id_riwayat;

    public function __construct($id_riwayat)
    {
        $this->id_riwayat = $id_riwayat;
    }

    public function result()
    {
        $matriksNormalisasi = $this->matriksNormalisasi();
        $nilaiPreferensi = $this->nilaiPreferensi($matriksNormalisasi['matriks']);

        return $result = [
            'NilaiMinMax' => $matriksNormalisasi['nilai_min_max'],
            'matriksNormalisasi' => $matriksNormalisasi['matriks'],
            'nilaiPreferensi' => $nilaiPreferensi
        ];
    }

    private function matriksNormalisasi()
    {
        // $data = NilaiAlternatifKriteria::where('id_riwayat_perhitungan', $this->id_riwayat)->get();
        $data = NilaiAlternatifKriteria::select('karyawans.name as alternatif', 'kriterias.kriteria', 'nilai_alternatif_kriterias.nilai')
                ->join('karyawans', 'karyawans.id', '=', 'nilai_alternatif_kriterias.id_karyawan')
                ->join('kriterias', 'kriterias.id', '=', 'nilai_alternatif_kriterias.id_kriteria')
                ->where('nilai_alternatif_kriterias.id_riwayat_perhitungan', '=', $this->id_riwayat)
                ->get();

        $dataKriteria = $this->getKriteria();
        $kriteria = $dataKriteria['kriteria'];

        $X = array();
        $min_max = array();

        foreach($data as $row){
            $j = $row->kriteria;
            $v = $row->nilai;
            $X[$row->alternatif][$j] = $v;
            $min_max[$j][] = $v;
        }

        $R = array();
        foreach($X as $i => $x_i){
            $R[$i] = array();
            foreach($x_i as $j => $x_ij){
                if($kriteria[$j][1] == 'Cost')
                    $R[$i][$j] = min($min_max[$j]) / $x_ij;
                else
                    $R[$i][$j] = $x_ij / max($min_max[$j]);
            }
        }

        return $data = [
            'nilai_min_max' => $min_max,
            'matriks' => $R
        ];
    }

    private function nilaiPreferensi($matriks)
    {
        $dataKriteria = $this->getKriteria();
        $bobot = $dataKriteria['bobot'];

        $P = array();
        foreach($matriks as $i => $r_i){
            $P[$i] = 0;
            foreach($r_i as $j => $r_ij){
                $P[$i] += $bobot[$j] * $r_ij;
            }
        }

        return $P;
    }

    private function getKriteria()
    {
        $data = NilaiAlternatifKriteria::select('kriterias.*')
                ->join('kriterias', 'kriterias.id', '=', 'nilai_alternatif_kriterias.id_kriteria')
                ->where('nilai_alternatif_kriterias.id_riwayat_perhitungan', '=', $this->id_riwayat)
                ->distinct()
                ->get();

        $kriteria = array();
        $bobot = array();

        foreach($data as $row){
            $kriteria[$row->kriteria] = array($row->kriteria, $row->tipe);
            $bobot[$row->kriteria] = $row->bobot;
        }

        return $x = [
            'kriteria' => $kriteria,
            'bobot' => $bobot
        ];
    }
}
