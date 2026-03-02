@extends('layouts.app')
@section('title', 'Kelola Promosi')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
:root {
  --brown:      #8B4513;
  --brown-dark: #6F3609;
  --brown-lt:   #C4813A;
  --cream:      #FDF6EE;
  --border:     #E8D5C0;
}
.page-header {
  display: flex; align-items: center; justify-content: space-between;
  flex-wrap: wrap; gap: 12px; margin-bottom: 28px;
}
.page-title { font-size: 1.5rem; font-weight: 800; color: #2C1810; margin: 0; }
.page-sub   { font-size: .85rem; color: #7A6655; margin: 4px 0 0; }

.btn-brown {
  background: var(--brown); color: #fff; border: none;
  padding: 10px 22px; border-radius: 8px; font-weight: 600;
  font-size: .88rem; text-decoration: none; display: inline-flex;
  align-items: center; gap: 6px; transition: background .2s, transform .2s; cursor: pointer;
}
.btn-brown:hover { background: var(--brown-dark); color: #fff; transform: translateY(-1px); }

.promo-table-wrap {
  background: #fff; border: 1px solid var(--border);
  border-radius: 14px; overflow: hidden;
  box-shadow: 0 2px 12px rgba(139,69,19,.07);
}
.promo-table { width: 100%; border-collapse: collapse; }
.promo-table thead th {
  background: var(--cream); padding: 14px 16px;
  font-size: .76rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .06em; color: var(--brown);
  border-bottom: 1px solid var(--border); white-space: nowrap;
}
.promo-table tbody td {
  padding: 14px 16px; font-size: .87rem; color: #3D2B1F;
  border-bottom: 1px solid #f0e4d4; vertical-align: middle;
}
.promo-table tbody tr:last-child td { border-bottom: none; }
.promo-table tbody tr:hover td { background: #fdf8f3; }

.promo-thumb {
  width: 72px; height: 56px; border-radius: 8px;
  object-fit: cover; border: 1px solid var(--border); display: block;
}
.promo-thumb-placeholder {
  width: 72px; height: 56px; border-radius: 8px;
  background: var(--cream); border: 1px dashed var(--border);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.3rem; color: #ccc;
}

.badge-aktif    { background: #dcfce7; color: #15803d; padding: 4px 12px; border-radius: 20px; font-size: .73rem; font-weight: 700; white-space:nowrap; }
.badge-nonaktif { background: #fee2e2; color: #b91c1c; padding: 4px 12px; border-radius: 20px; font-size: .73rem; font-weight: 700; white-space:nowrap; }
.badge-expired  { background: #f3f4f6; color: #6b7280; padding: 4px 12px; border-radius: 20px; font-size: .73rem; font-weight: 700; white-space:nowrap; }

.harga-promo { font-size: .98rem; font-weight: 800; color: var(--brown); }
.harga-asli  { font-size: .76rem; color: #999; text-decoration: line-through; display: block; }

.action-btns { display: flex; gap: 6px; }
.btn-icon {
  width: 34px; height: 34px; border-radius: 8px; border: none;
  display: inline-flex; align-items: center; justify-content: center;
  font-size: .92rem; cursor: pointer; transition: all .2s; text-decoration: none;
}
.btn-icon.edit        { background: #eff6ff; color: #2563eb; }
.btn-icon.edit:hover  { background: #2563eb; color: #fff; }
.btn-icon.toggle      { background: #f0fdf4; color: #16a34a; }
.btn-icon.toggle.off  { background: #fef9c3; color: #ca8a04; }
.btn-icon.toggle:hover     { background: #16a34a; color: #fff; }
.btn-icon.toggle.off:hover { background: #ca8a04; color: #fff; }
.btn-icon.del         { background: #fef2f2; color: #dc2626; }
.btn-icon.del:hover   { background: #dc2626; color: #fff; }

.empty-state { text-align: center; padding: 60px 20px; color: #9ca3af; }
.empty-state .icon { font-size: 3rem; margin-bottom: 12px; }

/* ── Confirm Modal ── */
.confirm-modal-content {
  border: none; border-radius: 20px;
  box-shadow: 0 20px 60px rgba(0,0,0,.18); overflow: hidden;
}
.confirm-icon-wrap {
  width: 68px; height: 68px; border-radius: 50%;
  display: inline-flex; align-items: center; justify-content: center; font-size: 2rem;
}
.confirm-icon-wrap.type-danger  { background: #FEE2E2; color: #DC2626; }
.confirm-icon-wrap.type-warning { background: #FEF3C7; color: #D97706; }
.confirm-title { font-size: 1.1rem; font-weight: 700; color: #1C1612; }
.confirm-msg   { font-size: .92rem; line-height: 1.6; color: #6B5E52; }
.confirm-btn-cancel {
  background: #f3f4f6; color: #374151; border: none;
  padding: 10px 28px; border-radius: 8px; font-weight: 600;
  transition: background .2s;
}
.confirm-btn-cancel:hover { background: #e5e7eb; color: #374151; }
.confirm-btn-ok {
  padding: 10px 28px; border-radius: 8px; font-weight: 600; border: none;
  transition: background .2s, transform .15s;
}
.confirm-btn-ok:hover { transform: translateY(-1px); }
.confirm-btn-ok.btn-danger  { background: #DC2626; color: #fff; }
.confirm-btn-ok.btn-danger:hover  { background: #B91C1C; }
.confirm-btn-ok.btn-warning { background: #D97706; color: #fff; }
.confirm-btn-ok.btn-warning:hover { background: #B45309; }
</style>

<div class="container-fluid py-4">

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="page-header">
    <div>
      <h1 class="page-title">🏷️ Kelola Promosi</h1>
      <p class="page-sub">Tambah, edit, dan hapus banner promosi di halaman utama</p>
    </div>
    <a href="{{ route('admin.promosi.create') }}" class="btn-brown">
      <i class="bi bi-plus-lg"></i> Tambah Promosi
    </a>
  </div>

  <div class="promo-table-wrap">
    @if($promosis->isEmpty())
      <div class="empty-state">
        <div class="icon">🏷️</div>
        <p class="mb-0">Belum ada promosi. <a href="{{ route('admin.promosi.create') }}">Tambah sekarang</a></p>
      </div>
    @else
      <table class="promo-table">
        <thead>
          <tr>
            <th>Gambar</th>
            <th>Judul</th>
            <th>Harga</th>
            <th>Berlaku Hingga</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($promosis as $p)
          <tr>
            <td>
              @if($p->gambar)
                <img src="{{ $p->gambarUrl() }}" alt="Gambar" class="promo-thumb">
              @else
                <div class="promo-thumb-placeholder" title="Tidak ada gambar">
                  <i class="bi bi-image"></i>
                </div>
              @endif
            </td>
            <td>
              <div style="font-weight:700;color:#2C1810;">{{ $p->judul }}</div>
              @if($p->subjudul)
                <div style="font-size:.78rem;color:#7A6655;margin-top:2px;">{{ $p->subjudul }}</div>
              @endif
            </td>
            <td>
              @if($p->harga_asli)
                <span class="harga-asli">{{ $p->hargaAsliFormatted() }}</span>
              @endif
              <span class="harga-promo">{{ $p->hargaPromoFormatted() }}</span>
            </td>
            <td>
              @if($p->berlaku_hingga)
                <span style="font-size:.84rem;">{{ $p->berlaku_hingga->format('d M Y') }}</span>
              @else
                <span style="color:#9ca3af;font-size:.8rem;">—</span>
              @endif
            </td>
            <td>
              @if($p->isExpired())
                <span class="badge-expired">Kadaluarsa</span>
              @elseif($p->is_active)
                <span class="badge-aktif">Aktif</span>
              @else
                <span class="badge-nonaktif">Nonaktif</span>
              @endif
            </td>
            <td>
              <div class="action-btns">

                {{-- Edit --}}
                <a href="{{ route('admin.promosi.edit', $p) }}" class="btn-icon edit" title="Edit">
                  <i class="bi bi-pencil"></i>
                </a>

                {{-- Toggle aktif/nonaktif — modal konfirmasi --}}
                <form method="POST" action="{{ route('admin.promosi.toggle', $p) }}"
                      class="confirm-form"
                      data-type="warning"
                      data-title="{{ $p->is_active ? 'Nonaktifkan Promosi' : 'Aktifkan Promosi' }}"
                      data-msg="{{ $p->is_active
                        ? 'Promosi <strong>' . e($p->judul) . '</strong> akan disembunyikan dari halaman utama.'
                        : 'Promosi <strong>' . e($p->judul) . '</strong> akan ditampilkan kembali di halaman utama.' }}"
                      data-ok="{{ $p->is_active ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan' }}">
                  @csrf @method('PATCH')
                  <button type="button"
                          class="btn-icon toggle {{ $p->is_active ? '' : 'off' }} btn-confirm-trigger"
                          title="{{ $p->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                    <i class="bi bi-{{ $p->is_active ? 'eye' : 'eye-slash' }}"></i>
                  </button>
                </form>

                {{-- Hapus — modal konfirmasi --}}
                <form method="POST" action="{{ route('admin.promosi.destroy', $p) }}"
                      class="confirm-form"
                      data-type="danger"
                      data-title="Hapus Promosi"
                      data-msg="Yakin ingin menghapus promosi <strong>{{ e($p->judul) }}</strong>?<br><span style='font-size:.83rem;color:#9ca3af;'>Gambar juga akan ikut terhapus permanen.</span>"
                      data-ok="Ya, Hapus">
                  @csrf @method('DELETE')
                  <button type="button" class="btn-icon del btn-confirm-trigger" title="Hapus">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>

              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>

</div>

{{-- ═══════════════════════════════════════
     MODAL KONFIRMASI — reusable
═══════════════════════════════════════ --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered" style="max-width:400px">
    <div class="modal-content confirm-modal-content">
      <div class="modal-body text-center px-4 pt-4 pb-2">
        <div class="confirm-icon-wrap" id="confirmIconWrap">
          <i id="confirmIcon"></i>
        </div>
        <h5 class="confirm-title mt-3 mb-2" id="confirmTitle">Konfirmasi</h5>
        <p class="confirm-msg mb-0" id="confirmMsg"></p>
      </div>
      <div class="modal-footer justify-content-center border-0 pb-4 gap-2">
        <button type="button" class="confirm-btn-cancel" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="confirm-btn-ok btn-danger" id="confirmOkBtn">Ya, Lanjutkan</button>
      </div>
    </div>
  </div>
</div>

<script>
(function () {
    var confirmModalEl  = document.getElementById('confirmModal');
    var confirmModal    = new bootstrap.Modal(confirmModalEl);
    var confirmIconWrap = document.getElementById('confirmIconWrap');
    var confirmIcon     = document.getElementById('confirmIcon');
    var confirmTitle    = document.getElementById('confirmTitle');
    var confirmMsg      = document.getElementById('confirmMsg');
    var confirmOkBtn    = document.getElementById('confirmOkBtn');
    var pendingForm     = null;

    var typeConfig = {
        danger:  { iconClass: 'bi bi-trash-fill',                wrapClass: 'type-danger',  btnClass: 'btn-danger'  },
        warning: { iconClass: 'bi bi-exclamation-triangle-fill', wrapClass: 'type-warning', btnClass: 'btn-warning' },
    };

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.btn-confirm-trigger');
        if (!btn) return;
        var form = btn.closest('.confirm-form');
        if (!form) return;

        var type  = form.dataset.type  || 'danger';
        var cfg   = typeConfig[type]   || typeConfig.danger;

        confirmIconWrap.className  = 'confirm-icon-wrap ' + cfg.wrapClass;
        confirmIcon.className      = cfg.iconClass;
        confirmTitle.textContent   = form.dataset.title || 'Konfirmasi';
        confirmMsg.innerHTML       = form.dataset.msg   || 'Lanjutkan aksi ini?';

        confirmOkBtn.className     = 'confirm-btn-ok ' + cfg.btnClass;
        confirmOkBtn.textContent   = form.dataset.ok   || 'Ya, Lanjutkan';

        pendingForm = form;
        confirmModal.show();
    });

    confirmOkBtn.addEventListener('click', function () {
        if (!pendingForm) return;
        var formToSubmit = pendingForm;
        pendingForm = null;
        confirmModal.hide();
        // Submit setelah animasi modal selesai
        confirmModalEl.addEventListener('hidden.bs.modal', function handler() {
            confirmModalEl.removeEventListener('hidden.bs.modal', handler);
            formToSubmit.submit();
        });
    });

    confirmModalEl.addEventListener('hidden.bs.modal', function () {
        pendingForm = null;
    });
})();
</script>

@endsection