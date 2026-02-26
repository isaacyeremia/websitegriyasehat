@extends('layouts.app')

@section('title', 'Kelola Tenaga Medis')

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
.stat-card.bg-primary  { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); }
.stat-card.bg-success  { background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); }
.stat-card.bg-info     { background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%); }
.stat-card.bg-secondary{ background: linear-gradient(135deg, #6c757d 0%, #545b62 100%); }
.stat-icon { font-size: 2.5rem; opacity: 0.9; }
.stat-content h3 { font-size: 2rem; font-weight: 700; margin: 0; }
.stat-content p  { margin: 5px 0 0 0; font-size: 0.85rem; opacity: 0.9; }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">👨‍⚕️ Kelola Tenaga Medis</h2>
            <p class="text-muted mb-0">Manajemen data dokter & terapis</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Dashboard
            </a>
            <a href="{{ route('admin.terapis.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Tambah Dokter
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

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card bg-primary">
                <div class="stat-icon"><i class="bi bi-people"></i></div>
                <div class="stat-content"><h3>{{ $terapis->total() }}</h3><p>Total Dokter</p></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-success">
                <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
                <div class="stat-content"><h3>{{ $terapis->where('is_active', true)->count() }}</h3><p>Aktif</p></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-info">
                <div class="stat-icon"><i class="bi bi-eye"></i></div>
                <div class="stat-content"><h3>{{ $terapis->where('show_in_about', true)->count() }}</h3><p>Tampil di About</p></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-secondary">
                <div class="stat-icon"><i class="bi bi-x-circle"></i></div>
                <div class="stat-content"><h3>{{ $terapis->where('is_active', false)->count() }}</h3><p>Nonaktif</p></div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.terapis.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-bold"><i class="bi bi-search"></i> Cari Dokter</label>
                    <input type="text" name="search" class="form-control"
                           value="{{ request('search') }}" placeholder="Nama atau jadwal...">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold"><i class="bi bi-funnel"></i> Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold"><i class="bi bi-eye"></i> About</label>
                    <select name="show_about" class="form-select">
                        <option value="">Semua</option>
                        <option value="yes" {{ request('show_about') == 'yes' ? 'selected' : '' }}>Ya</option>
                        <option value="no"  {{ request('show_about') == 'no'  ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Filter</button>
                    <a href="{{ route('admin.terapis.index') }}" class="btn btn-secondary w-100 mt-2">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-list-ul me-2"></i>Daftar Dokter/Terapis
                <span class="badge bg-primary">{{ $terapis->total() }}</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3" width="5%">No</th>
                            <th width="8%">Foto</th>
                            <th width="18%">Nama</th>
                            <th width="15%">Jadwal</th>
                            <th width="12%">Daftar Harga</th>
                            <th width="8%">Urutan</th>
                            <th width="10%">Status</th>
                            <th width="10%">About</th>
                            <th class="text-center" width="14%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($terapis as $index => $t)
                            <tr>
                                <td class="px-3">{{ $terapis->firstItem() + $index }}</td>
                                <td>
                                    @if($t->image)
                                        <img src="{{ '/images/tenaga-medis/' . $t->image }}"
                                             alt="{{ $t->name }}"
                                             class="rounded-circle"
                                             style="width:50px;height:50px;object-fit:cover;">
                                    @else
                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
                                             style="width:50px;height:50px;">
                                            <i class="bi bi-person"></i>
                                        </div>
                                    @endif
                                </td>
                                <td><strong>{{ $t->name }}</strong></td>
                                <td><small>{{ $t->schedule }}</small></td>
                                <td>
                                    @if($t->daftar_harga && count($t->daftar_harga) > 0)
                                        <button type="button" class="btn btn-sm btn-outline-info"
                                                data-bs-toggle="modal"
                                                data-bs-target="#hargaModal{{ $t->id }}">
                                            <i class="bi bi-list"></i> Lihat ({{ count($t->daftar_harga) }})
                                        </button>

                                        <div class="modal fade" id="hargaModal{{ $t->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-info text-white">
                                                        <h5 class="modal-title">Daftar Harga — {{ $t->name }}</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <ul class="list-group list-group-flush">
                                                            @foreach($t->daftar_harga as $harga)
                                                                <li class="list-group-item">{{ $harga }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td><span class="badge bg-dark">{{ $t->urutan }}</span></td>
                                <td>
                                    <form method="POST" action="{{ route('admin.terapis.toggle', $t->id) }}"
                                          class="d-inline confirm-form"
                                          data-type="{{ $t->is_active ? 'warning' : 'success' }}"
                                          data-title="Ubah Status Dokter"
                                          data-msg="Ubah status <strong>{{ $t->name }}</strong> menjadi <strong>{{ $t->is_active ? 'Nonaktif' : 'Aktif' }}</strong>?">
                                        @csrf @method('PATCH')
                                        <button type="button" class="btn btn-sm btn-confirm-trigger {{ $t->is_active ? 'btn-success' : 'btn-secondary' }}">
                                            @if($t->is_active)
                                                <i class="bi bi-check-circle"></i> Aktif
                                            @else
                                                <i class="bi bi-x-circle"></i> Nonaktif
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.terapis.toggle-about', $t->id) }}"
                                          class="d-inline confirm-form"
                                          data-type="{{ $t->show_in_about ? 'warning' : 'success' }}"
                                          data-title="Ubah Tampilan di About"
                                          data-msg="<strong>{{ $t->name }}</strong> akan {{ $t->show_in_about ? 'disembunyikan dari' : 'ditampilkan di' }} halaman About?">
                                        @csrf @method('PATCH')
                                        <button type="button" class="btn btn-sm btn-confirm-trigger {{ $t->show_in_about ? 'btn-info' : 'btn-outline-secondary' }}">
                                            @if($t->show_in_about)
                                                <i class="bi bi-eye"></i> Ya
                                            @else
                                                <i class="bi bi-eye-slash"></i> Tidak
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.terapis.edit', $t->id) }}"
                                           class="btn btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        {{-- Hapus: pakai modal konfirmasi --}}
                                        <form method="POST"
                                              action="{{ route('admin.terapis.destroy', $t->id) }}"
                                              class="d-inline confirm-form"
                                              data-type="danger"
                                              data-title="Hapus Dokter/Terapis"
                                              data-msg="Yakin ingin menghapus <strong>{{ $t->name }}</strong>?<br><small class='text-muted'>Data jadwal dan riwayat pasien akan ikut terhapus.</small>">
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
                                    <p>Belum ada dokter. <a href="{{ route('admin.terapis.create') }}">Tambah dokter pertama</a></p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($terapis->hasPages())
                <div class="p-3 border-top">
                    {{ $terapis->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     MODAL KONFIRMASI — reusable
═══════════════════════════════════════════ --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered" style="max-width:400px">
    <div class="modal-content" style="border-radius:20px; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,.18);">
      <div class="modal-body text-center px-4 pt-4 pb-2">
        <div id="confirmIconWrap"
             class="mx-auto mb-3 d-inline-flex align-items-center justify-content-center rounded-circle"
             style="width:68px;height:68px;font-size:2rem;background:#FEE2E2;color:#DC2626;">
          <i class="bi bi-trash-fill" id="confirmIcon"></i>
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
                style="padding:10px 28px;border-radius:8px;font-weight:600;border:none;background:#DC2626;color:#fff;">
          Lanjutkan
        </button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var confirmModalEl = document.getElementById('confirmModal');
    var confirmOkBtn    = document.getElementById('confirmOkBtn');
    var confirmIconWrap = document.getElementById('confirmIconWrap');
    var confirmIcon     = document.getElementById('confirmIcon');
    var confirmTitle    = document.getElementById('confirmTitle');
    var confirmMsg      = document.getElementById('confirmMsg');
    var pendingForm     = null;

    var typeConfig = {
        danger: {
            bg: '#FEE2E2', color: '#DC2626',
            iconClass: 'bi bi-trash-fill',
            btnBg: '#DC2626', btnColor: '#fff', okLabel: 'Ya, Hapus',
        },
        warning: {
            bg: '#FEF9C3', color: '#CA8A04',
            iconClass: 'bi bi-exclamation-triangle-fill',
            btnBg: '#CA8A04', btnColor: '#fff', okLabel: 'Ya, Ubah',
        },
        success: {
            bg: '#DCFCE7', color: '#16A34A',
            iconClass: 'bi bi-check-circle-fill',
            btnBg: '#16A34A', btnColor: '#fff', okLabel: 'Ya, Aktifkan',
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

        if (confirmIconWrap) {
            confirmIconWrap.style.background = cfg.bg;
            confirmIconWrap.style.color      = cfg.color;
        }
        if (confirmIcon) confirmIcon.className = cfg.iconClass;
        confirmTitle.textContent      = form.dataset.title || 'Konfirmasi';
        confirmMsg.innerHTML          = form.dataset.msg   || 'Lanjutkan aksi ini?';
        confirmOkBtn.style.background = cfg.btnBg;
        confirmOkBtn.style.color      = cfg.btnColor;
        confirmOkBtn.textContent      = cfg.okLabel;

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