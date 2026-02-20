@extends('layouts.app')

@section('title', 'Tambah Dokter/Terapis')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-10 mx-auto">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">➕ Tambah Dokter/Terapis</h2>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.terapis.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Terdapat kesalahan:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Form Tambah Dokter/Terapis</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.terapis.store') }}" enctype="multipart/form-data" id="doctorForm">
                        @csrf

                        {{-- Nama --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="Contoh: Dr. John Doe, B.Med" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Foto --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Foto Profil</label>
                            <input type="file" name="image" id="fotoInput"
                                   class="form-control @error('image') is-invalid @enderror"
                                   accept="image/jpeg,image/png,image/jpg">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format: JPG, PNG. Maksimal 2MB. Rasio 1:1 (square) recommended.</small>
                            
                            <div id="fotoPreview" class="mt-2" style="display:none;">
                                <label class="text-muted small">Preview:</label><br>
                                <img id="previewImg" src="" alt="Preview" 
                                     class="img-thumbnail rounded-circle" 
                                     style="width:120px; height:120px; object-fit:cover;">
                            </div>
                        </div>

                        {{-- Jadwal --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jadwal Praktik <span class="text-danger">*</span></label>
                            <input type="text" name="schedule"
                                   class="form-control @error('schedule') is-invalid @enderror"
                                   value="{{ old('schedule') }}"
                                   placeholder="Contoh: Senin & Rabu | 08.00-12.00" required>
                            @error('schedule')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format bebas. Contoh: "Senin & Rabu | 13.00-17.00"</small>
                        </div>

                        {{-- Daftar Harga --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                Daftar Harga Layanan 
                                <span class="text-muted">(opsional - untuk ditampilkan di halaman Tentang Kami)</span>
                            </label>
                            <div id="hargaContainer">
                                @if(old('daftar_harga'))
                                    @foreach(old('daftar_harga') as $index => $harga)
                                        <div class="input-group mb-2 harga-item">
                                            <input type="text" name="daftar_harga[]"
                                                   class="form-control"
                                                   value="{{ $harga }}"
                                                   placeholder="Contoh: Akupuntur Rp175.000">
                                            @if($index === 0)
                                                <button type="button" class="btn btn-success" onclick="addHarga()">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-danger" onclick="removeHarga(this)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <div class="input-group mb-2 harga-item">
                                        <input type="text" name="daftar_harga[]"
                                               class="form-control"
                                               placeholder="Contoh: Akupuntur Rp175.000">
                                        <button type="button" class="btn btn-success" onclick="addHarga()">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <small class="text-muted">
                                Tambahkan setiap item layanan & harga. Klik <i class="bi bi-plus"></i> untuk menambah baris. 
                                Kosongkan jika tidak ingin menampilkan harga.
                            </small>
                        </div>

                        {{-- Urutan --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Urutan Tampilan</label>
                            <input type="number" name="urutan"
                                   class="form-control @error('urutan') is-invalid @enderror"
                                   value="{{ old('urutan', 0) }}"
                                   min="0" max="999" placeholder="0">
                            @error('urutan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Angka lebih kecil = muncul lebih dulu. Default: 0</small>
                        </div>

                        {{-- Status Aktif --}}
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                       name="is_active" id="is_active" value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <strong>Status Aktif</strong> - Dokter dapat dipilih untuk booking antrian
                                </label>
                            </div>
                        </div>

                        {{-- Show in About --}}
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                       name="show_in_about" id="show_in_about" value="1"
                                       {{ old('show_in_about', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_in_about">
                                    <strong>Tampilkan di Halaman Tentang Kami</strong> - Foto & harga layanan akan muncul di halaman About
                                </label>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Simpan Dokter
                            </button>
                            <a href="{{ route('admin.terapis.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<script>
// Preview foto
document.getElementById('fotoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Validasi ukuran (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 2MB.');
            this.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('fotoPreview').style.display = 'block';
            document.getElementById('previewImg').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Add more harga fields
function addHarga() {
    const container = document.getElementById('hargaContainer');
    const div = document.createElement('div');
    div.className = 'input-group mb-2 harga-item';
    div.innerHTML = `
        <input type="text" name="daftar_harga[]" class="form-control" 
               placeholder="Contoh: Kop Jalan Rp50.000">
        <button type="button" class="btn btn-danger" onclick="removeHarga(this)">
            <i class="bi bi-trash"></i>
        </button>
    `;
    container.appendChild(div);
}

// Remove harga field
function removeHarga(button) {
    const item = button.closest('.harga-item');
    if (item) {
        item.remove();
        
        // Update first item button if needed
        updateFirstItemButton();
    }
}

// Update first item button (harus punya + button)
function updateFirstItemButton() {
    const items = document.querySelectorAll('.harga-item');
    if (items.length > 0) {
        items.forEach((item, index) => {
            const btnGroup = item.querySelector('.btn-success, .btn-danger');
            if (index === 0) {
                // First item should have + button
                if (btnGroup && btnGroup.classList.contains('btn-danger')) {
                    btnGroup.className = 'btn btn-success';
                    btnGroup.setAttribute('onclick', 'addHarga()');
                    btnGroup.innerHTML = '<i class="bi bi-plus"></i>';
                }
            } else {
                // Other items should have - button
                if (btnGroup && btnGroup.classList.contains('btn-success')) {
                    btnGroup.className = 'btn btn-danger';
                    btnGroup.setAttribute('onclick', 'removeHarga(this)');
                    btnGroup.innerHTML = '<i class="bi bi-trash"></i>';
                }
            }
        });
    }
}

// Prevent double submit
document.getElementById('doctorForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
});
</script>
@endsection