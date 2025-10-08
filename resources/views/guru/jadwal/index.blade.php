@extends('layouts.app')

@section('title', 'Jadwal Mengajar Saya')

@section('content')

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Jadwal Mengajar - {{ auth()->user()->name ?? auth()->user()->nama }}</h5>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form Filter Kelas (Opsional) -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Filter Jadwal</h6>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('guru.jadwal.index') }}" class="row g-3">
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
                                    <a href="{{ route('guru.jadwal.index') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Kelas</th>
                            <th scope="col">Mata Pelajaran</th>
                            <th scope="col">Hari</th>
                            <th scope="col">Jam Mulai</th>
                            <th scope="col">Jam Selesai</th>
                            <th scope="col">Ruangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwals as $jadwal)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ optional($jadwal->kelas)->nama_kelas ?? '-' }}</td>
                                <td>{{ $jadwal->mata_pelajaran }}</td>
                                <td>{{ $jadwal->hari }}</td>
                                <td>{{ $jadwal->jam_mulai }}</td>
                                <td>{{ $jadwal->jam_selesai }}</td>
                                <td>{{ $jadwal->ruangan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada jadwal mengajar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Summary -->
            @if($jadwals->count() > 0)
                <div class="mt-4 p-3 bg-light rounded">
                    <h6>Ringkasan Jadwal:</h6>
                    <p>Total <strong>{{ $jadwals->count() }}</strong> jadwal mengajar</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection