@extends('layouts.app')

@section('title', 'Koleksi Buku Saya')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 gap-2 flex-wrap">
        <h4>Koleksi Buku Saya</h4>
        {{-- Tombol kembali --}}
        <a href="{{ route('index') }}" class="btn btn-outline-secondary">Kembali ke Daftar Buku</a>
    </div>

    {{-- Pesan sukses --}}
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Daftar koleksi --}}
    <div class="row g-4">
        @forelse ($koleksis as $koleksi)
        <div class="col-sm-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column">
                    <h5>{{ $koleksi->buku->judul ?? 'Buku tidak ditemukan' }}</h5>
                    <p class="text-secondary mb-1">Penulis: {{ $koleksi->buku->penulis ?? '-' }}</p>
                    <p class="text-secondary mb-3">Penerbit: {{ $koleksi->buku->penerbit ?? '-' }}</p>

                    {{-- Tombol untuk melihat detail dan menghapus dari koleksi --}}
                    @if ($koleksi->buku)
                    <a href="{{ route('buku.detail', $koleksi->buku) }}" class="btn btn-outline-dark mt-auto">Lihat Detail</a>
                    <form action="{{ route('koleksi.hapus', $koleksi) }}" method="POST" class="mt-2"
                        onsubmit="return confirm('Hapus buku ini dari koleksi Anda?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">Hapus dari Koleksi</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-secondary mb-0">
                Belum ada buku di koleksi Anda.
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
