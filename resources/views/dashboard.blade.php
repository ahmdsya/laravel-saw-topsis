@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Dashboard</h6>
            </div>
            <div class="card-body">
                <p>
                    TOPSIS adalah metode pengambilan keputusan multikriteria yang pertama kali diperkenalkan oleh Yoon
                    dan
                    Hwang tahun 1981. Menurut Hwang dan Zeleny, TOPSIS didasarkan pada konsep
                    dimana alternatif terpilih yang terbaik tidak hanya memiliki jarak terpendek dari solusi ideal
                    positif,
                    namun juga memiliki jarak terpanjang dari solusi ideal negatif dari sudut pandang geometris dengan
                    menggunakan jarak euclidean untuk menentukan kedekatan relatif dari suatu alternatif dengan solusi
                    optimal.
                </p>

                <p>
                    Metode Simple Additive Weighting (SAW) sering juga dikenal istilah metode penjumlahan terbobot.
                    Konsep dasar metode SAW adalah mencari penjumlahan terbobot dari rating kinerja pada setiap
                    alternatif pada semua atribut.
                </p>
            </div>
        </div>
    </div>
</div>

@endsection
