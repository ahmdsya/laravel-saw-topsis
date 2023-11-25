@extends('layouts.app')

@section('title', 'Hasil Perhitungan')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Hasil Perhitungan</h1>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Matrix Keputusan (X)</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th colspan="2" class="text-center">Alternatif (Karyawan)</td>
                                <th colspan="{{$kriterias->count()}}" class="text-center">Kriteria / Bobot</th>
                            </tr>
                            <tr>
                                <th>No</th>
                                <th>Nama Karyawan</th>
                                @foreach ($matrixNilai[0]['details'] as $index => $item)
                                <th>{{$item['kriteria']." (C".($index+1) .")"}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($matrixNilai as $index => $row)
                            <tr>
                                <td>{{$index+1}}</td>
                                <td>{{$row['alternatif']}}</td>
                                @foreach ($row['details'] as $item)
                                <td>{{$item['nilai']}}</td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- SAW --}}
        <div class="card shadow mb-4">
            <a href="#saw" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true"
                aria-controls="collapseCardExample">
                <h6 class="m-0 font-weight-bold text-primary">Hasil Perhitungan SAW</h6>
            </a>
            <div class="collapse" id="saw">
                <div class="card-body">
                    <h5>[1] Menentukan Nilai Max/Min untuk setiap Kriteria</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-5" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Atribut</th>
                                    <th>Tipe</th>
                                    <th>Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kriterias as $index => $row)
                                <tr>
                                    <td>{{$row->kriteria." (C".($index+1).")"}}</td>
                                    <td>{{$row->tipe}}</td>
                                    <td>{{$row->tipe == 'Cost' ? 'min' : 'max'}}</td>
                                    <td>
                                        @if ($row->tipe == 'Cost')
                                        {{ min($saw['NilaiMinMax'][$row->kriteria]) }}
                                        @else
                                        {{ max($saw['NilaiMinMax'][$row->kriteria]) }}
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <h5>[2] Matriks Ternormalisasi (R)</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-5" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Alternatif</th>
                                    @foreach (reset($saw['matriksNormalisasi']) as $kriteria => $value)
                                    <th>{{$kriteria}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($saw['matriksNormalisasi'] as $alternatif => $data)
                                <tr>
                                    <td>{{$alternatif}}</td>
                                    @foreach ($data as $nilai)
                                    <td>{{$nilai}}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <h5>[3] Hasil Preferensi P</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-5" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Karyawan</th>
                                    <th>Hasil</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($saw['nilaiPreferensi'] as $alternatif => $hasil)
                                <tr>
                                    <td>{{$alternatif}}</td>
                                    <td>{{$hasil}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- END SAW --}}


        {{-- TOPSIS --}}
        <div class="card shadow mb-4">
            <a href="#topsis" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true"
                aria-controls="collapseCardExample">
                <h6 class="m-0 font-weight-bold text-primary">Hasil Perhitungan TOPSIS</h6>
            </a>
            <div class="collapse" id="topsis">
                <div class="card-body">
                    <h5>[1] Matriks Normalisasi (R)</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-5" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Alternatif</th>
                                    @foreach (reset($topsis['matriksNormalisasi']) as $kriteria => $value)
                                    <th>{{$kriteria}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topsis['matriksNormalisasi'] as $alternatif => $data)
                                <tr>
                                    <td>{{$alternatif}}</td>
                                    @foreach ($data as $nilai)
                                    <td>{{$nilai}}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <h5>[2] Matrik Normalisasi Terbobot (Y)</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-5" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Alternatif</th>
                                    @foreach (reset($topsis['matriksNormalisasiTerbobot']) as $kriteria => $value)
                                    <th>{{$kriteria}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topsis['matriksNormalisasiTerbobot'] as $alternatif => $data)
                                <tr>
                                    <td>{{$alternatif}}</td>
                                    @foreach ($data as $nilai)
                                    <td>{{$nilai}}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <h5>[3] Solusi Ideal (A)</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-5" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Plus (A+)</th>
                                    <th>Min (A-)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kriterias as $item)
                                    <tr>
                                        <td>{{$item->kriteria}}</td>
                                        <td>{{$topsis['solusiIdeal']['A_max'][$item->kriteria]}}</td>
                                        <td>{{$topsis['solusiIdeal']['A_min'][$item->kriteria]}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <h5>[4] Jarak Solusi Ideal (D)</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-5" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Plus (D+)</th>
                                    <th>Min (D-)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($karyawans as $item)
                                    <tr>
                                        <td>{{$item->name}}</td>
                                        <td>{{$topsis['jarakSolusiIdeal']['D_plus'][$item->name]}}</td>
                                        <td>{{$topsis['jarakSolusiIdeal']['D_min'][$item->name]}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <h5>[5] Hasil Preferensi (P)</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-5" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Karyawan</th>
                                    <th>Hasil</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topsis['nilaiPreferensi'] as $alternatif => $hasil)
                                <tr>
                                    <td>{{$alternatif}}</td>
                                    <td>{{$hasil}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- END TOPSIS --}}

        <div class="card shadow mb-4">
            <a href="#penggabunganMetode" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true"
                aria-controls="collapseCardExample">
                <h6 class="m-0 font-weight-bold text-primary">Hasil Penggabungan (SAW X TOPSIS)</h6>
            </a>
            <div class="collapse show" id="penggabunganMetode">
                <div class="card-body">
                    <table class="table table-bordered table-sm mb-5" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Alternatif</th>
                                <th>Hasil SAW (X1)</th>
                                <th>Hasil TOPSIS (X2)</th>
                                <th>
                                    Hasil Penggabungan
                                    <small class="d-block">
                                        (X1 + X2) / 2
                                    </small>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($karyawans as $item)
                                <tr>
                                    <td>{{$item->name}}</td>
                                    <td>{{$saw['nilaiPreferensi'][$item->name]}}</td>
                                    <td>{{$topsis['nilaiPreferensi'][$item->name]}}</td>
                                    <td>{{$penggabungan[$item->name]}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <a href="#ranking" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true"
                aria-controls="collapseCardExample">
                <h6 class="m-0 font-weight-bold text-primary">Perankingan</h6>
            </a>
            <div class="collapse show" id="ranking">
                <div class="card-body">
                    <table class="table table-bordered table-sm mb-5" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Alternatif</th>
                                <th>Hasil</th>
                                <th>Ranking</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ranking as $index => $row)
                                <tr>
                                    <td>{{$row->alternatif}}</td>
                                    <td>{{$row->nilai}}</td>
                                    <td>{{$index+1}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
