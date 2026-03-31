@extends('layouts.app')

@section('title', 'Tambah Buku')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Tambah Buku</h4>
                {{-- Tombol kembali --}}
                <a class="btn btn-outline-secondary" href="{{ route('index') }}">Kembali</a>
            </div>

            {{-- Menampilkan error --}}
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Form untuk menambah buku --}}
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('buku.tambah') }}" method="POST">
                        @csrf

                        {{-- Input judul buku --}}
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul Buku</label>
                            <input class="form-control" id="judul" name="judul" type="text"
                                value="{{ old('judul') }}" placeholder="Masukan Judul Buku">
                        </div>

                        {{-- Input penulis buku --}}
                        <div class="mb-3">
                            <label for="penulis" class="form-label">Penulis Buku</label>
                            <input class="form-control" id="penulis" name="penulis" type="text"
                                value="{{ old('penulis') }}" placeholder="Masukan Nama Penulis Buku">
                        </div>

                        {{-- Input penerbit buku --}}
                        <div class="mb-3">
                            <label for="penerbit" class="form-label">Penerbit Buku</label>
                            <input class="form-control" id="penerbit" name="penerbit" type="text"
                                value="{{ old('penerbit') }}" placeholder="Masukan Nama Penerbit Buku">
                        </div>

                        {{-- Input tahun terbit buku --}}
                        <div class="mb-3">
                            <label for="tahun_terbit" class="form-label">Tahun Terbit Buku</label>
                            <input class="form-control" id="tahun_terbit" name="tahun_terbit" type="text"
                                value="{{ old('tahun_terbit') }}" placeholder="Masukan Tahun Terbit Buku">
                        </div>

                        {{-- Input kategori --}}
                        <div class="mb-3">
                            <label for="kategori_id" class="form-label">Kategori</label>
                            @foreach ($kategoris as $kategori)
                            <br>
                            <input type="checkbox" class="form-check-input" name="kategori_id[]" value="{{ $kategori->id }}" id="kategori_{{ $kategori->id }}">
                            <label class="form-check-label" for="kategori_{{ $kategori->id }}" class="form-label">{{ $kategori->nama_kategori }}</label>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Buku</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection