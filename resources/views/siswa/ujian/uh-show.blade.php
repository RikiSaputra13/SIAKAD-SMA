@extends('siswa.layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">{{ $ujian->judul_ujian }}</h2>

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Mata Pelajaran:</strong> {{ $ujian->mata_pelajaran }}</p>
            <p><strong>Tipe Ujian:</strong> {{ $ujian->tipeUjian->nama ?? '-' }}</p>
            <p><strong>Waktu Mulai:</strong> {{ $ujian->waktu_mulai->format('d M Y H:i') }}</p>
            <p><strong>Batas Pengumpulan:</strong> {{ $ujian->batas_pengumpulan->format('d M Y H:i') }}</p>
            <p><strong>Deskripsi:</strong> {{ $ujian->deskripsi }}</p>

            @if ($ujian->berkas_soal)
                <p><strong>Soal:</strong> 
                    <a href="{{ Storage::url($ujian->berkas_soal) }}" target="_blank" class="btn btn-sm btn-primary">
                        📄 Lihat Soal
                    </a>
                </p>
            @endif
        </div>
    </div>

    @php
        $pengumpulan = $ujian->pengumpulan->first();
    @endphp

    {{-- Jika masih dalam waktu ujian --}}
    @if ($bisaKumpul)
        <div class="card">
            <div class="card-body">
                <h5>Kumpulkan Jawaban</h5>

                @if ($pengumpulan)
                    <p class="text-success">✅ Anda sudah mengumpulkan jawaban.</p>
                    <p><strong>Nilai:</strong> {{ $pengumpulan->nilai ?? '-' }}</p>
                    <a href="{{ Storage::url($pengumpulan->berkas_jawaban) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                        📎 Lihat Jawaban
                    </a>
                @else
                    <form action="{{ route('siswa.ujian-harian.submit', $ujian->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="berkas_jawaban" class="form-label">Unggah Jawaban (PDF/DOCX)</label>
                            <input type="file" name="berkas_jawaban" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="catatan_siswa" class="form-label">Catatan (opsional)</label>
                            <textarea name="catatan_siswa" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Kumpulkan Jawaban</button>
                    </form>
                @endif
            </div>
        </div>
    @else
        {{-- Jika waktu sudah lewat --}}
        <div class="alert alert-warning mt-3">
            ⏰ Waktu ujian telah berakhir. Anda tidak dapat mengumpulkan jawaban lagi.
        </div>

        @if ($pengumpulan)
            <div class="card mt-3">
                <div class="card-body">
                    <h5>Jawaban Anda</h5>
                    <a href="{{ Storage::url($pengumpulan->berkas_jawaban) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                        📎 Lihat Jawaban
                    </a>
                    <p class="mt-2"><strong>Nilai:</strong> {{ $pengumpulan->nilai ?? '-' }}</p>
                    <p><strong>Catatan Guru:</strong> {{ $pengumpulan->catatan_guru ?? '-' }}</p>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection
