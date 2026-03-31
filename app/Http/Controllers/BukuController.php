<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\KategoriBukuRelasi;
use App\Models\KoleksiPribadi;
use App\Models\UlasanBuku;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BukuController extends Controller
{
    /**
     * Menampilkan daftar buku.
     *
     * @return View
     */
    // Menampilkan daftar buku
    public function index(): View
    {
        $bukus = Buku::withAvg('ulasans', 'rating')->latest('id')->get();               // Mengambil semua buku dan mengecek rata-rata rating ulasan
        $kategoris = KategoriBuku::query()->latest('id')->get();                        // Mengambil semua kategori buku
        $koleksiBukuIds = auth()->check()                                               // Mengecek apakah user sudah login
            ? KoleksiPribadi::where('user_id', auth()->id())->pluck('buku_id')->all()   
            : [];

        // Mengirim data buku, kategori, dan koleksi buku yang dimiliki user ke view index
        return view('index', compact('bukus', 'kategoris', 'koleksiBukuIds'));
    }

    /**
     * Menambah buku.
     *
     * Hanya dapat diakses oleh user dengan role admin atau petugas.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    // Menambah buku
    public function tambah(Request $request): RedirectResponse
    {
        abort_unless(in_array($request->user()->role, ['admin', 'petugas']), 403, 'Hanya admin atau petugas yang dapat menambah buku.');

        // Mengambil data input dan melakukan validasi
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'penulis' => ['required', 'string', 'max:255'],
            'penerbit' => ['required', 'string', 'max:255'],
            'tahun_terbit' => ['required', 'integer'],
            'kategori_id' => ['nullable', 'array'],
            'kategori_id.*' => ['integer', 'exists:kategoribuku,id'],
        ]);

        // Membuat buku
        $bukus = Buku::create([
            'judul' => $validated['judul'],
            'penulis' => $validated['penulis'],
            'penerbit' => $validated['penerbit'],
            'tahun_terbit' => $validated['tahun_terbit'],
        ]);

        // Mengecek Kategori dan membuat relasi
        if (!empty($validated['kategori_id'])) {
            foreach ($validated['kategori_id'] as $kategoriId) {
                KategoriBukuRelasi::create([
                    'buku_id' => $bukus->id,
                    'kategori_id' => $kategoriId,
                ]);
            }
        }

        // Mengirim pesan sukses dan mengarahkan kembali ke halaman index
        return redirect()->route('index')->with('success', 'Buku berhasil ditambahkan.');
    }

    /**
     * Menghapus buku.
     *
     * Hanya dapat diakses oleh user dengan role admin atau petugas.
     *
     * @param Request $request
     * @param Buku $buku
     * @return RedirectResponse
     */
    // Menghapus Buku
    public function destroy(Request $request, Buku $buku): RedirectResponse
    {
        abort_unless(in_array($request->user()->role, ['admin', 'petugas']), 403, 'Hanya admin atau petugas yang dapat menghapus buku.');
        $buku->delete();
        return redirect()->route('index')->with('success', 'Buku berhasil dihapus.');
    }

    /**
     * Menampilkan form tambah buku.
     *
     * Hanya dapat diakses oleh user dengan role admin atau petugas.
     *
     * @return View
     */
    // Menampilkan form tambah buku
    public function create(): View
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'petugas']), 403, 'Hanya admin atau petugas yang dapat menambah buku.');
        $kategoris = KategoriBuku::query()->latest('id')->get();
        return view('buku.tambah', compact('kategoris'));
    }

    /**
     * Menampilkan detail buku.
     *
     * @param Buku $buku
     * @return View
     */

    // Menampilkan detail buku
    public function detail(Buku $buku): View
    {
        // Mengabil tipe kategori buku
        $kategoris = KategoriBuku::query()
            ->join('kategoribuku_relasi', 'kategoribuku.id', '=', 'kategoribuku_relasi.kategori_id')
            ->where('kategoribuku_relasi.buku_id', $buku->id)
            ->select('kategoribuku.*')
            ->latest('kategoribuku.id')
            ->get();

        // Menampilkan ulasan buku
        $ulasans = UlasanBuku::with('user')
            ->where('buku_id', $buku->id)
            ->latest('id')
            ->get();

        // Mengecek apakah user sudah mengulas
        $sudahUlasan = auth()->check()
            ? UlasanBuku::where('buku_id', $buku->id)->where('user_id', auth()->id())->exists()
            : false;

        return view('buku.detail', compact('buku', 'kategoris', 'ulasans', 'sudahUlasan'));
    }

    /**
     * Menambah ulasan buku.
     *
     * @param Request $request
     * @param Buku $buku
     * @return RedirectResponse
     */
    // Menambah ulasan buku
    public function tambahUlasan(Request $request, Buku $buku): RedirectResponse
    {
        // Mengecek apakah sudah Memberi Ulasan
        $sudahUlasan = UlasanBuku::where('buku_id', $buku->id)
            ->where('user_id', $request->user()->id)
            ->exists();

        abort_if($sudahUlasan, 403, 'Anda sudah memberikan ulasan untuk buku ini.');

        // Mengambil input dan menvalidasi ulasan 
        $validated = $request->validate([
            'ulasan' => ['required', 'string'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        // Membuat ulasan buku
        UlasanBuku::create([
            'buku_id' => $buku->id,
            'user_id' => $request->user()->id,
            'ulasan'  => $validated['ulasan'],
            'rating'  => $validated['rating'],
        ]);

        return redirect()->route('buku.detail', $buku)->with('success', 'Ulasan berhasil ditambahkan.');
    }

    /**
     * Mengupdate ulasan buku.
     *
     * Hanya dapat diakses oleh user yang membuat ulasan tersebut.
     *
     * @param Request $request
     * @param Buku $buku
     * @param UlasanBuku $ulasan
     * @return RedirectResponse
     */
    // Mengupdate ulasan buku
    public function updateUlasan(Request $request, Buku $buku, UlasanBuku $ulasan): RedirectResponse
    {
        // Mengecek apakah user yang mengupdate adalah pemilik ulasan
        abort_unless((int) $ulasan->user_id === (int) $request->user()->id, 403, 'Anda tidak berhak mengubah ulasan ini.');

        // Mengambil input dan menvalidasi ulasan
        $validated = $request->validate([
            'ulasan' => ['required', 'string'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        // Menggupdate ulasan
        $ulasan->update($validated);

        // Mengembalikan pesan jika berhasil dan kembali ke detail buku
        return redirect()->route('buku.detail', $buku)->with('success', 'Ulasan berhasil diperbarui.');
    }

    /**
     * Menampilkan form edit buku.
     *
     * Hanya dapat diakses oleh user dengan role admin atau petugas.
     *
     * @param Buku $buku
     * @return View
     */
    // Menampilkan form edit buku
    public function edit(Buku $buku): View
    {
        // Mengecek apakah role admin dan petugas
        abort_unless(in_array(auth()->user()->role, ['admin', 'petugas']), 403, 'Hanya admin atau petugas yang dapat mengubah buku.');

        $kategoris = KategoriBuku::query()->latest('id')->get();

        // Mengambil id Kategori
        $selectedKategoriIds = KategoriBukuRelasi::where('buku_id', $buku->id)
            ->pluck('kategori_id')
            ->toArray();

        return view('buku.edit', compact('buku', 'kategoris', 'selectedKategoriIds'));
    }

    /**
     * Mengupdate buku.
     *
     * Hanya dapat diakses oleh user dengan role admin atau petugas.
     *
     * @param Request $request
     * @param Buku $buku
     * @return RedirectResponse
     */
    // Mengupdate buku
    public function update(Request $request, Buku $buku): RedirectResponse
    {
        // Mengecek apakah role admin dan petugas
        abort_unless(in_array($request->user()->role, ['admin', 'petugas']), 403, 'Hanya admin atau petugas yang dapat mengubah buku.');

        // Mengambil data input dan melakukan validasi
        $validated = $request->validate([
            'judul'       => ['required', 'string', 'max:255'],
            'penulis'     => ['required', 'string', 'max:255'],
            'penerbit'    => ['required', 'string', 'max:255'],
            'tahun_terbit'=> ['required', 'integer'],
            'kategori_id' => ['nullable', 'array'],
            'kategori_id.*' => ['integer', 'exists:kategoribuku,id'],
        ]);

        // Mengupdate buku
        $buku->update([
            'judul'        => $validated['judul'],
            'penulis'      => $validated['penulis'],
            'penerbit'     => $validated['penerbit'],
            'tahun_terbit' => $validated['tahun_terbit'],
        ]);

        // Mengecek Kategori dan membuat relasi
        KategoriBukuRelasi::where('buku_id', $buku->id)->delete();
        foreach ($validated['kategori_id'] ?? [] as $kategoriId) {
            KategoriBukuRelasi::create([
                'buku_id'    => $buku->id,
                'kategori_id'=> $kategoriId,
            ]);
        }

        return redirect()->route('buku.detail', $buku)->with('success', 'Buku berhasil diperbarui.');
    }
}