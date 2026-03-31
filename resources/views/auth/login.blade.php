@extends('layouts.app')

@section('title', 'Login')

@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="card">
            <div class="card-body p-4">
                <h3>Login</h3>
                {{-- Form untuk login pengguna --}}
                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    {{-- Input email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="Masukan Email">
                        @error('email')
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

                    <div class="d-grid gap-2">
                        <p>Belum Punya Akun? Klik <a href="/register">Disini</a></p>
                        <button class="btn btn-primary">Login</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection