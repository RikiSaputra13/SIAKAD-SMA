@extends('layouts.app')

@section('title', 'Data Guru')

@section('content')

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Daftar Guru</h5>
        </div>
        <div class="card-body">
            <!-- Tombol tambah guru -->
            <a href="{{ route('admin.guru.create') }}" class="btn btn-success mb-3">Tambah</a>

            <!-- Tampilkan pesan sukses -->
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Tabel guru -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nama</th>
                            <th scope="col">NIP</th>
                            <th scope="col">Mata Pelajaran</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">Jenis Kelamin</th>
                            <th scope="col">No HP</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($guru as $key => $g)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>{{ $g->nama }}</td>
                                <td>{{ $g->nip }}</td>
                                <td>{{ $g->mapel }}</td>
                                <td>{{ $g->alamat }}</td>
                                <td>{{ $g->jenis_kelamin }}</td>
                                <td>{{ $g->no_hp }}</td>
                                <td>
                                    <a href="{{ route('admin.guru.edit', $g->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.guru.destroy', $g->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada data guru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection