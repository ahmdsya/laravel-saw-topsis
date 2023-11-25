<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\HasilPerhitunganController;
use App\Http\Controllers\TambahPerhitunganController;
use App\Http\Controllers\RiwayatPerhitunganController;


Route::middleware('auth')->group(function () {
    
    //route dashboard
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    //route karyawan
    Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan');
    Route::post('/karyawan', [KaryawanController::class, 'save'])->name('karyawan.save');
    Route::put('/karyawan', [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::delete('/karyawan/{id}', [KaryawanController::class, 'delete'])->name('karyawan.delete');

    //route kriteria
    Route::get('/kriteria', [KriteriaController::class, 'index'])->name('kriteria');
    Route::post('/kriteria', [KriteriaController::class, 'save'])->name('kriteria.save');
    Route::put('/kriteria', [KriteriaController::class, 'update'])->name('kriteria.update');
    Route::delete('/kriteria/{id}', [KriteriaController::class, 'delete'])->name('kriteria.delete');

    Route::get('/tambah-perhitungan', [TambahPerhitunganController::class, 'index'])->name('tambah.perhitungan');
    Route::post('/tambah-perhitungan', [TambahPerhitunganController::class, 'store'])->name('post.tambah.perhitungan');
    

    Route::get('/riwayat-perhitungan', [RiwayatPerhitunganController::class, 'index'])->name('riwayat.perhitungan');
    Route::delete('/riwayat-perhitungan/{id}', [RiwayatPerhitunganController::class, 'delete'])->name('riwayat.perhitungan.delete');
    
    Route::get('/hasil-perhitungan/{id_riwayat}', [HasilPerhitunganController::class, 'index'])->name('hasil.perhitungan');
    
    
    Route::get('/test', [TestController::class, 'index'])->name('test');

});


require __DIR__.'/auth.php';
