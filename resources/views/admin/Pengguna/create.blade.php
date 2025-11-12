@extends('components.layout')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Tambah Pengguna</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Beranda</a></li>
                    <li class="breadcrumb-item">Pengguna</li>
                    <li class="breadcrumb-item active">Tambah Pengguna</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12"> {{-- Ubah jadi full width jika butuh --}}
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3 mt-3">

                                <form action="{{ route('pengguna.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Username</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required>
                                            @error('username')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Nama</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Password</label>
                                        <div class="col-sm-10">
                                            <input type="password" name="password" class="form-control" required>
                                        </div>
                                    </div>


                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Perangkat Daerah</label>
                                        <div class="col-sm-10">
                                            <select name="id_opd" class="form-select @error('id_opd') is-invalid @enderror" required>
                                                <option value="">Pilih</option>
                                                @foreach ($opd as $data)
                                                    {{-- Tambahkan kondisi disabled jika id opd sudah ada di array $assigned_opd_ids --}}
                                                    <option value="{{ $data->id }}" @if(in_array($data->id, $assigned_opd_ids)) disabled @endif>
                                                        {{ $data->nama }} @if(in_array($data->id, $assigned_opd_ids)) (Sudah Dipilih) @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                             @error('id_opd')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Level</label>
                                        <div class="col-sm-10">
                                            <select name="level" class="form-select" required>
                                                <option value="">Pilih</option>
                                                <option value="Super Admin">Super Admin</option>
                                                <option value="Admin">Admin</option>
                                            </select>
                                        </div>
                                    </div>


                                    {{-- Tombol --}}
                                    <div class="row mb-3">
                                        <div class="col-sm-10 offset-sm-2 d-flex gap-2">
                                            <button type="submit" class="btn btn-success">Simpan</button>
                                            <a href="{{ route('pengguna') }}" class="btn btn-warning">Kembali</a>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
