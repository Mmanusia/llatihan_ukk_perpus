<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    /**
     * Menampilkan halaman form tambah peminjaman.
     *
     * Hanya dapat diakses oleh user dengan role peminjam.
     *
     * @return View
     */
    // Menampilkan halaman peminjaman
    public function index(Request $request): View
    {
        // Mengambil data peminjaman pengguna yang sedang login
        $pinjams = Peminjaman::with('buku')
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->get();

        return view('pinjam.index', compact('pinjams'));
    }

    /**
     * Meminjam buku.
     *
     * Hanya dapat diakses oleh user dengan role peminjam.
     *
     * @param Request $request
     * @param Buku $buku
     * @return RedirectResponse
     */
    // Meminjam buku
    public function dipinjam(Request $request, Buku $buku): RedirectResponse
    {
        // Mengecek apakah buku sudah ada di pinjam pengguna
        $pinjamanAktif = Peminjaman::where('user_id', $request->user()->id)
            ->where('buku_id', $buku->id)
            ->where('status_peminjaman', 'Pinjam')
            ->exists();

        if ($pinjamanAktif) {
            return redirect()->route('pinjam.index')->with('success', 'Buku sudah ada di pinjam Anda.');
        }

        // Mengecek apakah pengguna pernah meminjam buku ini sebelumnya dan sudah dikembalikan
        $peminjaman = Peminjaman::where('user_id', $request->user()->id)
            ->where('buku_id', $buku->id)
            ->where('status_peminjaman', 'Dikembalikan')
            ->latest('id')
            ->first();

        if ($peminjaman) {
            // Memperbarui status peminjaman
            $peminjaman->update([
                'tanggal_peminjaman' => now()->toDateString(),
                'tanggal_pengembalian' => null,
                'status_peminjaman' => 'Pinjam',
            ]);
        } else {

            $validate['tanggal_peminjaman'] = now()->toDateString();
            // Membuat data peminjaman baru
            Peminjaman::create([
                'user_id' => $request->user()->id,
                'buku_id' => $buku->id,
                'tanggal_peminjaman' => $validate['tanggal_peminjaman'],
                'tanggal_pengembalian' => NULL,
                'status_peminjaman' => 'Pinjam',
                ]);
        }
      
        return redirect()->route('pinjam.index')->with('success', 'Buku Dipinjam');
    }

    /**
     * Mengembalikan buku.
     *
     * Hanya dapat diakses oleh user dengan role peminjam.
     *
     * @param Request $request
     * @param Buku $buku
     * @return RedirectResponse
     */
    // Mengembalikan buku
    public function kembali(Request $request, Buku $buku): RedirectResponse
    {
        // Mengecek apakah ada status peminjaman aktif untuk buku ini oleh pengguna yang sedang login
        $peminjaman = Peminjaman::where('user_id', $request->user()->id)
            ->where('buku_id', $buku->id)
            ->where('status_peminjaman', 'Pinjam')
            ->latest('id')
            ->first();

        if (!$peminjaman) {
            return redirect()->route('pinjam.index')->with('success', 'Data peminjaman aktif tidak ditemukan.');
        }

        // Memperbarui status peminjaman menjadi dikembalikan
        $peminjaman->update([
            'tanggal_pengembalian' => now(),
            'status_peminjaman' => 'Dikembalikan',
        ]);

        return redirect()->route('pinjam.index')->with('success', 'Buku berhasil dikembalikan.');
    }

    /**
     * Menampilkan daftar peminjam.
     *
     * Hanya dapat diakses oleh user dengan role admin atau petugas.
     *
     * @param Request $request
     * @return View
     */
    // Menampilkan daftar peminjam (hanya untuk admin)
    public function daftarPeminjam(Request $request): View
    {
        // Jika bukan admin, maka akses ditolak
        if ($request->user()->role !== 'admin' && $request->user()->role !== 'petugas') {
            abort(403);
        }

        // Membuat query untuk mengambil data peminjaman beserta relasi user dan buku
        $query = Peminjaman::with(['user', 'buku']);

        // Filter berdasarkan tanggal peminjaman
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $tanggal_mulai = Carbon::parse($request->tanggal_mulai)->startOfDay();
            $tanggal_akhir = Carbon::parse($request->tanggal_akhir)->endOfDay();
            
            $query->whereBetween('tanggal_peminjaman', [$tanggal_mulai, $tanggal_akhir]);
        }

        $pinjams = $query->latest('id')->get();

        return view('pinjam.daftar', compact('pinjams'));
    }

    /**
     * Mengekspor data peminjaman ke PDF.
     *
     * Hanya dapat diakses oleh user dengan role admin atau petugas.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    // Mengekspor data peminjaman ke PDF
    public function exportPdf(Request $request)
    {
        if ($request->user()->role !== 'admin' && $request->user()->role !== 'petugas') {
            abort(403);
        }

        $query = Peminjaman::with(['user', 'buku']);

        // Filter berdasarkan tanggal peminjaman
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $tanggal_mulai = Carbon::parse($request->tanggal_mulai)->startOfDay();  // Mengatur waktu mulai ke awal hari
            $tanggal_akhir = Carbon::parse($request->tanggal_akhir)->endOfDay();    // Mengatur waktu akhir ke akhir hari
            
            // Menerapkan filter tanggal pada query
            $query->whereBetween('tanggal_peminjaman', [$tanggal_mulai, $tanggal_akhir]);
        }

        // Mengambil data peminjaman yang sudah difilter
        $pinjams = $query->latest('id')->get();

        // Menyiapkan data untuk PDF
        $data = [
            'pinjams' => $pinjams,
            'tanggal_mulai' => $request->tanggal_mulai ?? 'Semua',
            'tanggal_akhir' => $request->tanggal_akhir ?? 'Semua',
            'tanggal_cetak' => now()->format('d M Y H:i'),
        ];

        $pdf = Pdf::loadView('pinjam.export-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('Laporan-Peminjaman-' . now()->format('dmY-His') . '.pdf');
    }
}
