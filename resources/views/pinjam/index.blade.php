@extends('layouts.app')

@section('title', 'Pinjam Saya')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 gap-2 flex-wrap">
        <h4>Buku Pinjam Saya</h4>
        {{-- Tombol kembali --}}
        <a href="{{ route('index') }}" class="btn btn-outline-secondary">Kembali ke Daftar Buku</a>
    </div>

    {{-- Menampilkan pesan berhasil --}}
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Daftar Buku yang Dipinjam --}}
    <div class="row g-4">
        @forelse ($pinjams as $pinjam)
        @if ($pinjam->status_peminjaman == 'Pinjam')
        <div class="col-sm-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column">
                    <h5>{{ $pinjam->buku->judul ?? 'Buku tidak ditemukan' }}</h5>
                    <p class="text-secondary mb-1">Penulis: {{ $pinjam->buku->penulis ?? '-' }}</p>
                    <p class="text-secondary mb-3">Penerbit: {{ $pinjam->buku->penerbit ?? '-' }}</p>

                    {{-- Form untuk mengembalikan buku --}}
                    @if ($pinjam->buku)
                    <form action="{{ route('pinjam.kembali', $pinjam->buku) }}" method="POST" class="mt-2"
                        onsubmit="return confirm('Kembalikan buku ini dari pinjam Anda?');">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">Kembalikan Buku</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endif
        <div class="col-12">
            <div class="alert alert-secondary mb-0">
                Belum ada buku yang anda pinjam
            </div>
        </div>
        @empty
        @endforelse
    </div>
</div>
@endsection
