<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login - Griya Sehat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container">
  <div class="row justify-content-center align-items-center min-vh-100">
    <div class="col-md-5">
      
      <div class="card shadow-lg border-0">
        <div class="card-body p-5">
          
          {{-- Logo --}}
          <div class="text-center mb-4">
            <img src="{{ asset('logo.png') }}" alt="logo" style="height:60px;">
            <h4 class="mt-3 fw-bold">Admin Login</h4>
            <p class="text-muted">Masuk sebagai Administrator</p>
          </div>

          {{-- Error Messages --}}
          @if($errors->any())
            <div class="alert alert-danger">
              {{ $errors->first() }}
            </div>
          @endif

          {{-- Form Login --}}
          <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <div class="mb-3">
              <label class="form-label">Nomor Telepon</label>
              <input type="text" 
                     name="phone" 
                     class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                     placeholder="0812xxxx"
                     value="{{ old('phone') }}"
                     required>
              @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-4">
              <label class="form-label">Password</label>
              <div class="input-group">
                <input type="password" 
                       name="password" 
                       id="password"
                       class="form-control form-control-lg @error('password') is-invalid @enderror" 
                       placeholder="Masukkan password"
                       required>
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                  <i class="bi bi-eye" id="toggleIcon"></i>
                </button>
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <button type="submit" class="btn btn-danger w-100 btn-lg mb-3">
              Login sebagai Admin
            </button>

            <div class="text-center">
              <small class="text-muted">
                Belum punya akun admin? 
                <a href="{{ route('admin.register') }}" class="text-danger">Daftar</a>
              </small>
              <br>
              <small class="text-muted mt-2 d-block">
                <a href="{{ route('login') }}" class="text-muted">← Kembali ke Login User</a>
              </small>
            </div>

          </form>

        </div>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<script>
function togglePassword() {
  const password = document.getElementById('password');
  const icon = document.getElementById('toggleIcon');
  
  if (password.type === 'password') {
    password.type = 'text';
    icon.classList.remove('bi-eye');
    icon.classList.add('bi-eye-slash');
  } else {
    password.type = 'password';
    icon.classList.remove('bi-eye-slash');
    icon.classList.add('bi-eye');
  }
}
</script>

</body>
</html>