@extends('layouts.app')

@section('title','Verifikasi Kode')

@section('content')
<div class="center-screen">
  <div class="card-auth">
    <div class="logo-box">
      <img src="{{ asset('logo.png') }}" alt="logo" style="height:68px;">
    </div>

    <h5 class="text-center mb-3">Verifikasi Kode</h5>

    @if(session('success'))
      <div class="alert alert-info alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show">
        @foreach($errors->all() as $error)
          {{ $error }}
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <p class="text-center small text-muted mb-4">
      Masukkan kode verifikasi 6 digit yang ditampilkan di atas
    </p>

    <form method="POST" action="{{ route('password.verify.submit') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label">Kode Verifikasi</label>
        <input type="text" 
               name="token" 
               maxlength="6" 
               class="form-control text-center @error('token') is-invalid @enderror" 
               placeholder="000000" 
               required 
               style="letter-spacing: 0.5em; font-size: 1.5rem;"
               autofocus>
        @error('token')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary-custom">Verifikasi</button>
      </div>

      <div class="text-center small-muted">
        Tidak menerima kode? <a href="{{ route('password.request') }}">Kirim ulang</a>
      </div>
    </form>
  </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

@push('scripts')
<script>
  // Auto-format input to numbers only
  const tokenInput = document.querySelector('input[name="token"]');
  
  tokenInput.addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
  });

  // Auto submit when 6 digits entered (optional)
  tokenInput.addEventListener('input', function(e) {
    if (this.value.length === 6) {
      this.form.submit();
    }
  });
</script>
@endpush
@endsection