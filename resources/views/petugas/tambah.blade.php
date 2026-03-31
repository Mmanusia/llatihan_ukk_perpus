@extends('layouts.app')

@section('title', 'Tambah Petugas/Admin')

@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Tambah Petugas/Admin</h4>
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

            {{-- Form untuk menambah petugas --}}
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('petugas.tambah') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input class="form-control @error('nama_lengkap') is-invalid @enderror" type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Masukan Nama Lengkap">
                            @error('nama_lengkap')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input class="form-control @error('username') is-invalid @enderror" type="text" name="username" value="{{ old('username') }}" placeholder="Masukan Username">
                            @error('username')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" placeholder="Masukan Email">
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" placeholder="Masukan Alamat">{{ old('alamat') }}</textarea>
                            @error('alamat')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" placeholder="Masukan Password">
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input class="form-control" type="password" name="password_confirmation" placeholder="Masukan Kembali Password">
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select @error('role') is-invalid @enderror" name="role" id="role">
                                <option value="" disabled selected>Pilih Role</option>
                                <option value="petugas" @selected(old('role') === 'petugas')>Petugas</option>
                                <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                            </select>
                            @error('role')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" type="submit">Tambah User</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection