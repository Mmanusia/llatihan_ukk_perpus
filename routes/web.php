<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KoleksiController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PeminjamanController;

Route::get('/hidden', function () {
    return view('welcome');
});
Route::get('/', [BukuController::class, 'index']);

Route::get('/dashboard', [BukuController::class, 'index'])->name('dashboard');
Route::get('/index', [BukuController::class, 'index'])->name('index');
Route::redirect('/home', '/dashboard');

Route::middleware('auth')->group(function () {
    // Petugas
    Route::get('/petugas/tambah', [PetugasController::class, 'index'])->name('petugas.form');
    Route::post('/petugas', [PetugasController::class, 'tambah'])->name('petugas.tambah');


    // Kategori
    Route::get('/kategori/tambah', [KategoriController::class, 'index'])->name('kategori.form');
    Route::post('/kategori', [KategoriController::class, 'tambah'])->name('kategori.tambah');    
    
    // Buku
    Route::get('/buku/tambah', [BukuController::class, 'create'])->name('buku.form');
    Route::post('/buku', [BukuController::class, 'tambah'])->name('buku.tambah');
    Route::get('/buku/{buku}/edit', [BukuController::class, 'edit'])->whereNumber('buku')->name('buku.edit');
    Route::put('/buku/{buku}', [BukuController::class, 'update'])->whereNumber('buku')->name('buku.update');
    Route::post('/buku/{buku}/ulasan', [BukuController::class, 'tambahUlasan'])->whereNumber('buku')->name('buku.ulasan.tambah');
    Route::put('/buku/{buku}/ulasan/{ulasan}', [BukuController::class, 'updateUlasan'])->whereNumber('buku')->whereNumber('ulasan')->name('buku.ulasan.update');
    Route::delete('/buku/{buku}/ulasan/{ulasan}', [BukuController::class, 'hapusUlasan'])->whereNumber('buku')->whereNumber('ulasan')->name('buku.ulasan.hapus');
    Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])->whereNumber('buku')->name('buku.destroy');

    // Koleksi
    Route::get('/koleksi', [KoleksiController::class, 'index'])->name('koleksi.index');
    Route::post('/koleksi/{buku}', [KoleksiController::class, 'tambah'])->whereNumber('buku')->name('koleksi.tambah');
    Route::delete('/koleksi/{koleksi}', [KoleksiController::class, 'hapus'])->whereNumber('koleksi')->name('koleksi.hapus');

    // Pinjaman Buku
    Route::get('/pinjam', [PeminjamanController::class, 'index'])->name('pinjam.index');
    Route::get('/pinjam/daftar', [PeminjamanController::class, 'daftarPeminjam'])->name('pinjam.daftar');
    Route::get('/pinjam/export-pdf', [PeminjamanController::class, 'exportPdf'])->name('pinjam.export-pdf');
    Route::post('/pinjam/{buku}', [PeminjamanController::class, 'dipinjam'])->whereNumber('buku')->name('pinjam.tambah');
    Route::put('/pinjam/{buku}', [PeminjamanController::class, 'kembali'])->whereNumber('buku')->name('pinjam.kembali');
});

Route::get('/buku/{buku}', [BukuController::class, 'detail'])->whereNumber('buku')->name('buku.detail');