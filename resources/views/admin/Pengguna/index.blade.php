@extends('components.layout')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Tabel Pengguna</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Beranda</a></li>
                    <li class="breadcrumb-item active">Pengguna</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card ">
                        <div class="card-body">
                            <!-- Header control: Tambah, Search, Tampilkan Data -->
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3 mt-3">

                                <a href="{{ route('pengguna.create') }}" class="btn btn-tambah-utama">
                                    + Tambah Pengguna
                                </a>
                                 <div class="d-flex align-items-center gap-2">
                                    <label for="showEntries">Tampilkan</label>
                                    <select id="showEntries" class="form-select form-select-sm" style="width: auto;">
                                        <option value="5">5</option>
                                        <option value="10" selected>10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                    </select>
                                    <span>entri</span>
                                </div>
                                <div class="col-12 col-md-3">
                                    <input type="text" class="form-control" placeholder="Cari di halaman ini..."
                                            id="liveSearchInput">
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="detail-table" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Username</th>
                                            <th>Perangkat Daerah</th>
                                            <th>Level</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dataTabelBody">
                                        @foreach ($pengguna as $data)
                                            <tr>
                                                <td>{{ $loop->index }}</td>
                                                <td>{{ $data->username }}</td>
                                                <td>{{ $data->opd->nama ?? '-' }}</td>



                                                <td>{{ $data->level }}</td>
                                                <td class="text-center align-middle">
                                                    <div class="d-flex justify-content-center gap-1">
                                                        <form action="{{ route('pengguna.edit', $data->id) }}"
                                                            method="GET">
                                                            <button class="btn btn-tambah-utama btn-sm">
                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                            </button>
                                                        </form>
                                                        <form id="formDelete-{{ $data->id }}"
                                                            action="{{ route('pengguna.destroy', $data->id) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-danger btn-sm"
                                                                onclick="confirmDelete('{{ $data->id }}')">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>
                                                        </form>

                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                             <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3">
                                <div id="paginationInfo"></div>
                                <div id="paginationControls"></div>
                            </div>
                            <!-- End Table -->
                        </div>
                    </div>
                </div>
        </section>
    </main>
@endsection
