@extends('layouts.app')

@section('title', 'Profil Admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Profil Admin</h1>

    <div class="card p-4 shadow-sm">
        <div class="d-flex align-items-center gap-4">
            {{-- Foto Profil --}}
            <img src="{{ url('adminlte/img/admin.png') }}" 
                 alt="Foto Profil" 
                 class="img-thumbnail rounded-circle profile-img"
                 style="height:120px; width:120px; object-fit:cover; transition: all 0.3s ease;">

            <div>
                <h3>{{ $user->name }}</h3>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                <a href="{{ route('password.direct-change') }}" class="btn btn-sm btn-primary mt-2">Ubah Password</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .profile-img:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
</style>
@endpush
