@extends('layouts.app')
@section('title', $promosi ? 'Edit Promosi' : 'Tambah Promosi')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
:root {
  --brown:      #8B4513;
  --brown-dark: #6F3609;
  --cream:      #FDF6EE;
  --border:     #E8D5C0;
}
.form-wrap {
  max-width: 820px; margin: 0 auto;
  background: #fff; border: 1px solid var(--border);
  border-radius: 16px; padding: 36px 40px;
  box-shadow: 0 4px 20px rgba(139,69,19,.08);
}
.form-section-title {
  font-size: .72rem; font-weight: 700; letter-spacing: .1em;
  text-transform: uppercase; color: var(--brown);
  border-bottom: 1px solid var(--border);
  padding-bottom: 8px; margin: 28px 0 18px;
}
.form-section-title:first-child { margin-top: 0; }
label { font-size: .85rem; font-weight: 600; color: #3D2B1F; margin-bottom: 5px; display: block; }
.form-control, .form-select {
  border: 1px solid var(--border); border-radius: 8px;
  padding: 10px 14px; font-size: .9rem;
  transition: border-color .2s, box-shadow .2s; width: 100%;
}
.form-control:focus, .form-select:focus {
  border-color: var(--brown);
  box-shadow: 0 0 0 3px rgba(139,69,19,.12); outline: none;
}
.hint { font-size: .77rem; color: #9ca3af; margin-top: 4px; }

.upload-area {
  border: 2px dashed var(--border);
  border-radius: 12px; padding: 28px 20px;
  text-align: center; cursor: pointer;
  transition: border-color .25s, background .25s;
  position: relative; background: var(--cream);
}
.upload-area:hover, .upload-area.drag-over {
  border-color: var(--brown); background: rgba(139,69,19,.04);
}
.upload-area input[type="file"] {
  position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
}
.upload-icon { font-size: 2.2rem; color: var(--brown); margin-bottom: 10px; }
.upload-label { font-size: .88rem; color: #555; line-height: 1.5; }
.upload-label strong { color: var(--brown); }
.upload-types { font-size: .75rem; color: #9ca3af; margin-top: 6px; }

.img-preview-wrap {
  position: relative; display: inline-block;
  border-radius: 10px; overflow: hidden;
  box-shadow: 0 4px 12px rgba(0,0,0,.1); max-width: 100%;
}
.img-preview-wrap img { max-height: 220px; max-width: 100%; display: block; object-fit: contain; }
.img-preview-remove {
  position: absolute; top: 8px; right: 8px;
  background: rgba(220,38,38,.85); color: #fff;
  border: none; border-radius: 50%; width: 28px; height: 28px;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; font-size: .8rem; transition: background .2s;
}
.img-preview-remove:hover { background: #b91c1c; }

.existing-img-wrap {
  position: relative; display: inline-block;
  border-radius: 10px; overflow: hidden;
  box-shadow: 0 4px 12px rgba(0,0,0,.1);
}
.existing-img-wrap img { max-height: 200px; display: block; }
.existing-badge {
  position: absolute; bottom: 0; left: 0; right: 0;
  background: rgba(0,0,0,.55); color: #fff;
  font-size: .72rem; text-align: center; padding: 5px;
}

.btn-brown {
  background: var(--brown); color: #fff; border: none;
  padding: 11px 28px; border-radius: 8px; font-weight: 600;
  font-size: .9rem; display: inline-flex; align-items: center;
  gap: 7px; transition: background .2s, transform .2s; cursor: pointer;
}
.btn-brown:hover { background: var(--brown-dark); color: #fff; transform: translateY(-1px); }
.btn-cancel {
  background: #f3f4f6; color: #374151; border: none;
  padding: 11px 24px; border-radius: 8px; font-weight: 600;
  font-size: .9rem; text-decoration: none; display: inline-flex;
  align-items: center; gap: 7px; transition: background .2s;
}
.btn-cancel:hover { background: #e5e7eb; color: #374151; }

.harga-preview      { font-size: 1.5rem; font-weight: 900; color: var(--brown); display: block; margin-top: 6px; }
.harga-asli-preview { font-size: .88rem; color: #9ca3af; text-decoration: line-through; display: block; }

/* ── Confirm Modal (hapus gambar) ── */
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
  padding: 10px 28px; border-radius: 8px; font-weight: 600; transition: background .2s;
}
.confirm-btn-cancel:hover { background: #e5e7eb; color: #374151; }
.confirm-btn-ok {
  padding: 10px 28px; border-radius: 8px; font-weight: 600;
  border: none; transition: background .2s, transform .15s;
}
.confirm-btn-ok:hover { transform: translateY(-1px); }
.confirm-btn-ok.btn-danger { background: #DC2626; color: #fff; }
.confirm-btn-ok.btn-danger:hover { background: #B91C1C; }
</style>

<div class="container-fluid py-4">
  <div class="mb-4">
    <a href="{{ route('admin.promosi.index') }}" style="color:var(--brown);font-weight:600;text-decoration:none;font-size:.88rem;">
      <i class="bi bi-arrow-left me-1"></i>Kembali ke daftar
    </a>
  </div>

  <div class="form-wrap">
    <h2 style="font-size:1.4rem;font-weight:800;color:#2C1810;margin:0 0 4px;">
      {{ $promosi ? '✏️ Edit Promosi' : '➕ Tambah Promosi Baru' }}
    </h2>
    <p style="color:#7A6655;font-size:.85rem;margin:0 0 8px;">
      {{ $promosi ? 'Perbarui data promosi yang sudah ada.' : 'Buat promosi baru untuk ditampilkan di halaman utama.' }}
    </p>

    @if($errors->any())
      <div class="alert alert-danger mt-3">
        <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <form method="POST"
      action="{{ $promosi ? route('admin.promosi.update', $promosi) : route('admin.promosi.store') }}"
      enctype="multipart/form-data"
      id="promoForm">
      @csrf
      @if($promosi) @method('PUT') @endif

      <input type="hidden" name="cta_label" value="Booking Sekarang">
      <input type="hidden" name="cta_url" value="">

      {{-- ── INFORMASI UTAMA ── --}}
      <div class="form-section-title">Informasi Utama</div>

      <div class="mb-3">
        <label>Judul Promosi <span style="color:red">*</span></label>
        <input type="text" name="judul" class="form-control"
          value="{{ old('judul', $promosi?->judul) }}"
          placeholder="cth: Paket Ramadhan Bebas Nyeri" required>
      </div>
      <div class="mb-3">
        <label>Sub-judul</label>
        <input type="text" name="subjudul" class="form-control"
          value="{{ old('subjudul', $promosi?->subjudul) }}"
          placeholder="cth: Redakan nyeri, Raih Ibadah Maksimal">
      </div>
      <div class="mb-3">
        <label>Deskripsi Singkat</label>
        <textarea name="deskripsi" class="form-control" rows="3"
          placeholder="Deskripsi singkat...">{{ old('deskripsi', $promosi?->deskripsi) }}</textarea>
      </div>

      {{-- ── GAMBAR PROMOSI ── --}}
      <div class="form-section-title">Gambar / Poster Promosi</div>

      <div class="mb-3">
        @if($promosi && $promosi->gambar)
          <div class="mb-3" id="existingImgSection">
            <p class="hint mb-2">Gambar saat ini:</p>
            <div class="existing-img-wrap">
              <img src="{{ $promosi->gambarUrl() }}" alt="Gambar Promosi">
              <span class="existing-badge">{{ $promosi->gambar }}</span>
            </div>
            <div class="form-check mt-2">
              {{-- Checkbox hapus gambar — klik trigger modal konfirmasi --}}
              <input class="form-check-input" type="checkbox" name="hapus_gambar"
                     id="hapusGambar" value="1" style="display:none;">
              <button type="button" id="btnHapusGambar"
                      style="background:none;border:none;padding:0;cursor:pointer;display:flex;align-items:center;gap:6px;margin-top:4px;">
                <span style="width:16px;height:16px;border:2px solid #dc2626;border-radius:3px;display:inline-flex;align-items:center;justify-content:center;" id="hapusCheckbox">
                  <i class="bi bi-check" id="hapusCheckIcon" style="color:#dc2626;font-size:.7rem;display:none;"></i>
                </span>
                <span style="color:#dc2626;font-size:.83rem;font-weight:600;">Hapus gambar ini</span>
              </button>
            </div>
            <p class="hint">Atau upload gambar baru di bawah untuk mengganti.</p>
          </div>
        @endif

        <div class="upload-area" id="uploadArea">
          <input type="file" name="gambar" id="inputGambar"
            accept="image/jpeg,image/jpg,image/png,image/webp,image/gif">
          <div id="uploadPlaceholder">
            <div class="upload-icon"><i class="bi bi-image-fill"></i></div>
            <div class="upload-label">
              <strong>Klik untuk pilih gambar</strong> atau drag & drop di sini
            </div>
            <div class="upload-types">JPG · JPEG · PNG · WEBP · GIF &nbsp;|&nbsp; Maks. 5 MB</div>
          </div>
          <div id="previewWrap" style="display:none;">
            <div class="img-preview-wrap">
              <img id="previewImg" src="" alt="Preview">
              <button type="button" class="img-preview-remove" id="btnRemovePreview" title="Batalkan pilihan">
                <i class="bi bi-x"></i>
              </button>
            </div>
            <p class="hint mt-2" id="previewName"></p>
          </div>
        </div>
        <p class="hint mt-2">Upload poster/gambar promosi. Gambar akan ditampilkan penuh (tidak dipotong).</p>
      </div>

      {{-- ── MANFAAT ── --}}
      <div class="form-section-title">Manfaat & Detail</div>

      <div class="mb-3">
        <label>Daftar Manfaat</label>
        <textarea name="manfaat_raw" class="form-control" rows="5"
          placeholder="Tulis satu manfaat per baris:&#10;Memperbaiki postur bungkuk / bahu maju&#10;Mengatasi nyeri leher, pundak, pinggang">{{ old('manfaat_raw', $promosi ? implode("\n", $promosi->manfaat) : '') }}</textarea>
        <p class="hint">Satu manfaat per baris. Ditampilkan sebagai daftar ✓ checklist.</p>
      </div>
      <div class="mb-3">
        <label>Definisi / Cara Kerja</label>
        <textarea name="definisi" class="form-control" rows="3"
          placeholder="Penjelasan detail tentang paket...">{{ old('definisi', $promosi?->definisi) }}</textarea>
      </div>
      <div class="mb-3">
        <label>Bonus</label>
        <input type="text" name="bonus" class="form-control"
          value="{{ old('bonus', $promosi?->bonus) }}"
          placeholder="cth: Latihan gerak yang dapat dilakukan di rumah">
      </div>

      {{-- ── HARGA ── --}}
      <div class="form-section-title">Harga</div>

      <div class="row g-3 mb-3">
        <div class="col-md-6">
          <label>Harga Asli (coret) — opsional</label>
          <div class="input-group">
            <span class="input-group-text" style="border-color:var(--border);background:var(--cream);font-size:.85rem;">Rp</span>
            <input type="number" name="harga_asli" class="form-control" id="inputHargaAsli"
              value="{{ old('harga_asli', $promosi?->harga_asli) }}"
              placeholder="275000" min="0">
          </div>
          <span class="harga-asli-preview" id="previewAsli">{{ $promosi?->hargaAsliFormatted() }}</span>
        </div>
        <div class="col-md-6">
          <label>Harga Promo <span style="color:red">*</span></label>
          <div class="input-group">
            <span class="input-group-text" style="border-color:var(--border);background:var(--cream);font-size:.85rem;">Rp</span>
            <input type="number" name="harga_promo" class="form-control" id="inputHargaPromo"
              value="{{ old('harga_promo', $promosi?->harga_promo) }}"
              placeholder="200000" min="0" required>
          </div>
          <span class="harga-preview" id="previewPromo">{{ $promosi ? $promosi->hargaPromoFormatted() : '' }}</span>
        </div>
      </div>

      {{-- ── MASA BERLAKU ── --}}
      <div class="form-section-title">Masa Berlaku & Pengaturan</div>

      <div class="row g-3 mb-3">
        <div class="col-md-5">
          <label>Berlaku Hingga — opsional</label>
          <input type="date" name="berlaku_hingga" class="form-control"
            value="{{ old('berlaku_hingga', $promosi?->berlaku_hingga?->format('Y-m-d')) }}">
        </div>
        <div class="col-md-3">
          <label>Urutan Tampil</label>
          <input type="number" name="urutan" class="form-control"
            value="{{ old('urutan', $promosi?->urutan ?? 0) }}" min="0">
          <p class="hint">0 = paling atas</p>
        </div>
        <div class="col-md-4" style="display:flex;align-items:center;padding-top:28px;">
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1"
              {{ old('is_active', $promosi?->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="isActive" style="font-size:.9rem;font-weight:600;">
              Aktifkan Promosi
            </label>
          </div>
        </div>
      </div>

      {{-- ── ACTIONS ── --}}
      <div class="d-flex gap-3 mt-4 pt-3" style="border-top:1px solid var(--border);">
        <button type="submit" class="btn-brown">
          <i class="bi bi-{{ $promosi ? 'check-lg' : 'plus-lg' }}"></i>
          {{ $promosi ? 'Simpan Perubahan' : 'Tambah Promosi' }}
        </button>
        <a href="{{ route('admin.promosi.index') }}" class="btn-cancel">
          <i class="bi bi-x-lg"></i>Batal
        </a>
      </div>

    </form>
  </div>
</div>

{{-- ═══════════════════════════════════════
     MODAL KONFIRMASI — hapus gambar
═══════════════════════════════════════ --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered" style="max-width:400px">
    <div class="modal-content confirm-modal-content">
      <div class="modal-body text-center px-4 pt-4 pb-2">
        <div class="confirm-icon-wrap type-danger">
          <i class="bi bi-image-fill"></i>
        </div>
        <h5 class="confirm-title mt-3 mb-2">Hapus Gambar?</h5>
        <p class="confirm-msg mb-0">Gambar promosi ini akan dihapus saat kamu menyimpan. Tindakan ini tidak dapat dibatalkan.</p>
      </div>
      <div class="modal-footer justify-content-center border-0 pb-4 gap-2">
        <button type="button" class="confirm-btn-cancel" id="confirmCancelHapus" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="confirm-btn-ok btn-danger" id="confirmOkHapus">Ya, Hapus Gambar</button>
      </div>
    </div>
  </div>
</div>

<script>
/* ── Harga preview ── */
function fmt(n) { return n ? 'Rp ' + parseInt(n).toLocaleString('id-ID') : ''; }
document.getElementById('inputHargaPromo').addEventListener('input', function () {
    document.getElementById('previewPromo').textContent = fmt(this.value);
});
document.getElementById('inputHargaAsli').addEventListener('input', function () {
    document.getElementById('previewAsli').textContent = fmt(this.value);
});

/* ── Image upload / preview ── */
const inputGambar       = document.getElementById('inputGambar');
const uploadArea        = document.getElementById('uploadArea');
const uploadPlaceholder = document.getElementById('uploadPlaceholder');
const previewWrap       = document.getElementById('previewWrap');
const previewImg        = document.getElementById('previewImg');
const previewName       = document.getElementById('previewName');
const btnRemove         = document.getElementById('btnRemovePreview');

if (inputGambar) {
    inputGambar.addEventListener('change', function () {
        const file = this.files[0];
        if (file) showPreview(file);
    });
}

function showPreview(file) {
    const reader = new FileReader();
    reader.onload = e => {
        previewImg.src = e.target.result;
        previewName.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
        uploadPlaceholder.style.display = 'none';
        previewWrap.style.display = 'block';
    };
    reader.readAsDataURL(file);
}

if (btnRemove) {
    btnRemove.addEventListener('click', function (e) {
        e.stopPropagation();
        inputGambar.value = '';
        previewImg.src = '';
        uploadPlaceholder.style.display = 'block';
        previewWrap.style.display = 'none';
    });
}

if (uploadArea) {
    uploadArea.addEventListener('dragover', e => { e.preventDefault(); uploadArea.classList.add('drag-over'); });
    uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('drag-over'));
    uploadArea.addEventListener('drop', function (e) {
        e.preventDefault();
        uploadArea.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            const dt = new DataTransfer();
            dt.items.add(file);
            inputGambar.files = dt.files;
            showPreview(file);
        }
    });
}

/* ── Hapus gambar — modal konfirmasi ── */
var btnHapusGambar  = document.getElementById('btnHapusGambar');
var hapusGambar     = document.getElementById('hapusGambar');
var hapusCheckIcon  = document.getElementById('hapusCheckIcon');
var confirmModalEl  = document.getElementById('confirmModal');
var confirmOkHapus  = document.getElementById('confirmOkHapus');
var confirmCancelHapus = document.getElementById('confirmCancelHapus');

if (btnHapusGambar && confirmModalEl) {
    var confirmModal = new bootstrap.Modal(confirmModalEl);
    var isChecked = false;

    btnHapusGambar.addEventListener('click', function () {
        if (isChecked) {
            // Sudah dicentang → bisa langsung uncheck tanpa konfirmasi
            isChecked = false;
            hapusGambar.checked = false;
            hapusCheckIcon.style.display = 'none';
        } else {
            // Belum dicentang → tampilkan konfirmasi dulu
            confirmModal.show();
        }
    });

    confirmOkHapus.addEventListener('click', function () {
        isChecked = true;
        hapusGambar.checked = true;
        hapusCheckIcon.style.display = 'inline';
        confirmModal.hide();
    });
}
</script>
@endsection