@extends('layouts.app')

@section('title','Daftar')

@section('content')
<div class="center-screen">
  <div class="card-auth" style="width:420px;">
    <div class="logo-box">
      <img src="{{ asset('logo.png') }}" alt="logo" style="height:68px;">
    </div>

    <form method="POST" action="/register">
      @csrf

      <div class="mb-2">
        <label class="form-label">Nama</label>
        <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
        @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>

      <div class="mb-2">
        <label class="form-label">No Telepon</label>
        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" required>
        @error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>

      <div class="mb-2">
        <label class="form-label">Alamat</label>
        <input type="text" name="address" value="{{ old('address') }}" class="form-control">
      </div>

      <div class="mb-2">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
        @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control" required>
      </div>

      <div class="d-grid">
        <button class="btn btn-primary-custom">Daftar</button>
      </div>

      <div class="small-muted">
        Sudah punya akun? <a href="{{ route('login') }}">Login</a>
      </div>
    </form>
  </div>
</div>
@endsection