 @extends('layouts.app')

@section('title','Data Kelas')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Daftar Kelas</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <a href="{{ route('admin.kelas.create') }}" class="btn btn-success mb-3">Tambah</a>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nama Kelas</th>
                            <th scope="col">Wali Kelas</th>
                            <th scope="col">Jumlah Siswa</th> <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelas as $k)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $k->nama_kelas }}</td>
                                <td>{{ $k->guru->nama ?? '-' }}</td>
                                <td>{{ $k->siswas->count() }}</td> <td>
                                    <a href="{{ route('admin.kelas.edit', ['kelas' => $k->id]) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.kelas.destroy', ['kelas' => $k->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus kelas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                 <td colspan="5" class="text-center">Belum ada data kelas.</td> </tr>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection