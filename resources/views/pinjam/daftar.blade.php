@extends('layouts.app')

@section('title', 'Daftar Peminjam')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 gap-2 flex-wrap">
        <h4>Daftar Peminjam</h4>
        {{-- Tombol kembali --}}
        <a href="{{ route('index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    {{-- Menampilkan pesan sukses --}}
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Form Filter Tanggal -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                {{-- Filter Tanggal --}}
                <div class="col-md-4">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" 
                        value="{{ request('tanggal_mulai') }}">
                </div>
                {{-- Filter Tanggal Akhir --}}
                <div class="col-md-4">
                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" 
                        value="{{ request('tanggal_akhir') }}">
                </div>
                {{-- Tombol Filter dan Reset --}}
                <div class="col-md-4 d-flex gap-2">
                    <button type="button" class="btn btn-primary" onclick="filterData()">Filter</button>
                    <a href="{{ route('pinjam.daftar') }}" class="btn btn-outline-secondary">Reset</a>
                    <button type="button" class="btn btn-danger" onclick="exportPdf()">
                        <i class="bi bi-file-pdf"></i> Export PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk filter dan export PDF --}}
    <script>
        function filterData() {
            const tanggalMulai = document.getElementById('tanggal_mulai').value;
            const tanggalAkhir = document.getElementById('tanggal_akhir').value;
            
            if (!tanggalMulai || !tanggalAkhir) {
                alert('Silakan pilih tanggal mulai dan tanggal akhir');
                return;
            }
            
            window.location.href = `{{ route('pinjam.daftar') }}?tanggal_mulai=${tanggalMulai}&tanggal_akhir=${tanggalAkhir}`;
        }

        function exportPdf() {
            const tanggalMulai = document.getElementById('tanggal_mulai').value;
            const tanggalAkhir = document.getElementById('tanggal_akhir').value;
            
            if (!tanggalMulai || !tanggalAkhir) {
                alert('Silakan pilih tanggal mulai dan tanggal akhir');
                return;
            }
            
            window.location.href = `{{ route('pinjam.export-pdf') }}?tanggal_mulai=${tanggalMulai}&tanggal_akhir=${tanggalAkhir}`;
        }
    </script>

{{-- Tabel Daftar Peminjam --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3">No</th>
                            <th>Nama Peminjam</th>
                            <th>Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Daftar Peminjam --}}
                        @forelse ($pinjams as $pinjam)
                        <tr>
                            <td class="px-3">{{ $loop->iteration }}</td>
                            <td>{{ $pinjam->user->nama_lengkap ?? $pinjam->user->username ?? '-' }}</td>
                            <td>{{ $pinjam->buku->judul ?? '-' }}</td>
                            <td>{{ $pinjam->tanggal_peminjaman ? \Illuminate\Support\Carbon::parse($pinjam->tanggal_peminjaman)->format('d M Y') : '-' }}</td>
                            <td>{{ $pinjam->tanggal_pengembalian ? \Illuminate\Support\Carbon::parse($pinjam->tanggal_pengembalian)->format('d M Y') : '-' }}</td>
                            <td>
                                @if ($pinjam->status_peminjaman === 'Pinjam')
                                <span class="badge text-bg-warning">Belum Dikembalikan</span>
                                @else
                                <span class="badge text-bg-success">Sudah Dikembalikan</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-secondary">Belum ada data peminjaman.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
