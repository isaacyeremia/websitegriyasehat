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

    <p class="text-center small text-muted mb-4">
      Masukkan kode verifikasi 6 digit yang ditampilkan di atas
    </p>

    <form method="POST" action="{{ route('password.verify.submit') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label">Kode Verifikasi</label>
        <input type="text" name="token" maxlength="6" class="form-control text-center" 
               placeholder="000000" required style="letter-spacing: 0.5em; font-size: 1.5rem;">
        @error('token')<div class="text-danger small">{{ $message }}</div>@enderror
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

@push('scripts')
<script>
  // Auto-format input to numbers only
  document.querySelector('input[name="token"]').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
  });
</script>
@endpush
@endsection