@extends('layouts.app')

@section('title', 'Edit Produk Apotek')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">✏️ Edit Produk Apotek</h2>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.pharmacy.index') }}" class="btn btn-secondary">
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
                    <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Form Edit Produk</h5>
                </div>
                <div class="card-body">
                    
                    <form method="POST" action="{{ route('admin.pharmacy.update', $product->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $product->name) }}" 
                                   placeholder="Contoh: Minyak Gosok Cap Sehat"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   name="price" 
                                   class="form-control @error('price') is-invalid @enderror" 
                                   value="{{ old('price', $product->price) }}" 
                                   min="0"
                                   placeholder="Contoh: 50000"
                                   required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Gambar Produk</label>
                            
                            {{-- Gambar saat ini --}}
                            <div class="mb-2">
                                <label class="text-muted small">Gambar saat ini:</label><br>
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="img-thumbnail" 
                                         style="max-width: 200px;">
                                @else
                                    <span class="badge bg-secondary">Tidak ada gambar</span>
                                @endif
                            </div>
                            
                            {{-- Upload gambar baru --}}
                            <input type="file" 
                                   name="image" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   accept="image/*"
                                   id="imageInput">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.</small>
                            
                            {{-- Preview gambar baru --}}
                            <div id="imagePreview" class="mt-2" style="display: none;">
                                <label class="text-muted small">Preview gambar baru:</label><br>
                                <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Link Tokopedia</label>
                            <input type="url" 
                                   name="tokopedia_link" 
                                   class="form-control @error('tokopedia_link') is-invalid @enderror" 
                                   value="{{ old('tokopedia_link', $product->tokopedia_link) }}" 
                                   placeholder="https://tokopedia.link/xxxxx">
                            @error('tokopedia_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Link pembelian produk di Tokopedia (opsional)</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="is_active" 
                                       id="is_active" 
                                       value="1" 
                                       {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Tampilkan produk di halaman Apotek
                                </label>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Produk
                            </button>
                            <a href="{{ route('admin.pharmacy.index') }}" class="btn btn-secondary">
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
// Preview gambar sebelum upload
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').style.display = 'block';
            document.getElementById('previewImg').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endsection