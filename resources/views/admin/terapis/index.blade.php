@extends('layouts.app')

@section('title', 'Kelola Terapis')

@section('content')
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">👨‍⚕️ Kelola Terapis</h2>
            <p class="text-muted mb-0">Manajemen data tenaga medis</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Dashboard
            </a>
            <a href="{{ route('admin.terapis.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Tambah Terapis
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card bg-primary">
                <div class="stat-icon"><i class="bi bi-people"></i></div>
                <div class="stat-content">
                    <h3>{{ $terapis->total() }}</h3>
                    <p>Total Terapis</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-success">
                <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
                <div class="stat-content">
                    <h3>{{ $terapis->where('is_active', true)->count() }}</h3>
                    <p>Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-secondary">
                <div class="stat-icon"><i class="bi bi-x-circle"></i></div>
                <div class="stat-content">
                    <h3>{{ $terapis->where('is_active', false)->count() }}</h3>
                    <p>Nonaktif</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.terapis.index') }}" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-bold">
                        <i class="bi bi-search"></i> Cari Terapis
                    </label>
                    <input type="text" name="search" class="form-control" 
                           value="{{ request('search') }}" 
                           placeholder="Nama atau jadwal...">
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
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
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
                <i class="bi bi-list-ul me-2"></i>Daftar Terapis
                <span class="badge bg-primary">{{ $terapis->total() }}</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3" width="5%">No</th>
                            <th width="10%">Foto</th>
                            <th width="20%">Nama</th>
                            <th width="15%">Kondisi & Minat Klinis</th>
                            <th width="20%">Jadwal</th>
                            <th width="15%">Daftar Harga</th>
                            <th width="8%">Urutan</th>
                            <th width="10%">Status</th>
                            <th class="text-center" width="12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($terapis as $index => $t)
                            <tr>
                                <td class="px-3">{{ $terapis->firstItem() + $index }}</td>
                                <td>
                                    @if($t->image)
                                        <img src="{{ asset('storage/' . $t->image) }}" 
                                             alt="{{ $t->name }}" 
                                             class="rounded-circle" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="bi bi-person"></i>
                                        </div>
                                    @endif
                                </td>
                                <td><strong>{{ $t->name }}</strong></td>
                                <td>{{ $t->kondisi_minat_klinis ?: '-' }}</td>
                                <td>{{ $t->schedule }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#hargaModal{{ $t->id }}">
                                        <i class="bi bi-list"></i> Lihat ({{ count($t->daftar_harga) }})
                                    </button>

                                    {{-- Modal Daftar Harga --}}
                                    <div class="modal fade" id="hargaModal{{ $t->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info text-white">
                                                    <h5 class="modal-title">Daftar Harga - {{ $t->name }}</h5>
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
                                </td>
                                <td><span class="badge bg-dark">{{ $t->urutan }}</span></td>
                                <td>
                                    <form method="POST" action="{{ route('admin.terapis.toggle', $t->id) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="btn btn-sm {{ $t->is_active ? 'btn-success' : 'btn-secondary' }}"
                                                onclick="return confirm('Ubah status terapis {{ $t->name }}?')">
                                            @if($t->is_active)
                                                <i class="bi bi-check-circle"></i> Aktif
                                            @else
                                                <i class="bi bi-x-circle"></i> Nonaktif
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.terapis.edit', $t->id) }}" 
                                           class="btn btn-outline-primary" 
                                           title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form method="POST" 
                                              action="{{ route('admin.terapis.destroy', $t->id) }}" 
                                              onsubmit="return confirm('Yakin hapus terapis {{ $t->name }}?')" 
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
                                    <p>Belum ada admin.terapis. <a href="{{ route('admin.terapis.create') }}">Tambah terapis pertama</a></p>
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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
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