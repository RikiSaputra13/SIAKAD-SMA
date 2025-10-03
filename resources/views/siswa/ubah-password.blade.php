@extends('siswa.layouts.app')

@section('title', 'Ubah Password')
@section('header', 'Ubah Password')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Ubah Password</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ route('siswa.ubah-password') }}">
                @csrf

                {{-- Password Saat Ini --}}
                <div class="mb-3">
                    <label for="current_password" class="form-label">Password Saat Ini</label>
                    <div class="input-group">
                        <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                            <i class="fas fa-eye"></i>
                        </button>
                        @error('current_password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Password Baru --}}
                <div class="mb-3">
                    <label for="new_password" class="form-label">Password Baru</label>
                    <div class="input-group">
                        <input type="password" name="new_password" id="new_password" class="form-control @error('new_password') is-invalid @enderror" required>
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password">
                            <i class="fas fa-eye"></i>
                        </button>
                        @error('new_password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Konfirmasi Password Baru --}}
                <div class="mb-3">
                    <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                    <div class="input-group">
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password_confirmation">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-2">Ubah Password</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleButtons = document.querySelectorAll('.toggle-password');

    toggleButtons.forEach(button => {
        button.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
});
</script>
@endpush
