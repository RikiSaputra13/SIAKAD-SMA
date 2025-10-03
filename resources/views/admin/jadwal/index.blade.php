@extends('layouts.app')

@section('title', 'Daftar Jadwal')

@section('content')

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Daftar Jadwal</h5>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form Filter Kelas -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Filter Jadwal</h6>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.jadwal.index') }}" class="row g-3">
                                <div class="col-md-4">
                                    <label for="kelas_id" class="form-label">Kelas</label>
                                    <select name="kelas_id" id="kelas_id" class="form-select">
                                        <option value="">Semua Kelas</option>
                                        @foreach($kelas as $kelasItem)
                                            <option value="{{ $kelasItem->id }}" {{ request('kelas_id') == $kelasItem->id ? 'selected' : '' }}>
                                                {{ $kelasItem->nama_kelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                                    <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.jadwal.create') }}" class="btn btn-success mb-3">Tambah</a>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Guru</th>
                            <th scope="col">Kelas</th>
                            <th scope="col">Mata Pelajaran</th>
                            <th scope="col">Hari</th>
                            <th scope="col">Jam Mulai</th>
                            <th scope="col">Jam Selesai</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwals as $jadwal)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ optional($jadwal->guru)->nama ?? '-' }}</td>
                                <td>{{ optional($jadwal->kelas)->nama_kelas ?? '-' }}</td>
                                <td>{{ $jadwal->mata_pelajaran }}</td>
                                <td>{{ $jadwal->hari }}</td>
                                <td>{{ $jadwal->jam_mulai }}</td>
                                <td>{{ $jadwal->jam_selesai }}</td>
                                <td>
                                    <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada data jadwal.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection