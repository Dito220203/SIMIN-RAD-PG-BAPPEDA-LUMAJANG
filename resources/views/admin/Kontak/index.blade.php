@extends('components.layout')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Kontak</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Beranda</li>
                    <li class="breadcrumb-item active">Kontak</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section contact">
            <div class="row gy-4">

                <!-- Bagian Info Kontak -->
                <div class="col-xl-6">
                    <div class="info-box card mb-3 shadow-sm">
                        <i class="bi bi-geo-alt"></i>
                        <h3>Alamat</h3>
                        <p>{{ $kontak->alamat ?? '-' }}</p>
                    </div>

                    <div class="info-box card mb-3 shadow-sm">
                        <i class="bi bi-telephone"></i>
                        <h3>Telepon</h3>
                        <p>{{ $kontak->telepon ?? '-' }}</p>
                    </div>

                    <div class="info-box card mb-3 shadow-sm">
                        <i class="bi bi-envelope"></i>
                        <h3>Email</h3>
                        <p>{{ $kontak->email ?? '-' }}</p>
                    </div>

                    <div class="info-box card mb-3 shadow-sm">
                        <i class="bi bi-facebook"></i>
                        <h3>Facebook</h3>
                        @if (!empty($kontak->linkfb))
                            <p><a href="{{ $kontak->linkfb }}" target="_blank">{{ $kontak->namafb }}</a></p>
                        @else
                            <p>-</p>
                        @endif
                    </div>

                    <div class="info-box card mb-3 shadow-sm">
                        <i class="bi bi-instagram"></i>
                        <h3>Instagram</h3>
                        @if (!empty($kontak->linkig))
                            <p><a href="{{ $kontak->linkig }}" target="_blank">{{ $kontak->namaig }}</a></p>
                        @else
                            <p>-</p>
                        @endif
                    </div>

                    <div class="info-box card mb-3 shadow-sm">
                        <i class="bi bi-youtube"></i>
                        <h3>YouTube</h3>
                        @if (!empty($kontak->linkyt))
                            <p><a href="{{ $kontak->linkyt }}" target="_blank">{{ $kontak->namayt }}</a></p>
                        @else
                            <p>-</p>
                        @endif
                    </div>
                </div>

                <!-- Bagian Form Kontak -->
                <div class="col-xl-6">
                    <div class="card p-4 shadow-sm">
                        <h5 class="card-title mb-3">Form Kontak</h5>
                        <form action="{{ route('kontak.store') }}" method="POST">
                            @csrf
                            <div class="row gy-3">

                                <div class="col-md-12">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $kontak->alamat ?? '') }}</textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Telepon</label>
                                    <input type="text" name="telepon" value="{{ old('telepon', $kontak->telepon ?? '') }}"
                                        class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" value="{{ old('email', $kontak->email ?? '') }}"
                                        class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Nama Facebook</label>
                                    <input type="text" name="namafb" value="{{ old('namafb', $kontak->namafb ?? '') }}"
                                        class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Link Facebook</label>
                                    <input type="url" name="linkfb" value="{{ old('linkfb', $kontak->linkfb ?? '') }}"
                                        class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Nama Instagram</label>
                                    <input type="text" name="namaig" value="{{ old('namaig', $kontak->namaig ?? '') }}"
                                        class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Link Instagram</label>
                                    <input type="url" name="linkig" value="{{ old('linkig', $kontak->linkig ?? '') }}"
                                        class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Nama YouTube</label>
                                    <input type="text" name="namayt" value="{{ old('namayt', $kontak->namayt ?? '') }}"
                                        class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Link YouTube</label>
                                    <input type="url" name="linkyt" value="{{ old('linkyt', $kontak->linkyt ?? '') }}"
                                        class="form-control">
                                </div>

                                <div class="col-md-12 text-center mt-3">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-save"></i> Simpan Kontak
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </section>
    </main>
@endsection
