@extends('layouts.app')

@section('title', 'Profil Siswa')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Profil Siswa</h5>
        </div>
        <div class="card-body">

            {{-- Foto Profil --}}
            <div class="text-center mb-4">
                @php
                    // Gunakan foto jika ada, jika tidak fallback ke null
                    $fotoUrl = $siswa->foto ? asset('storage/siswa/' . $siswa->foto) : null;
                @endphp

                @if($fotoUrl)
                    <img src="{{ $fotoUrl }}" 
                         alt="Foto {{ $siswa->nama }}" 
                         class="rounded-circle border border-3 border-primary"
                         style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    {{-- Inisial jika foto belum ada --}}
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-secondary text-white"
                         style="width: 150px; height: 150px; font-size: 48px; font-weight: bold;">
                        {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                    </div>
                @endif
            </div>

            {{-- Tabel Profil --}}
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Nama</th>
                            <td>{{ $siswa->nama }}</td>
                        </tr>
                        <tr>
                            <th>NIS</th>
                            <td>{{ $siswa->nis }}</td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td>{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $siswa->alamat }}</td>
                        </tr>
                        <tr>
                            <th>Telepon Orang Tua</th>
                            <td>{{ $siswa->tlp_orang_tua }}</td>
                        </tr>
                        <tr>
                            <th>Tempat, Tanggal Lahir</th>
                            <td>{{ $siswa->tempat_lahir }}, {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $siswa->user->email ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
