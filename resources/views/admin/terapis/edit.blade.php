@extends('layouts.app')

@section('title', 'Edit Terapis')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-10 mx-auto">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">✏️ Edit Terapis</h2>
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
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Form Edit Terapis</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.terapis.update', $terapis->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Nama --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Terapis <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $terapis->name) }}"
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
                                value="{{ old('kondisi_minat_klinis', $terapis->kondisi_minat_klinis) }}"
                                placeholder="Contoh: Akupuntur, Bekam, Herbal">
                            @error('kondisi_minat_klinis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Foto --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Foto Profil</label>

                            {{-- Foto saat ini --}}
                            <div class="mb-2">
                                <label class="text-muted small">Foto saat ini:</label><br>
                                @if($terapis->image)
                                    <img src="{{ asset('storage/' . $terapis->image) }}"
                                         alt="{{ $terapis->nama }}"
                                         class="img-thumbnail rounded-circle"
                                         style="width:100px; height:100px; object-fit:cover;">
                                @else
                                    <span class="badge bg-secondary">Tidak ada foto</span>
                                @endif
                            </div>

                            {{-- Upload baru --}}
                            <input type="file" name="image" id="fotoInput"
                                   class="form-control @error('image') is-invalid @enderror"
                                   accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah foto. Format: JPG, PNG. Max 2MB.</small>

                            <div id="fotoPreview" class="mt-2" style="display:none;">
                                <label class="text-muted small">Preview foto baru:</label><br>
                                <img id="previewImg" src="" alt="Preview"
                                     class="img-thumbnail rounded-circle"
                                     style="width:100px; height:100px; object-fit:cover;">
                            </div>
                        </div>

                        {{-- Jadwal --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jadwal Praktik <span class="text-danger">*</span></label>
                            <input type="text" name="schedule"
                                   class="form-control @error('schedule') is-invalid @enderror"
                                   value="{{ old('schedule', $terapis->schedule) }}"
                                   placeholder="Contoh: Senin & Rabu | 08.00-12.00" required>
                            @error('schedule')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Daftar Harga --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Daftar Harga Layanan <span class="text-danger">*</span></label>
                            <div id="hargaContainer">
                                @foreach(old('daftar_harga', $terapis->daftar_harga) as $index => $harga)
                                    <div class="input-group mb-2">
                                        <input type="text" name="daftar_harga[]"
                                               class="form-control"
                                               value="{{ $harga }}"
                                               placeholder="Contoh: Akupuntur Rp175.000" required>
                                        @if($index === 0)
                                            <button type="button" class="btn btn-success" onclick="addHarga()">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Urutan --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Urutan Tampilan</label>
                            <input type="number" name="urutan"
                                   class="form-control @error('urutan') is-invalid @enderror"
                                   value="{{ old('urutan', $terapis->urutan) }}"
                                   min="0" placeholder="0">
                            @error('urutan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                       name="is_active" id="is_active" value="1"
                                       {{ old('is_active', $terapis->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Tampilkan di halaman Tentang Kami
                                </label>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Terapis
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