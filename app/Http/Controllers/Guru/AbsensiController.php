<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();
        
        // Ambil hanya absensi dari kelas yang diajar guru ini
        $kelasIds = \App\Models\Jadwal::where('guru_id', $guru->id)
                    ->pluck('kelas_id')
                    ->unique();
        
        // Query dasar
        $query = Absensi::whereHas('siswa', function($query) use ($kelasIds) {
                    $query->whereIn('kelas_id', $kelasIds);
                })
                ->with('siswa.kelas');
        
        // Filter berdasarkan tanggal
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->dari_tanggal);
        }
        
        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->sampai_tanggal);
        }
        
        // Filter berdasarkan kelas
        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }
        
        // Filter berdasarkan status
        if ($request->filled('status') && $request->status != 'Semua') {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }
        
        // Hitung statistik
        $statsQuery = clone $query;
        $stats = [
            'Hadir' => (clone $statsQuery)->where('status', 'Hadir')->count(),
            'Sakit' => (clone $statsQuery)->where('status', 'Sakit')->count(),
            'Izin' => (clone $statsQuery)->where('status', 'Izin')->count(),
            'Alpha' => (clone $statsQuery)->where('status', 'Alpha')->count(),
        ];
        
        $absensis = $query->latest()->paginate(10);
        $kelas = Kelas::whereIn('id', $kelasIds)->get();
        
        return view('guru.absensi.index', compact('absensis', 'kelas', 'stats'));
    }

    public function create()
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();
        
        $kelasIds = \App\Models\Jadwal::where('guru_id', $guru->id)
                    ->pluck('kelas_id')
                    ->unique();
        
        $siswas = Siswa::whereIn('kelas_id', $kelasIds)->get();
        
        return view('guru.absensi.create', compact('siswas'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();
        
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:Hadir,Izin,Sakit,Alpha',
            'keterangan_izin' => 'nullable|string|max:255'
        ]);
        
        // Authorization check
        $kelasIds = \App\Models\Jadwal::where('guru_id', $guru->id)
                    ->pluck('kelas_id')
                    ->unique();
        
        $siswa = Siswa::findOrFail($validated['siswa_id']);
        if (!$kelasIds->contains($siswa->kelas_id)) {
            abort(403, 'Akses ditolak. Anda tidak mengajar di kelas ini.');
        }
        
        // Cek apakah sudah ada absensi untuk siswa pada tanggal tersebut
        $existing = Absensi::where('siswa_id', $validated['siswa_id'])
                    ->whereDate('tanggal', $validated['tanggal'])
                    ->first();
        
        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Absensi untuk siswa ini pada tanggal tersebut sudah ada.');
        }
        
        Absensi::create($validated);
        
        return redirect()->route('guru.absensi.index')
            ->with('success', 'Absensi berhasil ditambahkan.');
    }

    public function edit(Absensi $absensi)
    {
        // Authorization check - hanya bisa edit absensi siswa di kelasnya
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();
        
        $kelasIds = \App\Models\Jadwal::where('guru_id', $guru->id)
                    ->pluck('kelas_id')
                    ->unique();
                    
        if (!$kelasIds->contains($absensi->siswa->kelas_id)) {
            abort(403, 'Akses ditolak. Anda tidak mengajar di kelas ini.');
        }

        $siswas = Siswa::whereIn('kelas_id', $kelasIds)->get();
        return view('guru.absensi.edit', compact('absensi', 'siswas'));
    }

    public function update(Request $request, Absensi $absensi)
    {
        // Authorization check
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();
        
        $kelasIds = \App\Models\Jadwal::where('guru_id', $guru->id)
                    ->pluck('kelas_id')
                    ->unique();
                    
        if (!$kelasIds->contains($absensi->siswa->kelas_id)) {
            abort(403, 'Akses ditolak. Anda tidak mengajar di kelas ini.');
        }

        $validated = $request->validate([
            'status'         => 'required|in:Hadir,Izin,Sakit,Alpha',
            'tanggal'        => 'required|date',
            'keterangan_izin'=> 'nullable|string'
        ]);

        $absensi->update($validated);

        return redirect()->route('guru.absensi.index')
                         ->with('success', 'Absensi berhasil diperbarui.');
    }

    public function destroy(Absensi $absensi)
    {
        // Authorization check
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();
        
        $kelasIds = \App\Models\Jadwal::where('guru_id', $guru->id)
                    ->pluck('kelas_id')
                    ->unique();
                    
        if (!$kelasIds->contains($absensi->siswa->kelas_id)) {
            abort(403, 'Akses ditolak. Anda tidak mengajar di kelas ini.');
        }

        $absensi->delete();
        
        return redirect()->route('guru.absensi.index')
                         ->with('success', 'Absensi berhasil dihapus.');
    }

    // Method untuk rekap (ajax)
    public function rekap(Request $request)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();
        
        $kelasIds = \App\Models\Jadwal::where('guru_id', $guru->id)
                    ->pluck('kelas_id')
                    ->unique();
        
        $query = Siswa::whereIn('kelas_id', $kelasIds)
                ->with(['absensis' => function($q) use ($request) {
                    if ($request->filled('start_date')) {
                        $q->whereDate('tanggal', '>=', $request->start_date);
                    }
                    if ($request->filled('end_date')) {
                        $q->whereDate('tanggal', '<=', $request->end_date);
                    }
                }])
                ->with('kelas');
        
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }
        
        $siswas = $query->get();
        
        $rekap = [];
        foreach ($siswas as $siswa) {
            $hadir = $siswa->absensis->where('status', 'Hadir')->count();
            $sakit = $siswa->absensis->where('status', 'Sakit')->count();
            $izin = $siswa->absensis->where('status', 'Izin')->count();
            $alpha = $siswa->absensis->where('status', 'Alpha')->count();
            
            $rekap[] = [
                'nis' => $siswa->nis,
                'nama_siswa' => $siswa->nama,
                'kelas' => $siswa->kelas->nama_kelas,
                'Hadir' => $hadir,
                'Sakit' => $sakit,
                'Izin' => $izin,
                'Alpha' => $alpha,
                'total' => $hadir + $sakit + $izin + $alpha
            ];
        }
        
        return response()->json([
            'success' => true,
            'rekap' => $rekap
        ]);
    }
}