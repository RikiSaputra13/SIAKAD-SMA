@extends('layouts.app')

@section('title', 'Data Guru')

@section('content')

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Daftar Guru</h5>
        </div>
        <div class="card-body">
            
            <a href="{{ route('admin.guru.create') }}" class="btn btn-success mb-3">Tambah Guru</a>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center">
                    <thead class="bg-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>NIP</th>
                            <th>Mata Pelajaran</th>
                            <th>Jenis Kelamin</th>
                            <th>No HP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gurus as $key => $g)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $g->nama }}</td>
                                <td>{{ $g->user->email }}</td>
                                <td>{{ $g->nip }}</td>
                                <td>{{ $g->mapel }}</td>
                                <td>{{ $g->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td>{{ $g->no_hp ?? '-' }}</td>

                                <td>
                                    <a href="{{ route('admin.guru.edit', $g->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    
                                    <form action="{{ route('admin.guru.destroy', $g->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus guru ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Belum ada data guru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

@endsection
