@extends('layouts.app')

@section('title','Lupa Password')

@section('content')
<div class="center-screen">
  <div class="card-auth">
    <div class="logo-box">
      <img src="{{ asset('logo.png') }}" alt="logo" style="height:68px;">
    </div>

    <h5 class="text-center mb-3">Lupa Password</h5>
    <p class="text-center small text-muted mb-4">
      Masukkan email atau nomor telepon yang terdaftar untuk menerima kode verifikasi
    </p>

    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show">
        @foreach($errors->all() as $error)
          {{ $error }}
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <form method="POST" action="{{ route('password.send') }}">
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
        <small class="text-muted">Anda bisa menggunakan email atau nomor telepon</small>
      </div>

      <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary-custom">Kirim Kode Verifikasi</button>
      </div>

      <div class="text-center small-muted">
        Kembali ke <a href="{{ route('login') }}">Login</a>
      </div>
    </form>
  </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection