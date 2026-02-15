@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<style>
/* ================================
   BOOTSTRAP ICONS (CDN FALLBACK)
   ================================ */
@import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css');

/* ================================
   STAT CARDS STYLING
   ================================ */
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

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.2);
}

.stat-card.bg-primary { 
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); 
}

.stat-card.bg-warning { 
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%); 
    color: #000; 
}

.stat-card.bg-info { 
    background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%); 
}

.stat-card.bg-success { 
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); 
}

.stat-card.bg-secondary { 
    background: linear-gradient(135deg, #6c757d 0%, #545b62 100%); 
}

.stat-card.bg-dark { 
    background: linear-gradient(135deg, #343a40 0%, #1d2124 100%); 
}

.stat-icon {
    font-size: 2.5rem;
    opacity: 0.9;
}

.stat-content h3 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    line-height: 1;
}

.stat-content p {
    margin: 5px 0 0 0;
    font-size: 0.85rem;
    opacity: 0.9;
}

/* ================================
   ACTION CARDS STYLING
   ================================ */
.action-card {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.action-card:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-color: #007bff;
    text-decoration: none;
    color: inherit;
}

.action-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: white;
    flex-shrink: 0;
}

.action-content {
    flex: 1;
}

.action-content h5 {
    margin: 0 0 5px 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
}

.action-content p {
    margin: 0;
    font-size: 0.85rem;
    color: #666;
}

.action-arrow {
    font-size: 1.5rem;
    color: #ccc;
}

.action-card:hover .action-arrow {
    color: #007bff;
}

/* ================================
   TABLE STYLING
   ================================ */
.table thead th {
    font-weight: 600;
    font-size: 0.9rem;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
    white-space: nowrap;
}

.table tbody tr {
    transition: background-color 0.2s;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

/* ================================
   RESPONSIVE DESIGN
   ================================ */
@media (max-width: 768px) {
    .stat-card, .action-card {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<div class="container-fluid py-4">
    {{-- Header Section --}}
    <div class="mb-4">
        <h2 class="fw-bold mb-1">🏥 Admin Dashboard</h2>
        <p class="text-muted mb-0">Selamat datang, <strong>{{ Auth::user()->name }}</strong></p>
    </div>

    {{-- Success Alert --}}
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
                    <h3>{{ $statistik['total'] }}</h3>
                    <p>Total Antrian</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="stat-card bg-warning">
                <div class="stat-icon"><i class="bi bi-clock"></i></div>
                <div class="stat-content">
                    <h3>{{ $statistik['menunggu'] }}</h3>
                    <p>Menunggu</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="stat-card bg-info">
                <div class="stat-icon"><i class="bi bi-megaphone"></i></div>
                <div class="stat-content">
                    <h3>{{ $statistik['dipanggil'] }}</h3>
                    <p>Dipanggil</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="stat-card bg-success">
                <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
                <div class="stat-content">
                    <h3>{{ $statistik['selesai'] }}</h3>
                    <p>Selesai</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="stat-card bg-secondary">
                <div class="stat-icon"><i class="bi bi-x-circle"></i></div>
                <div class="stat-content">
                    <h3>{{ $statistik['dibatalkan'] }}</h3>
                    <p>Dibatalkan</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="stat-card bg-dark">
                <div class="stat-icon"><i class="bi bi-people"></i></div>
                <div class="stat-content">
                    <h3>{{ $statistik['total_pasien'] }}</h3>
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
                <div class="action-content">
                    <h5>Data Pasien</h5>
                    <p>Kelola data pasien</p>
                </div>
                <div class="action-arrow"><i class="bi bi-arrow-right"></i></div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.terapis.index') }}" class="action-card">
                <div class="action-icon bg-success"><i class="bi bi-person-badge"></i></div>
                <div class="action-content">
                    <h5>Kelola Terapis</h5>
                    <p>Manajemen terapis</p>
                </div>
                <div class="action-arrow"><i class="bi bi-arrow-right"></i></div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.schedules.index') }}" class="action-card">
                <div class="action-icon bg-warning"><i class="bi bi-calendar-week"></i></div>
                <div class="action-content">
                    <h5>Jadwal Praktek</h5>
                    <p>Kelola jadwal dokter</p>
                </div>
                <div class="action-arrow"><i class="bi bi-arrow-right"></i></div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.pharmacy.index') }}" class="action-card">
                <div class="action-icon bg-danger"><i class="bi bi-capsule"></i></div>
                <div class="action-content">
                    <h5>Produk Apotek</h5>
                    <p>Kelola katalog produk</p>
                </div>
                <div class="action-arrow"><i class="bi bi-arrow-right"></i></div>
            </a>
        </div>
    </div>

    {{-- View All Queue Button --}}
    <div class="row g-3 mb-4">
        <div class="col-12">
            <a href="#daftarAntrian" class="action-card" onclick="event.preventDefault(); scrollToAntrian();">
                <div class="action-icon bg-info"><i class="bi bi-list-check"></i></div>
                <div class="action-content">
                    <h5>Semua Antrian</h5>
                    <p>Daftar antrian pasien</p>
                </div>
                <div class="action-arrow"><i class="bi bi-arrow-down"></i></div>
            </a>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">
                        <i class="bi bi-calendar-event"></i> Tanggal
                    </label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">
                        <i class="bi bi-list-check"></i> Status Antrian
                    </label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>⏳ Menunggu</option>
                        <option value="Dipanggil" {{ request('status') == 'Dipanggil' ? 'selected' : '' }}>📢 Dipanggil</option>
                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>✅ Selesai</option>
                        <option value="Dibatalkan" {{ request('status') == 'Dibatalkan' ? 'selected' : '' }}>❌ Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">
                        <i class="bi bi-person-check"></i> Status Kedatangan
                    </label>
                    <select name="arrival_status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="Belum Hadir" {{ request('arrival_status') == 'Belum Hadir' ? 'selected' : '' }}>🔴 Belum Hadir</option>
                        <option value="Sudah Hadir" {{ request('arrival_status') == 'Sudah Hadir' ? 'selected' : '' }}>✅ Sudah Hadir</option>
                        <option value="Tidak Hadir" {{ request('arrival_status') == 'Tidak Hadir' ? 'selected' : '' }}>❌ Tidak Hadir</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary w-100 mt-2">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Queue Table --}}
    <div class="card shadow-sm" id="daftarAntrian">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-bold"><i class="bi bi-list-ul me-2"></i>Daftar Semua Antrian</h5>
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
                    <tbody>
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
                                    <span class="badge 
                                        @if($antrian->status == 'Menunggu') bg-warning text-dark
                                        @elseif($antrian->status == 'Dipanggil') bg-info
                                        @elseif($antrian->status == 'Selesai') bg-success
                                        @else bg-secondary
                                        @endif">
                                        {{ $antrian->status }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($antrian->arrival_status == 'Belum Hadir') bg-secondary
                                        @elseif($antrian->arrival_status == 'Sudah Hadir') bg-success
                                        @else bg-danger
                                        @endif">
                                        {{ $antrian->arrival_status }}
                                    </span>
                                    @if($antrian->confirmed_at)
                                        <br><small class="text-muted">
                                            {{ \Carbon\Carbon::parse($antrian->confirmed_at)->timezone('Asia/Jakarta')->format('H:i') }} WIB
                                        </small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        {{-- Quick Check-In Button --}}
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
                                            <button class="btn btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>

                                    {{-- Modal Update Status --}}
                                    <div class="modal fade" id="updateModal{{ $antrian->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title">Update Status Antrian</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="{{ route('admin.antrian.update', $antrian->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <p class="mb-2"><strong>Kode Antrian:</strong> <span class="text-primary">{{ $antrian->kode_antrian }}</span></p>
                                                            <p class="mb-2"><strong>Nama Pasien:</strong> {{ $antrian->patient_name }}</p>
                                                            <p class="mb-2"><strong>NIK:</strong> {{ $antrian->patient_nik ?? 'Tidak ada' }}</p>
                                                            <p class="mb-2"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($antrian->tanggal)->format('d M Y') }}</p>
                                                            @if($antrian->appointment_time)
                                                                <p class="mb-0"><strong>Jam:</strong> {{ \Carbon\Carbon::parse($antrian->appointment_time)->format('H:i') }}</p>
                                                            @endif
                                                        </div>
                                                        
                                                        <hr>
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">
                                                                <i class="bi bi-person-check"></i> Status Kedatangan
                                                            </label>
                                                            <select name="arrival_status" class="form-select" required>
                                                                <option value="Belum Hadir" {{ $antrian->arrival_status == 'Belum Hadir' ? 'selected' : '' }}>🔴 Belum Hadir</option>
                                                                <option value="Sudah Hadir" {{ $antrian->arrival_status == 'Sudah Hadir' ? 'selected' : '' }}>✅ Sudah Hadir</option>
                                                                <option value="Tidak Hadir" {{ $antrian->arrival_status == 'Tidak Hadir' ? 'selected' : '' }}>❌ Tidak Hadir</option>
                                                            </select>
                                                            <small class="text-muted">Ubah status ini saat pasien check-in di front desk</small>
                                                            
                                                            @if($antrian->confirmed_at)
                                                                <div class="alert alert-info mt-2 mb-0">
                                                                    <small>
                                                                        <i class="bi bi-info-circle"></i> 
                                                                        Dikonfirmasi pada: {{ \Carbon\Carbon::parse($antrian->confirmed_at)->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB
                                                                    </small>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">
                                                                <i class="bi bi-list-check"></i> Status Antrian
                                                            </label>
                                                            <select name="status" class="form-select" required>
                                                                <option value="Menunggu" {{ $antrian->status == 'Menunggu' ? 'selected' : '' }}>⏳ Menunggu</option>
                                                                <option value="Dipanggil" {{ $antrian->status == 'Dipanggil' ? 'selected' : '' }}>📢 Dipanggil</option>
                                                                <option value="Selesai" {{ $antrian->status == 'Selesai' ? 'selected' : '' }}>✅ Selesai</option>
                                                                <option value="Dibatalkan" {{ $antrian->status == 'Dibatalkan' ? 'selected' : '' }}>❌ Dibatalkan</option>
                                                            </select>
                                                        </div>

                                                        <div class="alert alert-warning mb-0">
                                                            <small>
                                                                <i class="bi bi-lightbulb"></i> <strong>Tips:</strong><br>
                                                                • Ubah ke "Sudah Hadir" saat pasien check-in<br>
                                                                • Ubah ke "Tidak Hadir" jika pasien tidak datang<br>
                                                                • Status antrian "Dipanggil" untuk memanggil pasien
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="bi bi-save"></i> Simpan Perubahan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
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
                <div class="p-3 border-top">
                    {{ $antrians->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function scrollToAntrian() {
    const element = document.getElementById('daftarAntrian');
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}
</script>
@endsection