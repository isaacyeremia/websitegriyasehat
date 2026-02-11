@extends('layouts.app')

@section('title','Login')

@section('content')
<div class="center-screen">
  <div class="card-auth">
    <div class="logo-box">
      <img src="{{ asset('logo.png') }}" alt="logo" style="height:68px;">
    </div>

    <form method="POST" action="/login">
      @csrf

      <div class="mb-3">
        <label class="form-label">Nomor Telepon</label>
        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="0812xxxx" required>
        @error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
          <input id="password-field" type="password" name="password" class="form-control" placeholder="Masukkan password" required>
          <button type="button" class="btn input-eye" onclick="togglePassword()">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
              <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/>
              <path d="M8 5.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5z" fill="#fff"/>
            </svg>
          </button>
        </div>
      </div>

      <div class="d-grid mb-2">
        <button class="btn btn-primary-custom">Login</button>
      </div>

      <div class="small-muted">
        Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  function togglePassword(){
    const f = document.getElementById('password-field');
    f.type = (f.type === 'password') ? 'text' : 'password';
  }
</script>
@endpush
@endsection