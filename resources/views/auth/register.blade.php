@extends('layouts.app')

@section('title','Daftar')

@section('content')
<div class="center-screen">
  <div class="card-auth" style="width:480px;">
    <div class="logo-box">
      <img src="{{ asset('logo.png') }}" alt="logo" style="height:68px;">
    </div>

    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <form method="POST" action="/register">
      @csrf

      <div class="mb-3">
        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
        <input type="text" 
               name="name" 
               value="{{ old('name') }}" 
               class="form-control @error('name') is-invalid @enderror" 
               placeholder="Masukkan nama lengkap"
               required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="mb-3">
        <label class="form-label">NIK/KTP <span class="text-danger">*</span></label>
        <input type="text" 
               name="nik" 
               value="{{ old('nik') }}" 
               class="form-control @error('nik') is-invalid @enderror" 
               placeholder="16 digit NIK/KTP"
               maxlength="16"
               pattern="[0-9]{16}"
               required>
        @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <small class="text-muted">Masukkan 16 digit NIK sesuai KTP</small>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
          <input type="text" 
                 name="phone" 
                 value="{{ old('phone') }}" 
                 class="form-control @error('phone') is-invalid @enderror" 
                 placeholder="0812xxxxxxxx"
                 required>
          @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">Email <span class="text-danger">*</span></label>
          <input type="email" 
                 name="email" 
                 value="{{ old('email') }}" 
                 class="form-control @error('email') is-invalid @enderror" 
                 placeholder="contoh@email.com"
                 required>
          @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Alamat Lengkap</label>
        <textarea name="address" 
                  class="form-control @error('address') is-invalid @enderror" 
                  rows="2" 
                  placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Password <span class="text-danger">*</span></label>
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
        @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
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

      <div class="d-grid">
        <button type="submit" class="btn btn-primary-custom">Daftar</button>
      </div>

      <div class="small-muted text-center mt-3">
        Sudah punya akun? <a href="{{ route('login') }}">Login</a>
      </div>
    </form>
  </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

@push('scripts')
<script>
  function togglePassword(fieldId, iconId){
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

  // Validasi NIK hanya angka
  document.querySelector('input[name="nik"]').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
  });
</script>
@endpush
@endsection