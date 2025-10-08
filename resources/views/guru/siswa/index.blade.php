@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Daftar Siswa</h5>
        </div>
        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nama</th>
                            <th scope="col">NIS</th>
                            <th scope="col">Kelas</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">Telepon Orang Tua</th>
                            <th scope="col">Email</th>
                            <th scope="col">Jenis Kelamin</th>
                            <th scope="col">Tempat Lahir</th>
                            <th scope="col">Tanggal Lahir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswas as $siswa)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $siswa->nama }}</td>
                                <td>{{ $siswa->nis }}</td>
                                {{-- KODE INI YANG DIPERBAIKI --}}
                                <td>{{ optional($siswa->kelas)->nama_kelas }}</td>
                                <td>{{ $siswa->alamat }}</td>
                                <td>{{ $siswa->tlp_orang_tua }}</td>
                                <td>{{ $siswa->user->email }}</td>
                                <td>{{ $siswa->jenis_kelamin }}</td>
                                <td>{{ $siswa->tempat_lahir }}</td>
                                <td>{{ $siswa->tanggal_lahir }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">Belum ada data siswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection