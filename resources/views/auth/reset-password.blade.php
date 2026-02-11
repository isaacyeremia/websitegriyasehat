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

    <form method="POST" action="{{ route('password.update') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label">Password Baru</label>
        <div class="input-group">
          <input id="password-field" type="password" name="password" class="form-control" 
                 placeholder="Minimal 6 karakter" required>
          <button type="button" class="btn input-eye" onclick="togglePassword('password-field')">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
              <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/>
              <path d="M8 5.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5z" fill="#fff"/>
            </svg>
          </button>
        </div>
        @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Konfirmasi Password</label>
        <div class="input-group">
          <input id="password-confirm-field" type="password" name="password_confirmation" 
                 class="form-control" placeholder="Ulangi password" required>
          <button type="button" class="btn input-eye" onclick="togglePassword('password-confirm-field')">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
              <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/>
              <path d="M8 5.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5z" fill="#fff"/>
            </svg>
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

@push('scripts')
<script>
  function togglePassword(fieldId) {
    const f = document.getElementById(fieldId);
    f.type = (f.type === 'password') ? 'text' : 'password';
  }
</script>
@endpush
@endsection