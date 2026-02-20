@extends('layouts.app')

@section('title', 'Tambah Terapis')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-10 mx-auto">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">➕ Tambah Terapis</h2>
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
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Form Tambah Terapis</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.terapis.store') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Nama --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Terapis <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="Contoh: Dr. John Doe, B.Med" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kondisi & Minat Klinis --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kondisi & Minat Klinis</label>
                            <input type="text" name="kondisi_minat_klinis"
                                class="form-control @error('kondisi_minat_klinis') is-invalid @enderror"
                                value="{{ old('kondisi_minat_klinis') }}"
                                placeholder="Contoh: Akupuntur, Bekam, Herbal">
                            @error('kondisi_minat_klinis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Spesialisasi atau bidang keahlian terapis.</small>
                        </div>

                        {{-- Foto --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Foto Profil</label>
                            <input type="file" name="image" id="fotoInput"
                                   class="form-control @error('image') is-invalid @enderror"
                                   accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format: JPG, PNG. Maksimal 2MB. Rasio 1:1 (square) recommended.</small>
                            
                            <div id="fotoPreview" class="mt-2" style="display:none;">
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
                            <small class="text-muted">Format bebas, contoh: "Senin & Rabu | 13.00-17.00"</small>
                        </div>

                        {{-- Daftar Harga --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Daftar Harga Layanan <span class="text-danger">*</span></label>
                            <div id="hargaContainer">
                                <div class="input-group mb-2">
                                    <input type="text" name="daftar_harga[]"
                                           class="form-control"
                                           placeholder="Contoh: Akupuntur Rp175.000"
                                           value="{{ old('daftar_harga.0') }}" required>
                                    <button type="button" class="btn btn-success" onclick="addHarga()">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <small class="text-muted">Tambahkan setiap item layanan & harga. Klik + untuk menambah baris.</small>
                        </div>

                        {{-- Urutan --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Urutan Tampilan</label>
                            <input type="number" name="urutan"
                                   class="form-control @error('urutan') is-invalid @enderror"
                                   value="{{ old('urutan', 0) }}"
                                   min="0" placeholder="0">
                            @error('urutan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Angka lebih kecil = muncul lebih dulu. Default: 0</small>
                        </div>

                        {{-- Status --}}
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                       name="is_active" id="is_active" value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Tampilkan di halaman Tentang Kami
                                </label>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Simpan Terapis
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
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <input type="text" name="daftar_harga[]" class="form-control" 
               placeholder="Contoh: Kop Jalan Rp50.000" required>
        <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">
            <i class="bi bi-trash"></i>
        </button>
    `;
    container.appendChild(div);
}
</script>
@endsection