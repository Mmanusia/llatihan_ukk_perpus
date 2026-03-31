@extends('layouts.app')

@section('title', 'Tambah Buku')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            {{-- Form untuk menambah kategori --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Tambah Kategori</h4>
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

            {{-- Form untuk menambah kategori --}}
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('kategori.tambah') }}" method="POST">
                        @csrf

                        {{-- Input nama kategori --}}
                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">Nama Katergori</label>
                            <input class="form-control" id="nama_kategori" name="nama_kategori" type="text"
                                value="{{ old('nama_kategori') }}" placeholder="Masukan Kategori Buku">
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Kategori</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection