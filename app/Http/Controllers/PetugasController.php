<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PetugasController extends Controller
{
    /**
     * Menampilkan halaman form tambah petugas.
     *
     * Hanya dapat diakses oleh user dengan role admin.
     *
     * @return View
     */
    // Menampilkan halaman tambah petugas
    public function index(): View
    {
        // Jika bukan admin, maka akses ditolak
        abort_unless(auth()->user()->role === 'admin', 403, 'Hanya admin yang dapat menambahkan petugas.');

        return view('petugas.tambah');
    }


    /**
     * Menyimpan data petugas baru ke database.
     *
     * Melakukan validasi input sebelum disimpan.
     * Hanya admin yang dapat mengakses fungsi ini.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    // Fungsi untuk menambahkan petugas baru
    public function tambah(Request $request): RedirectResponse
    {
        // Jika bukan admin, maka akses ditolak
        abort_unless($request->user()->role === 'admin', 403, 'Hanya admin yang dapat menambahkan petugas.');

        // Melakukan validasi input petugas
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique(User::class)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'alamat' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:petugas,admin'],
        ]);

        // Membuat petugas baru
        User::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'alamat' => $validated['alamat'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('index')->with('success', 'User berhasil ditambahkan.');
    }
}
