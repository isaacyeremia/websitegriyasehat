@extends('layouts.app')

@section('title', 'Kelola Produk Apotek')

@section('content')

<style>
.stat-card {
    color: white;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
.stat-card.bg-primary   { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); }
.stat-card.bg-success   { background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); }
.stat-card.bg-secondary { background: linear-gradient(135deg, #6c757d 0%, #545b62 100%); }
.stat-card.bg-info      { background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%); }
.stat-icon { font-size: 2.5rem; opacity: 0.9; }
.stat-content h3 { font-size: 2rem; font-weight: 700; margin: 0; }
.stat-content p  { margin: 5px 0 0 0; font-size: 0.85rem; opacity: 0.9; }

/* Pagination */
.pagination-wrapper { font-size: 0.82rem; }
.pagination-wrapper nav { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; }
.pagination-wrapper svg { width: 14px !important; height: 14px !important; }
.pagination-wrapper span[aria-current] > span,
.pagination-wrapper a {
    padding: 4px 10px !important;
    font-size: 0.82rem !important;
    min-width: auto !important;
    border-radius: 6px !important;
    display: inline-flex !important;
    align-items: center !important;
}
</style>

<div class="container-fluid py-4">

    {{-- Header --}}
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

    {{-- Flash Messages --}}
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
                <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
                <div class="stat-content"><h3>{{ $products->total() }}</h3><p>Total Produk</p></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-success">
                <div class="stat-icon"><i class="bi bi-eye"></i></div>
                <div class="stat-content"><h3>{{ $products->where('is_active', true)->count() }}</h3><p>Produk Aktif</p></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-secondary">
                <div class="stat-icon"><i class="bi bi-eye-slash"></i></div>
                <div class="stat-content"><h3>{{ $products->where('is_active', false)->count() }}</h3><p>Produk Nonaktif</p></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-info">
                <div class="stat-icon"><i class="bi bi-link-45deg"></i></div>
                <div class="stat-content"><h3>{{ $products->whereNotNull('tokopedia_link')->count() }}</h3><p>Dengan Link</p></div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.pharmacy.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold"><i class="bi bi-search"></i> Cari Produk</label>
                    <input type="text" name="search" class="form-control"
                           value="{{ request('search') }}" placeholder="Nama produk...">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold"><i class="bi bi-tag"></i> Kategori</label>
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold"><i class="bi bi-funnel"></i> Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold"><i class="bi bi-sort-down"></i> Urutkan</label>
                    <select name="sort" class="form-select">
                        <option value="newest"     {{ request('sort') == 'newest'     ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest"     {{ request('sort') == 'oldest'     ? 'selected' : '' }}>Terlama</option>
                        <option value="name_asc"   {{ request('sort') == 'name_asc'   ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="name_desc"  {{ request('sort') == 'name_desc'  ? 'selected' : '' }}>Nama Z-A</option>
                        <option value="price_low"  {{ request('sort') == 'price_low'  ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Filter</button>
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
                            <th class="px-3" width="4%">No</th>
                            <th width="8%">Gambar</th>
                            <th width="22%">Nama Produk</th>
                            <th width="10%">Kategori</th>
                            <th width="12%">Harga</th>
                            <th width="14%">Link Tokopedia</th>
                            <th width="8%">Status</th>
                            <th width="10%">Dibuat</th>
                            <th class="text-center" width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $index => $product)
                            <tr>
                                <td class="px-3">{{ $products->firstItem() + $index }}</td>

                                {{-- Gambar --}}
                                <td>
                                    @if($product->image)
                                        {{-- image di DB sudah path lengkap: images/pharmacy-products/xxx.jpg --}}
                                        <img src="{{ asset($product->image) }}"
                                             alt="{{ $product->name }}"
                                             class="img-thumbnail"
                                             style="width:60px;height:60px;object-fit:cover;flex-shrink:0;"
                                             onerror="this.style.display='none'">
                                    @else
                                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center"
                                             style="width:60px;height:60px;border-radius:8px;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </td>

                                {{-- Nama + Deskripsi --}}
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    @if($product->description)
                                        <br><small class="text-muted">{{ Str::limit($product->description, 60) }}</small>
                                    @endif
                                </td>

                                {{-- Kategori --}}
                                <td>
                                    @php
                                        $categoryColors = [
                                            'minyak'   => 'warning',
                                            'herbal'   => 'success',
                                            'suplemen' => 'info',
                                            'sirup'    => 'primary',
                                        ];
                                        $color = $categoryColors[$product->category] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }}">
                                        {{ $categories[$product->category] ?? $product->category }}
                                    </span>
                                </td>

                                {{-- Harga --}}
                                <td>
                                    <strong class="text-success">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </strong>
                                </td>

                                {{-- Link --}}
                                <td>
                                    @if($product->tokopedia_link)
                                        <a href="{{ $product->tokopedia_link }}" target="_blank"
                                           class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-link-45deg"></i> Lihat
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Status Toggle --}}
                                <td>
                                    <form method="POST"
                                          action="{{ route('admin.pharmacy.toggle', $product->id) }}"
                                          class="d-inline confirm-form"
                                          data-type="{{ $product->is_active ? 'warning' : 'success' }}"
                                          data-title="Ubah Status Produk"
                                          data-msg="Ubah status <strong>{{ $product->name }}</strong> menjadi <strong>{{ $product->is_active ? 'Nonaktif' : 'Aktif' }}</strong>?">
                                        @csrf @method('PATCH')
                                        <button type="button"
                                                class="btn btn-sm btn-confirm-trigger {{ $product->is_active ? 'btn-success' : 'btn-secondary' }}">
                                            @if($product->is_active)
                                                <i class="bi bi-eye"></i> Aktif
                                            @else
                                                <i class="bi bi-eye-slash"></i> Nonaktif
                                            @endif
                                        </button>
                                    </form>
                                </td>

                                {{-- Tanggal --}}
                                <td>
                                    <small class="text-muted">
                                        {{ $product->created_at->format('d M Y') }}<br>
                                        {{ $product->created_at->format('H:i') }}
                                    </small>
                                </td>

                                {{-- Aksi --}}
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.pharmacy.edit', $product->id) }}"
                                           class="btn btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form method="POST"
                                              action="{{ route('admin.pharmacy.destroy', $product->id) }}"
                                              class="d-inline confirm-form"
                                              data-type="danger"
                                              data-title="Hapus Produk"
                                              data-msg="Yakin ingin menghapus produk <strong>{{ $product->name }}</strong>? Tindakan ini tidak dapat dibatalkan.">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-outline-danger btn-confirm-trigger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    <p>Belum ada produk. <a href="{{ route('admin.pharmacy.create') }}">Tambah produk pertama</a></p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
                <div class="p-3 border-top pagination-wrapper">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal Konfirmasi --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered" style="max-width:400px">
    <div class="modal-content" style="border-radius:20px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.18);">
      <div class="modal-body text-center px-4 pt-4 pb-2">
        <div id="confirmIconWrap"
             class="mx-auto mb-3 d-inline-flex align-items-center justify-content-center rounded-circle"
             style="width:68px;height:68px;font-size:2rem;">
          <i id="confirmIcon"></i>
        </div>
        <h5 class="fw-bold mb-2" id="confirmTitle">Konfirmasi</h5>
        <p class="mb-0" style="font-size:.92rem;color:#6B5E52;line-height:1.6" id="confirmMsg"></p>
      </div>
      <div class="modal-footer justify-content-center border-0 pb-4 gap-2">
        <button type="button" data-bs-dismiss="modal"
                style="background:#f3f4f6;color:#374151;border:none;padding:10px 28px;border-radius:8px;font-weight:600;">
          Batal
        </button>
        <button type="button" id="confirmOkBtn"
                style="padding:10px 28px;border-radius:8px;font-weight:600;border:none;">
          Lanjutkan
        </button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var confirmModalEl  = document.getElementById('confirmModal');
    var confirmOkBtn    = document.getElementById('confirmOkBtn');
    var confirmIconWrap = document.getElementById('confirmIconWrap');
    var confirmIcon     = document.getElementById('confirmIcon');
    var confirmTitle    = document.getElementById('confirmTitle');
    var confirmMsg      = document.getElementById('confirmMsg');
    var pendingForm     = null;

    var typeConfig = {
        danger: {
            bg       : '#FEE2E2',
            color    : '#DC2626',
            iconClass: 'bi bi-trash-fill',
            btnBg    : '#DC2626',
            btnColor : '#fff',
            okLabel  : 'Ya, Hapus',
        },
        warning: {
            bg       : '#FEF9C3',
            color    : '#CA8A04',
            iconClass: 'bi bi-exclamation-triangle-fill',
            btnBg    : '#CA8A04',
            btnColor : '#fff',
            okLabel  : 'Ya, Ubah',
        },
        success: {
            bg       : '#DCFCE7',
            color    : '#16A34A',
            iconClass: 'bi bi-check-circle-fill',
            btnBg    : '#16A34A',
            btnColor : '#fff',
            okLabel  : 'Ya, Aktifkan',
        },
    };

    function getModal() {
        return bootstrap.Modal.getOrCreateInstance(confirmModalEl);
    }

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.btn-confirm-trigger');
        if (!btn) return;
        var form = btn.closest('.confirm-form');
        if (!form) return;

        var type = form.dataset.type || 'danger';
        var cfg  = typeConfig[type]  || typeConfig.danger;

        confirmIconWrap.style.background = cfg.bg;
        confirmIconWrap.style.color      = cfg.color;
        confirmIcon.className            = cfg.iconClass;
        confirmTitle.textContent         = form.dataset.title || 'Konfirmasi';
        confirmMsg.innerHTML             = form.dataset.msg   || 'Lanjutkan aksi ini?';
        confirmOkBtn.style.background    = cfg.btnBg;
        confirmOkBtn.style.color         = cfg.btnColor;
        confirmOkBtn.textContent         = cfg.okLabel;

        pendingForm = form;
        getModal().show();
    });

    confirmOkBtn.addEventListener('click', function () {
        if (!pendingForm) return;
        var formToSubmit = pendingForm;
        pendingForm = null;
        getModal().hide();
        confirmModalEl.addEventListener('hidden.bs.modal', function handler() {
            confirmModalEl.removeEventListener('hidden.bs.modal', handler);
            formToSubmit.submit();
        });
    });

    confirmModalEl.addEventListener('hidden.bs.modal', function () {
        pendingForm = null;
    });
});
</script>
@endsection