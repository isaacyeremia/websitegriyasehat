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
      Masukkan nomor telepon yang terdaftar untuk menerima kode verifikasi
    </p>

    <form method="POST" action="{{ route('password.send') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label">Nomor Telepon</label>
        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="0812xxxx" required>
        @error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
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
@endsection