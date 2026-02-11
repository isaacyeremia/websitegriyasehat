@extends('layouts.app')

@section('title', 'Kelola Produk Apotek')

@section('content')
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">💊 Kelola Produk Apotek</h2>
            <p class="text-muted mb-0">Manajemen katalog produk yang tampil di halaman Apotek</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Dashboard
            </a>
            <a href="{{ route('admin.pharmacy.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Tambah Produk
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Statistik Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card bg-primary">
                <div class="stat-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $products->total() }}</h3>
                    <p>Total Produk</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-success">
                <div class="stat-icon">
                    <i class="bi bi-eye"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $products->where('is_active', true)->count() }}</h3>
                    <p>Produk Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-secondary">
                <div class="stat-icon">
                    <i class="bi bi-eye-slash"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $products->where('is_active', false)->count() }}</h3>
                    <p>Produk Nonaktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-info">
                <div class="stat-icon">
                    <i class="bi bi-link-45deg"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $products->whereNotNull('tokopedia_link')->count() }}</h3>
                    <p>Dengan Link</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.pharmacy.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-bold">
                        <i class="bi bi-search"></i> Cari Produk
                    </label>
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           value="{{ request('search') }}" 
                           placeholder="Nama produk...">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">
                        <i class="bi bi-funnel"></i> Status
                    </label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">
                        <i class="bi bi-sort-down"></i> Urutkan
                    </label>
                    <select name="sort" class="form-select">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('admin.pharmacy.index') }}" class="btn btn-secondary w-100 mt-2">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Produk --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-list-ul me-2"></i>Daftar Produk Apotek
                <span class="badge bg-primary">{{ $products->total() }}</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3" width="5%">No</th>
                            <th width="10%">Gambar</th>
                            <th width="25%">Nama Produk</th>
                            <th width="12%">Harga</th>
                            <th width="15%">Link Tokopedia</th>
                            <th width="10%">Status</th>
                            <th width="13%">Dibuat</th>
                            <th class="text-center" width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $index => $product)
                            <tr>
                                <td class="px-3">{{ $products->firstItem() + $index }}</td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="img-thumbnail" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                             style="width: 60px; height: 60px; border-radius: 8px;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                </td>
                                <td>
                                    <strong class="text-success">Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @if($product->tokopedia_link)
                                        <a href="{{ $product->tokopedia_link }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-link-45deg"></i> Lihat Link
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <form method="POST" 
                                          action="{{ route('admin.pharmacy.toggle', $product->id) }}" 
                                          class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="btn btn-sm {{ $product->is_active ? 'btn-success' : 'btn-secondary' }}"
                                                onclick="return confirm('Ubah status produk {{ $product->name }}?')">
                                            @if($product->is_active)
                                                <i class="bi bi-eye"></i> Aktif
                                            @else
                                                <i class="bi bi-eye-slash"></i> Nonaktif
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $product->created_at->format('d M Y') }}<br>
                                        {{ $product->created_at->format('H:i') }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.pharmacy.edit', $product->id) }}" 
                                           class="btn btn-outline-primary" 
                                           title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form method="POST" 
                                              action="{{ route('admin.pharmacy.destroy', $product->id) }}" 
                                              onsubmit="return confirm('Yakin hapus produk {{ $product->name }}? Tindakan ini tidak bisa dibatalkan!')" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    <p>Belum ada produk. <a href="{{ route('admin.pharmacy.create') }}">Tambah produk pertama</a></p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($products->hasPages())
            <div class="p-3 border-top">
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </div>

</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
/* Stat Cards (reuse dari dashboard) */
.stat-card {
    background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark, #0056b3) 100%);
    color: white;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.stat-card.bg-primary { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); }
.stat-card.bg-success { background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); }
.stat-card.bg-secondary { background: linear-gradient(135deg, #6c757d 0%, #545b62 100%); }
.stat-card.bg-info { background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%); }

.stat-icon {
    font-size: 2.5rem;
    opacity: 0.9;
}

.stat-content h3 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
}

.stat-content p {
    margin: 5px 0 0 0;
    font-size: 0.85rem;
    opacity: 0.9;
}
</style>
@endsection