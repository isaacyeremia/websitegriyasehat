@extends('layouts.app')

@section('title','Reset Password')

@section('content')
<div class="center-screen">
  <div class="card-auth">
    <div class="logo-box">
      <img src="{{ asset('logo.png') }}" alt="logo" style="height:68px;">
    </div>

    <h5 class="text-center mb-3">Reset Password</h5>
    <p class="text-center small text-muted mb-4">
      Masukkan password baru untuk akun Anda
    </p>

    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show">
        @foreach($errors->all() as $error)
          {{ $error }}
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label">Password Baru</label>
        <div class="input-group">
          <input id="password-field" 
                 type="password" 
                 name="password" 
                 class="form-control @error('password') is-invalid @enderror" 
                 placeholder="Minimal 6 karakter" 
                 required>
          <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password-field', 'eye-icon-1')">
            <i class="bi bi-eye" id="eye-icon-1"></i>
          </button>
        </div>
        @error('password')
          <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Konfirmasi Password</label>
        <div class="input-group">
          <input id="password-confirm-field" 
                 type="password" 
                 name="password_confirmation" 
                 class="form-control" 
                 placeholder="Ulangi password" 
                 required>
          <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password-confirm-field', 'eye-icon-2')">
            <i class="bi bi-eye" id="eye-icon-2"></i>
          </button>
        </div>
      </div>

      <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary-custom">Reset Password</button>
      </div>

      <div class="text-center small-muted">
        Kembali ke <a href="{{ route('login') }}">Login</a>
      </div>
    </form>
  </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

@push('scripts')
<script>
  function togglePassword(fieldId, iconId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);
    
    if (field.type === 'password') {
      field.type = 'text';
      icon.className = 'bi bi-eye-slash';
    } else {
      field.type = 'password';
      icon.className = 'bi bi-eye';
    }
  }
</script>
@endpush
@endsection