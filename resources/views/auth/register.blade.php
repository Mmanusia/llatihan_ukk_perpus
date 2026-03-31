@extends('layouts.app')

@section('title', 'Register')

@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="card">
            <div class="card-body p-4">
                <h3>Register</h3>
                {{-- Form untuk registrasi pengguna --}}
                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    {{-- Input nama lengkap --}}
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama</label>
                        <input class="form-control" type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Masukan Nama">
                        @error('nama_lengkap')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Input username --}}
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input class="form-control" type="text" name="username" value="{{ old('username') }}" placeholder="Masukan Username">
                        @error('username')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Input email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="Masukan Email">
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Input alamat --}}
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" name="alamat" placeholder="Masukan Alamat">{{ old('alamat') }}</textarea>
                        @error('alamat')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Input password --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input class="form-control" type="password" name="password" value="{{ old('password') }}" placeholder="Masukan Password">
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Input konfirmasi password --}}
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input class="form-control" type="password" name="password_confirmation" placeholder="Masukan Kembali Password">
                    </div>

                    <div class="d-grid gap-2">
                        <p>Sudah Punya Akun? Klik <a href="/login">Disini</a></p>
                        <button class="btn btn-primary" type="submit">Register</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection