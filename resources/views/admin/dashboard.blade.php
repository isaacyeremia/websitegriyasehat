@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<style>
@import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css');

.stat-card {
    background: linear-gradient(135deg, var(--bs-primary, #007bff) 0%, #0056b3 100%);
    color: white;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}
.stat-card:hover { transform: translateY(-5px); box-shadow: 0 8px 15px rgba(0,0,0,0.2); }
.stat-card.bg-primary  { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); }
.stat-card.bg-warning  { background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%); color: #000; }
.stat-card.bg-info     { background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%); }
.stat-card.bg-success  { background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); }
.stat-card.bg-secondary{ background: linear-gradient(135deg, #6c757d 0%, #545b62 100%); }
.stat-card.bg-dark     { background: linear-gradient(135deg, #343a40 0%, #1d2124 100%); }

.stat-icon { font-size: 2.5rem; opacity: 0.9; }
.stat-content h3 { font-size: 2rem; font-weight: 700; margin: 0; line-height: 1; }
.stat-content p  { margin: 5px 0 0 0; font-size: 0.85rem; opacity: 0.9; }

.action-card {
    display: flex; align-items: center; gap: 15px; padding: 20px;
    background: white; border: 1px solid #e0e0e0; border-radius: 12px;
    text-decoration: none; color: inherit; transition: all 0.3s;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}
.action-card:hover { transform: translateX(5px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-color: #007bff; text-decoration: none; color: inherit; }
.action-icon { width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: white; flex-shrink: 0; }
.action-content { flex: 1; }
.action-content h5 { margin: 0 0 5px 0; font-size: 1.1rem; font-weight: 600; color: #333; }
.action-content p  { margin: 0; font-size: 0.85rem; color: #666; }
.action-arrow { font-size: 1.5rem; color: #ccc; }
.action-card:hover .action-arrow { color: #007bff; }

.table thead th { font-weight: 600; font-size: 0.9rem; color: #495057; border-bottom: 2px solid #dee2e6; white-space: nowrap; }
.table tbody tr { transition: background-color 0.2s; }
.table tbody tr:hover { background-color: #f8f9fa; }

/* Live indicator */
.live-dot {
    display: inline-block;
    width: 8px; height: 8px;
    background: #28a745;
    border-radius: 50%;
    animation: pulse 1.5s infinite;
    margin-right: 4px;
}
@keyframes pulse {
    0%   { opacity: 1; transform: scale(1); }
    50%  { opacity: 0.5; transform: scale(1.3); }
    100% { opacity: 1; transform: scale(1); }
}

@media (max-width: 768px) {
    .stat-card, .action-card { flex-direction: column; text-align: center; }
}
</style>

<div class="container-fluid py-4">
    <div class="mb-4">
        <h2 class="fw-bold mb-1">🏥 Admin Dashboard</h2>
        <p class="text-muted mb-0">Selamat datang, <strong>{{ Auth::user()->name }}</strong></p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Statistics Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-2 col-sm-4 col-6">
            <div class="stat-card bg-primary">
                <div class="stat-icon"><i class="bi bi-list-ul"></i></div>
                <div class="stat-content">
                    <h3 id="stat-total">{{ $statistik['total'] }}</h3>
                    <p>Total Antrian</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="stat-card bg-warning">
                <div class="stat-icon"><i class="bi bi-clock"></i></div>
                <div class="stat-content">
                    <h3 id="stat-menunggu">{{ $statistik['menunggu'] }}</h3>
                    <p>Menunggu</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="stat-card bg-info">
                <div class="stat-icon"><i class="bi bi-megaphone"></i></div>
                <div class="stat-content">
                    <h3 id="stat-dipanggil">{{ $statistik['dipanggil'] }}</h3>
                    <p>Dipanggil</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="stat-card bg-success">
                <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
                <div class="stat-content">
                    <h3 id="stat-selesai">{{ $statistik['selesai'] }}</h3>
                    <p>Selesai</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="stat-card bg-secondary">
                <div class="stat-icon"><i class="bi bi-x-circle"></i></div>
                <div class="stat-content">
                    <h3 id="stat-dibatalkan">{{ $statistik['dibatalkan'] }}</h3>
                    <p>Dibatalkan</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="stat-card bg-dark">
                <div class="stat-icon"><i class="bi bi-people"></i></div>
                <div class="stat-content">
                    <h3 id="stat-total-pasien">{{ $statistik['total_pasien'] }}</h3>
                    <p>Total Pasien</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.patients.index') }}" class="action-card">
                <div class="action-icon bg-primary"><i class="bi bi-people-fill"></i></div>
                <div class="action-content"><h5>Data Pasien</h5><p>Kelola data pasien</p></div>
                <div class="action-arrow"><i class="bi bi-arrow-right"></i></div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.terapis.index') }}" class="action-card">
                <div class="action-icon bg-success"><i class="bi bi-person-badge"></i></div>
                <div class="action-content"><h5>Kelola Terapis</h5><p>Manajemen terapis</p></div>
                <div class="action-arrow"><i class="bi bi-arrow-right"></i></div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.schedules.index') }}" class="action-card">
                <div class="action-icon bg-warning"><i class="bi bi-calendar-week"></i></div>
                <div class="action-content"><h5>Jadwal Praktek</h5><p>Kelola jadwal dokter</p></div>
                <div class="action-arrow"><i class="bi bi-arrow-right"></i></div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.pharmacy.index') }}" class="action-card">
                <div class="action-icon bg-danger"><i class="bi bi-capsule"></i></div>
                <div class="action-content"><h5>Produk Apotek</h5><p>Kelola katalog produk</p></div>
                <div class="action-arrow"><i class="bi bi-arrow-right"></i></div>
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12">
            <a href="#daftarAntrian" class="action-card" onclick="event.preventDefault(); scrollToAntrian();">
                <div class="action-icon bg-info"><i class="bi bi-list-check"></i></div>
                <div class="action-content"><h5>Semua Antrian</h5><p>Daftar antrian pasien</p></div>
                <div class="action-arrow"><i class="bi bi-arrow-down"></i></div>
            </a>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-3 align-items-end" id="filterForm">
                <div class="col-md-3">
                    <label class="form-label small fw-bold"><i class="bi bi-calendar-event"></i> Tanggal</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold"><i class="bi bi-list-check"></i> Status Antrian</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="Menunggu"  {{ request('status') == 'Menunggu'  ? 'selected' : '' }}>⏳ Menunggu</option>
                        <option value="Dipanggil" {{ request('status') == 'Dipanggil' ? 'selected' : '' }}>📢 Dipanggil</option>
                        <option value="Selesai"   {{ request('status') == 'Selesai'   ? 'selected' : '' }}>✅ Selesai</option>
                        <option value="Dibatalkan"{{ request('status') == 'Dibatalkan'? 'selected' : '' }}>❌ Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold"><i class="bi bi-person-check"></i> Status Kedatangan</label>
                    <select name="arrival_status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="Belum Hadir" {{ request('arrival_status') == 'Belum Hadir' ? 'selected' : '' }}>🔴 Belum Hadir</option>
                        <option value="Sudah Hadir" {{ request('arrival_status') == 'Sudah Hadir' ? 'selected' : '' }}>✅ Sudah Hadir</option>
                        <option value="Tidak Hadir" {{ request('arrival_status') == 'Tidak Hadir' ? 'selected' : '' }}>❌ Tidak Hadir</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Filter</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary w-100 mt-2"><i class="bi bi-arrow-clockwise"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Queue Table --}}
    <div class="card shadow-sm" id="daftarAntrian">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="bi bi-list-ul me-2"></i>Daftar Semua Antrian</h5>
            <small class="text-muted">
                <span class="live-dot"></span> Live &middot; Update terakhir: <span id="last-refresh">--:--:--</span>
            </small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3">No</th>
                            <th>Kode</th>
                            <th>Nama Pasien</th>
                            <th>NIK</th>
                            <th>Poli</th>
                            <th>Dokter</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Keluhan</th>
                            <th>Status</th>
                            <th>Kedatangan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="antrian-tbody">
                        @forelse($antrians as $index => $antrian)
                            <tr>
                                <td class="px-3">{{ $antrians->firstItem() + $index }}</td>
                                <td><strong class="text-primary">{{ $antrian->kode_antrian }}</strong></td>
                                <td>{{ $antrian->patient_name }}</td>
                                <td>{{ $antrian->patient_nik ?? '-' }}</td>
                                <td>{{ $antrian->poli }}</td>
                                <td>{{ $antrian->dokter }}</td>
                                <td>{{ \Carbon\Carbon::parse($antrian->tanggal)->format('d M Y') }}</td>
                                <td>
                                    @if($antrian->appointment_time)
                                        <i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($antrian->appointment_time)->format('H:i') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($antrian->keluhan ?? '-', 30) }}</td>
                                <td>
                                    <span class="badge @if($antrian->status == 'Menunggu') bg-warning text-dark @elseif($antrian->status == 'Dipanggil') bg-info @elseif($antrian->status == 'Selesai') bg-success @else bg-secondary @endif">
                                        {{ $antrian->status }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge @if($antrian->arrival_status == 'Belum Hadir') bg-secondary @elseif($antrian->arrival_status == 'Sudah Hadir') bg-success @else bg-danger @endif">
                                        {{ $antrian->arrival_status }}
                                    </span>
                                    @if($antrian->confirmed_at)
                                        <br><small class="text-muted">{{ \Carbon\Carbon::parse($antrian->confirmed_at)->timezone('Asia/Jakarta')->format('H:i') }} WIB</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        @if($antrian->arrival_status == 'Belum Hadir' && $antrian->tanggal == now()->toDateString())
                                            <form method="POST" action="{{ route('admin.antrian.update', $antrian->id) }}" class="d-inline" title="Quick Check-In">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="{{ $antrian->status }}">
                                                <input type="hidden" name="arrival_status" value="Sudah Hadir">
                                                <button type="submit" class="btn btn-success" onclick="return confirm('Konfirmasi pasien {{ $antrian->patient_name }} sudah hadir?')">
                                                    <i class="bi bi-person-check-fill"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updateModal{{ $antrian->id }}" title="Update Status">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form method="POST" action="{{ route('admin.antrian.delete', $antrian->id) }}" onsubmit="return confirm('Yakin hapus antrian {{ $antrian->kode_antrian }}?')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    <p>Belum ada antrian</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($antrians->hasPages())
                <div class="p-3 border-top" id="pagination-links">
                    {{ $antrians->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- MODALS --}}
@foreach($antrians as $antrian)
<div class="modal fade" id="updateModal{{ $antrian->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Update Status Antrian</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.antrian.update', $antrian->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="row g-2">
                            <div class="col-12"><strong>Kode Antrian:</strong> <span class="text-primary">{{ $antrian->kode_antrian }}</span></div>
                            <div class="col-12"><strong>Nama Pasien:</strong> {{ $antrian->patient_name }}</div>
                            <div class="col-12"><strong>NIK:</strong> {{ $antrian->patient_nik ?? 'Tidak ada' }}</div>
                            <div class="col-6"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($antrian->tanggal)->format('d M Y') }}</div>
                            @if($antrian->appointment_time)
                            <div class="col-6"><strong>Jam:</strong> {{ \Carbon\Carbon::parse($antrian->appointment_time)->format('H:i') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="bi bi-person-check"></i> Status Kedatangan</label>
                        <select name="arrival_status" class="form-select" required>
                            <option value="Belum Hadir" {{ $antrian->arrival_status == 'Belum Hadir' ? 'selected' : '' }}>🔴 Belum Hadir</option>
                            <option value="Sudah Hadir" {{ $antrian->arrival_status == 'Sudah Hadir' ? 'selected' : '' }}>✅ Sudah Hadir</option>
                            <option value="Tidak Hadir" {{ $antrian->arrival_status == 'Tidak Hadir' ? 'selected' : '' }}>❌ Tidak Hadir</option>
                        </select>
                        <small class="text-muted d-block mt-1">Ubah status saat pasien check-in</small>
                        @if($antrian->confirmed_at)
                            <div class="alert alert-info mt-2 mb-0 py-2">
                                <small><i class="bi bi-info-circle"></i> Dikonfirmasi: {{ \Carbon\Carbon::parse($antrian->confirmed_at)->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB</small>
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="bi bi-list-check"></i> Status Antrian</label>
                        <select name="status" class="form-select" required>
                            <option value="Menunggu"  {{ $antrian->status == 'Menunggu'  ? 'selected' : '' }}>⏳ Menunggu</option>
                            <option value="Dipanggil" {{ $antrian->status == 'Dipanggil' ? 'selected' : '' }}>📢 Dipanggil</option>
                            <option value="Selesai"   {{ $antrian->status == 'Selesai'   ? 'selected' : '' }}>✅ Selesai</option>
                            <option value="Dibatalkan"{{ $antrian->status == 'Dibatalkan'? 'selected' : '' }}>❌ Dibatalkan</option>
                        </select>
                    </div>
                    <div class="alert alert-warning mb-0 py-2">
                        <small><i class="bi bi-lightbulb"></i> <strong>Tips:</strong><br>
                        • "Sudah Hadir" saat pasien check-in<br>
                        • "Tidak Hadir" jika pasien tidak datang<br>
                        • "Dipanggil" untuk memanggil pasien</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
const CSRF_TOKEN = '{{ csrf_token() }}';
const LIVE_DATA_URL = '{{ route('admin.live.data') }}';
const TODAY = '{{ now()->toDateString() }}';

function scrollToAntrian() {
    document.getElementById('daftarAntrian')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// =====================================================
// MODAL FIX
// =====================================================
document.addEventListener('show.bs.modal', function(event) {
    const modal = event.target;
    document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
    setTimeout(() => {
        modal.style.backgroundColor = 'rgba(0, 0, 0, 0.65)';
        modal.style.display = 'flex';
        modal.style.alignItems = 'center';
        modal.style.justifyContent = 'center';
        modal.querySelectorAll('*').forEach(el => el.style.pointerEvents = 'auto');
    }, 10);
});

document.addEventListener('hidden.bs.modal', function(event) {
    const modal = event.target;
    modal.style.backgroundColor = '';
    document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
    // Refresh data setelah modal tutup (status mungkin berubah)
    fetchLiveData();
});

// =====================================================
// LIVE REFRESH - AJAX POLLING SETIAP 5 DETIK
// =====================================================
let isModalOpen = false;
let refreshTimer = null;

document.addEventListener('show.bs.modal',   () => { isModalOpen = true;  });
document.addEventListener('hidden.bs.modal', () => { isModalOpen = false; });

function statusBadgeClass(status) {
    return {
        'Menunggu':  'bg-warning text-dark',
        'Dipanggil': 'bg-info',
        'Selesai':   'bg-success',
        'Dibatalkan':'bg-secondary',
    }[status] || 'bg-secondary';
}

function arrivalBadgeClass(arrival) {
    return {
        'Belum Hadir': 'bg-secondary',
        'Sudah Hadir': 'bg-success',
        'Tidak Hadir': 'bg-danger',
    }[arrival] || 'bg-secondary';
}

function renderRow(a, nomor) {
    const jamHtml = a.appointment_time
        ? `<i class="bi bi-clock"></i> ${a.appointment_time}`
        : '<span class="text-muted">-</span>';

    const confirmedHtml = a.confirmed_at
        ? `<br><small class="text-muted">${a.confirmed_at} WIB</small>`
        : '';

    const checkinBtn = (a.arrival_status === 'Belum Hadir' && a.tanggal_raw === TODAY)
        ? `<form method="POST" action="/admin/antrian/${a.id}/status" class="d-inline">
               <input type="hidden" name="_token" value="${CSRF_TOKEN}">
               <input type="hidden" name="_method" value="PUT">
               <input type="hidden" name="status" value="${a.status}">
               <input type="hidden" name="arrival_status" value="Sudah Hadir">
               <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Konfirmasi pasien ${a.patient_name.replace(/'/g,"\\'")} sudah hadir?')">
                   <i class="bi bi-person-check-fill"></i>
               </button>
           </form>`
        : '';

    return `<tr>
        <td class="px-3">${nomor}</td>
        <td><strong class="text-primary">${a.kode_antrian}</strong></td>
        <td>${a.patient_name}</td>
        <td>${a.patient_nik}</td>
        <td>${a.poli}</td>
        <td>${a.dokter}</td>
        <td>${a.tanggal}</td>
        <td>${jamHtml}</td>
        <td>${a.keluhan}</td>
        <td><span class="badge ${statusBadgeClass(a.status)}">${a.status}</span></td>
        <td>
            <span class="badge ${arrivalBadgeClass(a.arrival_status)}">${a.arrival_status}</span>
            ${confirmedHtml}
        </td>
        <td class="text-center">
            <div class="btn-group btn-group-sm">
                ${checkinBtn}
                <button type="button" class="btn btn-outline-primary"
                    data-bs-toggle="modal" data-bs-target="#updateModal${a.id}">
                    <i class="bi bi-pencil-square"></i>
                </button>
                <form method="POST" action="/admin/antrian/${a.id}" class="d-inline"
                      onsubmit="return confirm('Yakin hapus antrian ${a.kode_antrian}?')">
                    <input type="hidden" name="_token" value="${CSRF_TOKEN}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
            </div>
        </td>
    </tr>`;
}

function fetchLiveData() {
    if (isModalOpen) return;

    const params = new URLSearchParams(window.location.search);

    fetch(`${LIVE_DATA_URL}?${params.toString()}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(json => {
        // Update stat cards
        const statMap = {
            'stat-total':        json.statistik.total,
            'stat-menunggu':     json.statistik.menunggu,
            'stat-dipanggil':    json.statistik.dipanggil,
            'stat-selesai':      json.statistik.selesai,
            'stat-dibatalkan':   json.statistik.dibatalkan,
            'stat-total-pasien': json.statistik.total_pasien,
        };
        Object.entries(statMap).forEach(([id, val]) => {
            const el = document.getElementById(id);
            if (el && el.textContent != val) el.textContent = val;
        });

        // Update tbody
        const tbody = document.getElementById('antrian-tbody');
        if (!tbody) return;

        const rows = json.antrians.data;
        const offset = json.antrians.from ? json.antrians.from - 1 : 0;

        if (!rows || rows.length === 0) {
            tbody.innerHTML = `<tr><td colspan="12" class="text-center text-muted py-5">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i><p>Belum ada antrian</p>
            </td></tr>`;
        } else {
            tbody.innerHTML = rows.map((a, i) => renderRow(a, offset + i + 1)).join('');
        }

        // Update timestamp
        const now = new Date();
        const ts = [now.getHours(), now.getMinutes(), now.getSeconds()]
            .map(n => String(n).padStart(2, '0')).join(':');
        const el = document.getElementById('last-refresh');
        if (el) el.textContent = ts;
    })
    .catch(err => console.warn('Live refresh error:', err));
}

// Mulai polling setiap 5 detik
refreshTimer = setInterval(fetchLiveData, 5000);
window.addEventListener('beforeunload', () => clearInterval(refreshTimer));
</script>
@endsection