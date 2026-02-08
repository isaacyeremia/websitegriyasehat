<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Terapis Register - Griya Sehat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-light">

<div class="container">
  <div class="row justify-content-center align-items-center min-vh-100 py-5">
    <div class="col-md-6">
      
      <div class="card shadow-lg border-0">
        <div class="card-body p-5">
          
          {{-- Logo --}}
          <div class="text-center mb-4">
            <img src="{{ asset('logo.png') }}" alt="logo" style="height:60px;">
            <h4 class="mt-3 fw-bold text-success">Daftar Terapis</h4>
            <p class="text-muted">Buat akun Terapis baru</p>
          </div>

          {{-- Error Messages --}}
          @if($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          {{-- Form Register --}}
          <form method="POST" action="{{ route('terapis.register.submit') }}" id="registerForm">
            @csrf

            <div class="mb-3">
              <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
              <input type="text" 
                     name="name" 
                     id="name"
                     class="form-control @error('name') is-invalid @enderror" 
                     placeholder="Nama lengkap"
                     value="{{ old('name') }}"
                     required>
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
              <input type="text" 
                     name="phone" 
                     id="phone"
                     class="form-control @error('phone') is-invalid @enderror" 
                     placeholder="0812xxxx"
                     value="{{ old('phone') }}"
                     required>
              @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" 
                     name="email" 
                     id="email"
                     class="form-control @error('email') is-invalid @enderror" 
                     placeholder="email@example.com"
                     value="{{ old('email') }}"
                     required>
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Alamat</label>
              <textarea name="address" 
                        class="form-control @error('address') is-invalid @enderror" 
                        placeholder="Alamat lengkap"
                        rows="2">{{ old('address') }}</textarea>
              @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Password <span class="text-danger">*</span></label>
              <input type="password" 
                     name="password" 
                     id="password"
                     class="form-control @error('password') is-invalid @enderror" 
                     placeholder="Min. 6 karakter"
                     required>
              @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
              <input type="password" 
                     name="password_confirmation" 
                     id="password_confirmation"
                     class="form-control" 
                     placeholder="Ulangi password"
                     required>
            </div>

            {{-- Tombol Generate Kode --}}
            <div class="mb-4">
              <button type="button" 
                      class="btn btn-warning w-100" 
                      id="generateCodeBtn"
                      onclick="generateTerapisCode()">
                <i class="bi bi-key"></i> Generate Kode Terapis
              </button>
              <small class="text-muted d-block mt-1">
                Klik tombol di atas setelah mengisi semua data untuk mendapatkan kode terapis
              </small>
            </div>

            {{-- Field Kode Terapis (readonly, akan terisi otomatis) --}}
            <div class="mb-4" id="codeSection" style="display: none;">
              <label class="form-label">Kode Terapis <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="text" 
                       name="terapis_code" 
                       id="terapis_code"
                       class="form-control bg-light @error('terapis_code') is-invalid @enderror" 
                       placeholder="Kode akan muncul otomatis"
                       readonly
                       value="{{ old('terapis_code') }}">
                <button class="btn btn-outline-secondary" type="button" onclick="copyCode()">
                  <i class="bi bi-clipboard"></i> Copy
                </button>
              </div>
              <small class="text-success d-block mt-1" id="codeMessage"></small>
              @error('terapis_code')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <button type="submit" class="btn btn-success w-100 btn-lg mb-3" id="submitBtn" disabled>
              Daftar sebagai Terapis
            </button>

            <div class="text-center">
              <small class="text-muted">
                Sudah punya akun? 
                <a href="{{ route('terapis.login') }}" class="text-success">Login</a>
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
// Validasi semua field terisi sebelum generate kode
function validateFields() {
  const name = document.getElementById('name').value.trim();
  const phone = document.getElementById('phone').value.trim();
  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value;
  const passwordConfirm = document.getElementById('password_confirmation').value;

  if (!name || !phone || !email || !password || !passwordConfirm) {
    alert('Harap isi semua field yang wajib (bertanda *) terlebih dahulu!');
    return false;
  }

  if (password.length < 6) {
    alert('Password minimal 6 karakter!');
    return false;
  }

  if (password !== passwordConfirm) {
    alert('Konfirmasi password tidak cocok!');
    return false;
  }

  return true;
}

// Generate kode terapis
async function generateTerapisCode() {
  if (!validateFields()) {
    return;
  }

  const btn = document.getElementById('generateCodeBtn');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Generating...';

  const formData = {
    name: document.getElementById('name').value.trim(),
    phone: document.getElementById('phone').value.trim(),
    email: document.getElementById('email').value.trim(),
    password: document.getElementById('password').value,
  };

  console.log('Sending data:', formData);

  try {
    const response = await fetch('{{ route("terapis.generate.code") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json'
      },
      body: JSON.stringify(formData)
    });

    console.log('Response status:', response.status);

    const data = await response.json();
    console.log('Response data:', data);

    if (response.ok && data.success) {
      // Tampilkan kode
      document.getElementById('terapis_code').value = data.terapis_code;
      document.getElementById('codeSection').style.display = 'block';
      document.getElementById('codeMessage').textContent = '✓ Kode terapis berhasil di-generate! Simpan kode ini.';
      
      // Enable tombol submit
      document.getElementById('submitBtn').disabled = false;
      
      // Ubah tombol generate
      btn.innerHTML = '<i class="bi bi-check-circle"></i> Kode Sudah Di-generate';
      btn.classList.remove('btn-warning');
      btn.classList.add('btn-success');
      
    } else {
      // Tampilkan error yang spesifik
      let errorMessage = 'Gagal generate kode:\n\n';
      
      if (data.errors && Object.keys(data.errors).length > 0) {
        for (const field in data.errors) {
          errorMessage += '• ' + data.errors[field][0] + '\n';
        }
      } else if (data.message) {
        errorMessage = data.message;
      } else {
        errorMessage = 'Terjadi kesalahan yang tidak diketahui.';
      }
      
      alert(errorMessage);
      btn.disabled = false;
      btn.innerHTML = '<i class="bi bi-key"></i> Generate Kode Terapis';
    }
  } catch (error) {
    console.error('Fetch error:', error);
    alert('Terjadi kesalahan jaringan:\n\n' + error.message);
    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-key"></i> Generate Kode Terapis';
  }
}

// Copy kode ke clipboard
function copyCode() {
  const code = document.getElementById('terapis_code');
  code.select();
  document.execCommand('copy');
  alert('Kode berhasil di-copy!');
}
</script>

</body>
</html>