@extends('layouts.app')

@section('title','Login')

@section('content')
<div class="center-screen">
  <div class="card-auth">
    <div class="logo-box">
      <img src="{{ asset('logo.png') }}" alt="logo" style="height:68px;">
    </div>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <form method="POST" action="/login">
      @csrf

      <div class="mb-3">
        <label class="form-label">Email atau Nomor Telepon</label>
        <input type="text" 
               name="login" 
               value="{{ old('login') }}" 
               class="form-control @error('login') is-invalid @enderror" 
               placeholder="contoh@email.com atau 0812xxxx" 
               required>
        @error('login')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Anda bisa login dengan email atau nomor telepon</small>
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
          <input id="password-field" 
                 type="password" 
                 name="password" 
                 class="form-control" 
                 placeholder="Masukkan password" 
                 required>
          <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
            <i class="bi bi-eye" id="eye-icon"></i>
          </button>
        </div>
      </div>

      <div class="text-end mb-3">
        <a href="{{ route('password.request') }}" class="small text-decoration-none">Lupa Password?</a>
      </div>

      <div class="d-grid mb-2">
        <button class="btn btn-primary-custom">Login</button>
      </div>

      <div class="small-muted text-center">
        Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
      </div>
    </form>
  </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

@push('scripts')
<script>
  function togglePassword(){
    const field = document.getElementById('password-field');
    const icon = document.getElementById('eye-icon');
    
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