<?php

namespace App\Http\Controllers\Metode;

use App\Models\Karyawan;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NilaiAlternatifKriteria;

class TopsisController extends Controller
{
    private $id_riwayat;

    public function __construct($id_riwayat)
    {
        $this->id_riwayat = $id_riwayat;
    }

    public function result()
    {
        $matriksNormalisasi = $this->matriksNormalisasi();
        $matriksNormalisasiTerbobot = $this->matriksNormalisasiTerbobot($matriksNormalisasi);
        $solusiIdeal = $this->solusiIdeal($matriksNormalisasiTerbobot);
        $jarakSolusiIdeal = $this->jarakSolusiIdeal($matriksNormalisasiTerbobot, $solusiIdeal);
        $nilaiPreferensi = $this->nilaiPreferensi($jarakSolusiIdeal);

        return $result = [
            'matriksNormalisasi' => $matriksNormalisasi,
            'matriksNormalisasiTerbobot' => $matriksNormalisasiTerbobot,
            'solusiIdeal' => $solusiIdeal,
            'jarakSolusiIdeal' => $jarakSolusiIdeal,
            'nilaiPreferensi' => $nilaiPreferensi
        ];
    }

    private function matriksNormalisasi()
    {
        $data = NilaiAlternatifKriteria::select('karyawans.name as alternatif', 'kriterias.kriteria', 'nilai_alternatif_kriterias.nilai')
                ->join('karyawans', 'karyawans.id', '=', 'nilai_alternatif_kriterias.id_karyawan')
                ->join('kriterias', 'kriterias.id', '=', 'nilai_alternatif_kriterias.id_kriteria')
                ->where('nilai_alternatif_kriterias.id_riwayat_perhitungan', '=', $this->id_riwayat)
                ->get();

        $dataKriteria = $this->getKriteria();
        $dataAlternatif = $this->getAlternatif();

        $kriteria = $dataKriteria['kriteria'];
        $alternatif = $dataAlternatif;
        $X = array();

        foreach($data as $row){
            $j = $row->kriteria;
            $v = $row->nilai;
            $X[$row->alternatif][$j] = $v;
        }

        $pembagi = array();

        foreach($kriteria as $kriteria_name => $value){
            $pembagi[$kriteria_name] = 0;

            foreach($alternatif as $alternatif_name => $a_value){
                $pembagi[$kriteria_name] = pow($X[$alternatif_name][$kriteria_name], 2);
            }
        }

        $R = array();
        foreach($X as $alternatif => $kriteria) {
            $R[$alternatif] = array();
            foreach($kriteria as $kriteria_name=>$nilai){
                $R[$alternatif][$kriteria_name]=$nilai/sqrt($pembagi[$kriteria_name]);
            }
        }

        return $R;
    }

    private function matriksNormalisasiTerbobot($matriks)
    {
        $dataKriteria = $this->getKriteria();
        $bobot = $dataKriteria['bobot'];

        $Y = array();

        foreach($matriks as $alternatif => $kriteria) {
            $Y[$alternatif] = array();
            foreach($kriteria as $kriteria_name => $nilai){
                $Y[$alternatif][$kriteria_name] = $nilai * $bobot[$kriteria_name];
            }
        }

        return $Y;
    }

    private function solusiIdeal($matriksTerbobot)
    {
        $A_max = array();
        $A_min = array();

        $dataKriteria = $this->getKriteria();
        $dataAlternatif = $this->getAlternatif();

        $kriteria = $dataKriteria['kriteria'];
        $alternatif = $dataAlternatif;

        foreach($kriteria as $kriteria_name => $a_kriteria) {
            $A_max[$kriteria_name] = 0;
            $A_min[$kriteria_name] = 100;

            foreach($alternatif as $alternatif_name => $nilai){
                if($A_max[$kriteria_name] < $matriksTerbobot[$alternatif_name][$kriteria_name]){
                    $A_max[$kriteria_name] = $matriksTerbobot[$alternatif_name][$kriteria_name];
                }
                if($A_min[$kriteria_name] > $matriksTerbobot[$alternatif_name][$kriteria_name]){
                    $A_min[$kriteria_name] = $matriksTerbobot[$alternatif_name][$kriteria_name];
                }
            }
        }

        return $data = [
            'A_max' => $A_max,
            'A_min' => $A_min
        ];
    }

    private function jarakSolusiIdeal($matriksTerbobot, $solusiIdeal)
    {
        $A_max = $solusiIdeal['A_max'];
        $A_min = $solusiIdeal['A_min'];

        $D_plus = array();
        $D_min = array();

        foreach($matriksTerbobot as $alternatif => $kriteria){
            $D_plus[$alternatif]=0;
            $D_min[$alternatif]=0;

            foreach($kriteria as $kriteria_name => $nilai ) {
                $D_plus[$alternatif] += pow($nilai - $A_max[$kriteria_name ], 2);
                $D_min[$alternatif] += pow($nilai - $A_min[$kriteria_name ], 2);
            }

            $D_plus[$alternatif] = sqrt($D_plus[$alternatif]);
            $D_min[$alternatif] = sqrt($D_min[$alternatif]);
        }

        return $data = [
            'D_plus' => $D_plus,
            'D_min' => $D_min
        ];
    }

    private function nilaiPreferensi($jarakSolusiIdeal)
    {
        $D_plus = $jarakSolusiIdeal['D_plus'];
        $D_min = $jarakSolusiIdeal['D_min'];

        $V = array();

        foreach($D_min as $alternatif => $d_min){
            $V[$alternatif] = $d_min/($d_min + $D_plus[$alternatif]);
        }

        return $V;
    }

    private function getKriteria()
    {
        // $data = Kriteria::all();

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

    private function getAlternatif()
    {
        $data = NilaiAlternatifKriteria::select('karyawans.*')
                ->join('karyawans', 'karyawans.id', '=', 'nilai_alternatif_kriterias.id_karyawan')
                ->where('nilai_alternatif_kriterias.id_riwayat_perhitungan', '=', $this->id_riwayat)
                ->distinct()
                ->get();

        $alternatif = array();

        foreach($data as $row){
            $alternatif[$row->name] = array($row->name);
        }

        return $alternatif;
    }
}
