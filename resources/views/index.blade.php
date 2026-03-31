@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
<div class="container py-5">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <h3>Daftar Buku</h3>
        </div>

        <div class="d-flex gap-2">
            @auth
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-outline-danger">Logout</button>
            </form>
            @else
            <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
            @endauth
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tombol Kelola Buku --}}
    @auth
    <div class="row g-3 mb-4">
        {{-- Tombol Kelola Buku --}}
        @if(in_array(auth()->user()->role, ['admin', 'petugas'])) {{-- Hanya admin dan petugas yang bisa melihat tombol ini --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5>Kelola Buku</h5>
                    <a class="btn btn-primary" href="{{ route('buku.form') }}">Tambah Buku</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5>Kelola Kategori Buku</h5>
                    <a class="btn btn-dark" href="{{ route('kategori.form') }}">Tambah Kategori</a>
                </div>
            </div>
        </div>
        @endif
        @if(in_array(auth()->user()->role, ['admin']))  {{-- Hanya admin yang bisa melihat tombol ini --}}
        {{-- Tombol Kelola Petugas --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5>Kelola Petugas</h5>
                    <a class="btn btn-warning" href="{{ route('petugas.form') }}">Tambah Petugas</a>
                </div>
            </div>
        </div>
        {{-- Tombol Cek Peminjaman --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5>Cek Para Peminjam</h5>
                    <a class="btn btn-success" href="{{ route('pinjam.daftar') }}">Cek Peminjaman</a>
                </div>
            </div>
        </div>
        @endif
        @if(in_array(auth()->user()->role, ['peminjam']))   {{-- Hanya peminjam yang dapat melihat ini --}}
        {{-- Tombol Koleksi Buku --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5>Daftar Koleksi Buku</h5>
                    <a href="{{ route('koleksi.index') }}" class="btn btn-success">Koleksi Saya</a>
                </div>
            </div>
        </div>
        {{-- Tombol Lihat Peminjaman --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5>Buku Yang Di Pinjam</h5>
                    <a href="{{ route('pinjam.index') }}" class="btn btn-info">Lihat Pinjaman</a>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endauth

<div class="row g-4">
    {{-- Daftar Buku --}}
    @forelse ($bukus as $buku)
    <div class="col-sm-6 col-lg-4">
        <div class="card shadow-sm border-0 h-100 overflow-hidden">
            
            <div class="card-body d-flex flex-column">
                
                {{-- Detail Buku --}}
            <h5>{{ $buku->judul }}</h5>
            <div class="text-warning small mb-1">
                @php $avg = round($buku->ulasans_avg_rating ?? 0); @endphp
                @for ($i = 1; $i <= 5; $i++) {{ $i <= $avg ? '★' : '☆' }} @endfor <span class="text-secondary ms-1">
                    @if ($buku->ulasans_avg_rating)
                    {{ number_format($buku->ulasans_avg_rating, 1) }}/5
                    @else
                    Belum ada rating
                    @endif
                    </span>
            </div>

            <div class="d-flex gap-2 mt-auto">
                <a href="{{ route('buku.detail', $buku) }}" class="btn btn-outline-dark btn-sm">Lihat Detail</a>

                @auth
                {{-- Form untuk menambahkan buku ke koleksi --}}
                @if(in_array(auth()->user()->role, ['peminjam']))
                @if(in_array($buku->id, $koleksiBukuIds ?? []))
                {{-- Buku sudah ditambahkan ke koleksi --}}
                <button type="button" class="btn btn-success btn-sm" disabled>Sudah di Koleksi</button>
                @else
                <form action="{{ route('koleksi.tambah', $buku) }}" method="POST">
                    @csrf
                    {{-- Form untuk menambahkan buku ke koleksi --}}
                    <button type="submit" class="btn btn-outline-success btn-sm">Tambah ke Koleksi</button>
                </form>
                @endif
                @endif

                {{-- Form untuk menghapus buku --}}
                @if(in_array(auth()->user()->role, ['admin', 'petugas']))
                <form action="{{ route('buku.destroy', $buku) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus buku ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                </form>
                @endif
                @endauth
            </div>
        </div>
    </div>
</div>
@empty
@endforelse
</div>
</div>
@endsection