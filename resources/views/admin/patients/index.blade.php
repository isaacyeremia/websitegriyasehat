@extends('layouts.app')

@section('title', 'Manajemen Data Pasien')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">👥 Manajemen Data Pasien</h2>
            <p class="text-muted">Kelola semua data pasien</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-people-fill"></i> Daftar Semua Pasien</h5>
        </div>
        <div class="card-body">

            {{-- Search --}}
            <div class="mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="🔍 Cari nama, NIK, atau email pasien...">
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle" id="patientTable">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Pasien</th>
                            <th>NIK</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                            <th>Total Kunjungan</th>
                            <th>Rekam Medis</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pasiens as $index => $pasien)
                            <tr>
                                <td>{{ $pasiens->firstItem() + $index }}</td>
                                <td><strong>{{ $pasien->name }}</strong></td>
                                <td>{{ $pasien->nik ?? '-' }}</td>
                                <td>{{ $pasien->email }}</td>
                                <td>{{ $pasien->phone }}</td>
                                <td>{{ Str::limit($pasien->address ?? '-', 30) }}</td>
                                <td><span class="badge bg-info">{{ $pasien->patient_histories_count }}</span></td>
                                <td><span class="badge bg-success">{{ $pasien->medical_records_count }}</span></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.patients.show', $pasien->id) }}" class="btn btn-info" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.patients.edit', $pasien->id) }}" class="btn btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        {{-- Hapus: pakai modal konfirmasi --}}
                                        <form method="POST"
                                              action="{{ route('admin.patients.destroy', $pasien->id) }}"
                                              class="d-inline confirm-form"
                                              data-type="danger"
                                              data-title="Hapus Data Pasien"
                                              data-msg="Yakin ingin menghapus pasien <strong>{{ $pasien->name }}</strong>?<br><small class='text-muted'>Semua riwayat dan rekam medis akan ikut terhapus.</small>">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-confirm-trigger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1"></i>
                                    <p>Belum ada data pasien</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $pasiens->links() }}
            </div>

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
        <div id="confirmIconWrap" class="mx-auto mb-3 d-inline-flex align-items-center justify-content-center rounded-circle"
             style="width:68px;height:68px;font-size:2rem;background:#FEE2E2;color:#DC2626;">
          <i class="bi bi-trash-fill" id="confirmIcon"></i>
        </div>
        <h5 class="fw-bold mb-2" id="confirmTitle">Konfirmasi</h5>
        <p class="mb-0" style="font-size:.92rem;color:#6B5E52;line-height:1.6" id="confirmMsg"></p>
      </div>
      <div class="modal-footer justify-content-center border-0 pb-4 gap-2">
        <button type="button" id="confirmCancelBtn" data-bs-dismiss="modal"
                style="background:#f3f4f6;color:#374151;border:none;padding:10px 28px;border-radius:8px;font-weight:600;">
          Batal
        </button>
        <button type="button" id="confirmOkBtn"
                style="padding:10px 28px;border-radius:8px;font-weight:600;border:none;background:#DC2626;color:#fff;">
          Ya, Hapus
        </button>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function () {
    const val = this.value.toLowerCase();
    document.querySelectorAll('#patientTable tbody tr').forEach(row => {
        const name  = row.cells[1]?.textContent.toLowerCase() || '';
        const nik   = row.cells[2]?.textContent.toLowerCase() || '';
        const email = row.cells[3]?.textContent.toLowerCase() || '';
        row.style.display = (name.includes(val) || nik.includes(val) || email.includes(val)) ? '' : 'none';
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var confirmModalEl = document.getElementById('confirmModal');
    var confirmOkBtn   = document.getElementById('confirmOkBtn');
    var confirmTitle   = document.getElementById('confirmTitle');
    var confirmMsg     = document.getElementById('confirmMsg');
    var pendingForm    = null;

    function getModal() {
        return bootstrap.Modal.getOrCreateInstance(confirmModalEl);
    }

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.btn-confirm-trigger');
        if (!btn) return;
        var form = btn.closest('.confirm-form');
        if (!form) return;

        confirmTitle.textContent = form.dataset.title || 'Konfirmasi';
        confirmMsg.innerHTML     = form.dataset.msg   || 'Lanjutkan aksi ini?';
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