<?php

namespace App\Http\Controllers;

use App\Models\KategoriBuku;
use App\Models\KategoriBukuRelasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KategoriController extends Controller
{
    /**
     * Menampilkan halaman tambah kategori.
     *
     * Hanya dapat diakses oleh user dengan role admin atau petugas.
     *
     * @return View
     */
    // Menampilkan halaman tambah kategori
    public function index(): View
    {
        return view('kategori.tambah');
    }

    /**
     * Menambahkan kategori baru.
     *
     * Hanya dapat diakses oleh user dengan role admin atau petugas.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    // Fungsi untuk menambahkan kategori baru
    public function tambah(Request $request): RedirectResponse
    {
        // Melakukan validasi input kategori
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:255'],
        ]);

        // Membuat kategori baru
        KategoriBuku::create([
            'nama_kategori' => $validated['nama_kategori'],
        ]);

        return redirect()->route('index')->with('success', 'Kategori berhasil ditambahkan.');
    }
}