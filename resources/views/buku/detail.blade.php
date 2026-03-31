@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')
<div class="container py-5">
	<div class="row justify-content-center">
		<div class="col-lg-8">
			<div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
				<h4>Detail Buku</h4>
				<div class="d-flex gap-2">
					<a class="btn btn-outline-secondary" href="{{ route('index') }}">Kembali</a>
					@auth
						@if (in_array(auth()->user()->role, ['admin', 'petugas']))
							<a class="btn btn-primary" href="{{ route('buku.edit', $buku) }}">Edit Buku</a>
						@endif
					@endauth
				</div>
			</div>

			@if (session('success'))
				<div class="alert alert-success">{{ session('success') }}</div>
			@endif

			<div class="card shadow-sm border-0">
				<div class="card-body p-4">
					<dl class="row mb-0">
						<dt class="col-sm-3">Judul</dt>
						<dd class="col-sm-9">{{ $buku->judul }}</dd>		{{-- Menampilkan judul buku --}}

						<dt class="col-sm-3">Penulis</dt>
						<dd class="col-sm-9">{{ $buku->penulis }}</dd>		{{-- Menampilkan penulis buku --}}

						<dt class="col-sm-3">Penerbit</dt>
						<dd class="col-sm-9">{{ $buku->penerbit }}</dd>		{{-- Menampilkan penerbit buku --}}

						<dt class="col-sm-3">Tahun Terbit</dt>
						<dd class="col-sm-9">{{ $buku->tahun_terbit }}</dd>	{{-- Menampilkan tahun terbit buku --}}

						<dt class="col-sm-3">Kategori</dt>
						<dd class="col-sm-9">
							@if ($kategoris->isNotEmpty())	{{-- Menampilkan kategori buku jika ada --}}
								<div class="d-flex flex-wrap gap-2">
									@foreach ($kategoris as $kategori)
										<span class="badge text-bg-secondary">{{ $kategori->nama_kategori }}</span>	{{-- Menampilkan nama kategori dengan badge --}}
									@endforeach
								</div>
							@else
								<span class="text-secondary">Belum ada kategori</span>
							@endif
						</dd>
					</dl>
				</div>
			</div>
		</div>
	</div>

	{{-- Pinjam Buku --}}
	@if(in_array(auth()->user()->role, ['peminjam']))
	<div class="row justify-content-center mt-4">
		<div class="d-flex justify-content-between align-items-center">
			<form action="{{ route('pinjam.tambah', $buku) }}" method="POST"> {{-- Form untuk meminjam buku --}}
				@csrf
				<button class="btn btn-outline-success ps-auto">Pinjam Buku</button>
			</form>
			<form action="{{ route('koleksi.tambah', $buku) }}" method="POST"> {{-- Form untuk menambahkan buku ke koleksi pribadi --}}
				@csrf
				<button type="submit" class="btn btn-outline-success">Tambah ke Koleksi</button>
            </form>
		</div>
	</div>
	@endif

	<div class="row justify-content-center mt-4">
		<div class="col-lg-8">
			<h5 class="mb-3">Ulasan Pembaca</h5>

			@forelse ($ulasans as $ulasan)
			<div class="card shadow-sm border-0 mb-3">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center mb-1">
						<span class="fw-semibold">{{ $ulasan->user->nama_lengkap ?? $ulasan->user->username ?? 'Pengguna' }}</span>
						<span class="text-warning">
							@for ($i = 1; $i <= 5; $i++)
								{{ $i <= $ulasan->rating ? '★' : '☆' }}	{{-- Menampilkan bintang rating berdasarkan nilai rating ulasan --}}
							@endfor
							<span class="text-secondary small ms-1">({{ $ulasan->rating }}/5)</span>
						</span>
					</div>
					<p class="mb-0">{{ $ulasan->ulasan }}</p>
				</div>
			</div>
			@empty
			<p class="text-secondary">Belum ada ulasan untuk buku ini.</p>
			@endforelse

			@auth
			{{-- Form untuk menambahkan ulasan --}}
				@if (!$sudahUlasan)
                @if (in_array(auth()->user()->role, ['peminjam']))
				<div class="card shadow-sm border-0 mt-3">
					<div class="card-body p-4">
						<h6 class="mb-3">Tambah Ulasan</h6>

						{{-- Menampilkan error validasi --}}
						@if ($errors->any())
							<div class="alert alert-danger">
								<ul class="mb-0 ps-3">
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif

						{{-- Form untuk menambahkan ulasan --}}
						<form action="{{ route('buku.ulasan.tambah', $buku) }}" method="POST">
							@csrf

							{{-- Input ulasan --}}
							<div class="mb-3">
								<label for="ulasan" class="form-label">Ulasan</label>
								<textarea class="form-control @error('ulasan') is-invalid @enderror"
									id="ulasan" name="ulasan" rows="4"
									placeholder="Tulis ulasan Anda...">{{ old('ulasan') }}</textarea>
								@error('ulasan')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>

							{{-- Input rating --}}
							<div class="mb-3">
								<label for="rating" class="form-label">Rating</label>
								<select class="form-select @error('rating') is-invalid @enderror" id="rating" name="rating">
									<option value="" disabled {{ old('rating') ? '' : 'selected' }}>Pilih rating</option>
									@for ($i = 1; $i <= 5; $i++)
										<option value="{{ $i }}" @selected(old('rating') == $i)>
											{{ str_repeat('★', $i) }}{{ str_repeat('☆', 5 - $i) }} ({{ $i }})
										</option>
									@endfor
								</select>
								@error('rating')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>

							<button type="submit" class="btn btn-primary">Kirim Ulasan</button>
						</form>
                        @endif
					</div>
				</div>
				@else
				{{-- Menampilkan ulasan pengguna saat ini --}}
				@php $ulasanSaya = $ulasans->firstWhere('user_id', auth()->id()); @endphp
				@if ($ulasanSaya)
				<div class="card border-0 shadow-sm mt-3">
					<div class="card-body p-4">
						<div class="d-flex justify-content-between align-items-center">
							<span>Anda sudah memberikan ulasan untuk buku ini.</span>
							<button class="btn btn-outline-primary btn-sm" type="button"
								onclick="var f=document.getElementById('editUlasanSaya');f.style.display=f.style.display==='block'?'none':'block'"
							>Edit Ulasan</button>
						</div>

							<div id="editUlasanSaya" style="display:none" class="mt-3">
							<hr class="mt-0">
							{{-- Form untuk mengedit ulasan --}}
							<form action="{{ route('buku.ulasan.update', [$buku, $ulasanSaya]) }}" method="POST">
								@csrf
								@method('PUT')

								{{-- Input ulasan --}}
								<div class="mb-3">
									<label class="form-label">Ulasan</label>
									<textarea class="form-control" name="ulasan" rows="3">{{ $ulasanSaya->ulasan }}</textarea>
								</div>

								{{-- Input rating --}}
								<div class="mb-3">
									<label class="form-label">Rating</label>
									<select class="form-select" name="rating">
										@for ($i = 1; $i <= 5; $i++)
											<option value="{{ $i }}" @selected($ulasanSaya->rating == $i)>
												{{ str_repeat('★', $i) }}{{ str_repeat('☆', 5 - $i) }} ({{ $i }})
											</option>
										@endfor
									</select>
								</div>

								<button type="submit" class="btn btn-primary btn-sm">Simpan</button>
								<button type="button" class="btn btn-outline-secondary btn-sm"
									onclick="document.getElementById('editUlasanSaya').style.display='none'"
								>Batal</button>
							</form>
						</div>
					</div>
				</div>
				@endif
				@endif
			@else
				{{-- Menampilkan pesan jika pengguna belum login --}}
				<div class="alert alert-secondary mt-3">Silakan <a href="{{ route('login') }}">login</a> untuk memberikan ulasan.</div>
			@endauth
		</div>
	</div>
</div>
@endsection
