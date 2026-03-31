<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\KoleksiPribadi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KoleksiController extends Controller
{
    /**
     * Menampilkan halaman koleksi pribadi.
     *
     * Hanya dapat diakses oleh user dengan role peminjam.
     *
     * @param Request $request
     * @return View
     */
    // Menampilkan halaman koleksi pribadi
    public function index(Request $request): View
    {
        // Mengambil koleksi pribadi pengguna yang sedang login
        $koleksis = KoleksiPribadi::with('buku')
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->get();

        return view('koleksi.index', compact('koleksis'));
    }

    /**
     * Menambah buku ke koleksi pribadi.
     *
     * Hanya dapat diakses oleh user dengan role peminjam.
     *
     * @param Request $request
     * @param Buku $buku
     * @return RedirectResponse
     */
    // Menambah buku ke koleksi pribadi
    public function tambah(Request $request, Buku $buku): RedirectResponse
    {
        // Mengecek apakah buku sudah ada di koleksi pribadi pengguna
        $sudahAda = KoleksiPribadi::where('user_id', $request->user()->id)
            ->where('buku_id', $buku->id)
            ->exists();

        if ($sudahAda) {
            return redirect()->route('koleksi.index')->with('success', 'Buku sudah ada di koleksi Anda.');
        }

        // Menambah buku ke koleksi pribadi pengguna
        KoleksiPribadi::create([
            'user_id' => $request->user()->id,
            'buku_id' => $buku->id,
        ]);

        return redirect()->route('koleksi.index')->with('success', 'Buku berhasil ditambahkan ke koleksi.');
    }

    /**
     * Menghapus buku dari koleksi pribadi.
     *
     * Hanya dapat diakses oleh user dengan role peminjam.
     *
     * @param Request $request
     * @param KoleksiPribadi $koleksi
     * @return RedirectResponse
     */
    // Menghapus buku dari koleksi pribadi
    public function hapus(Request $request, KoleksiPribadi $koleksi): RedirectResponse
    {
        // Mengecek apakah buku yang akan dihapus adalah milik pengguna yang sedang login
        abort_unless((int) $koleksi->user_id === (int) $request->user()->id, 403, 'Ngapain jir.');
        $koleksi->delete(); // Menghapus buku dari koleksi pribadi pengguna
        return redirect()->route('koleksi.index')->with('success', 'Buku berhasil dihapus dari koleksi.');
    }
}
