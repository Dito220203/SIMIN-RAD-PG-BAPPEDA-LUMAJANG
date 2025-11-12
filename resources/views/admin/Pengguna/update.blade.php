@extends('components.layout')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Edit Pengguna</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Beranda</li>
                    <li class="breadcrumb-item">Pengguna</li>
                    <li class="breadcrumb-item active">Edit Pengguna</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12"> {{-- Full width --}}
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3 mt-3">

                                <form action="{{ route('pengguna.update', $pengguna->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    {{-- Username --}}
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Username</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="e_username" class="form-control @error('e_username') is-invalid @enderror"
                                                value="{{ old('e_username', $pengguna->username) }}" required>
                                            @error('e_username')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Nama --}}
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Nama</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="e_nama" class="form-control"
                                                value="{{ old('e_nama', $pengguna->nama) }}" required>
                                        </div>
                                    </div>

                                    {{-- Perangkat Daerah --}}
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Perangkat Daerah</label>
                                        <div class="col-sm-10">
                                            <select name="e_id_opd" class="form-select @error('e_id_opd') is-invalid @enderror" required>
                                                <option value="">Pilih</option>
                                                @foreach ($opd as $data)
                                                    <option value="{{ $data->id }}"
                                                        {{ old('e_id_opd', $pengguna->id_opd) == $data->id ? 'selected' : '' }}
                                                        {{-- Nonaktifkan jika ID ada di array dan itu bukan OPD milik pengguna saat ini --}}
                                                        @if(in_array($data->id, $assigned_opd_ids)) disabled @endif>
                                                        {{ $data->nama }}
                                                        @if(in_array($data->id, $assigned_opd_ids)) (Sudah Dipilih) @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('e_id_opd')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Level --}}
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Level</label>
                                        <div class="col-sm-10">
                                            <select name="e_level" class="form-select" required>
                                                <option value="">Pilih</option>
                                                <option value="Super Admin"
                                                    {{ old('e_level', $pengguna->level) == 'Super Admin' ? 'selected' : '' }}>
                                                    Super Admin</option>
                                                <option value="Admin"
                                                    {{ old('e_level', $pengguna->level) == 'Admin' ? 'selected' : '' }}>Admin
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                     {{-- Password --}}
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Password Baru</label>
                                        <div class="col-sm-10">
                                            <input type="password" name="e_password" class="form-control">
                                            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                                        </div>
                                    </div>


                                    {{-- Tombol --}}
                                    <div class="row mb-3">
                                        <div class="col-sm-10 offset-sm-2 d-flex gap-2">
                                            <button type="submit" class="btn btn-success">Update</button>
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
