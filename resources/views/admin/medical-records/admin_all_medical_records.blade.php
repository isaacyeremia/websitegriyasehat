@extends('layouts.app')

@section('title', 'Semua Rekam Medis')

@section('content')
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">📄 Semua Rekam Medis</h2>
            <p class="text-muted">Daftar rekam medis dari semua terapis/dokter</p>
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

    {{-- Filter Section --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="bi bi-funnel"></i> Filter Rekam Medis</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.medical-records.all') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Cari Pasien</label>
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Nama atau email pasien..."
                               value="{{ request('search') }}">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Terapis/Dokter</label>
                        <select name="terapis_id" class="form-select">
                            <option value="">Semua Terapis</option>
                            @foreach($allTerapis as $terapis)
                                <option value="{{ $terapis->id }}" {{ request('terapis_id') == $terapis->id ? 'selected' : '' }}>
                                    {{ $terapis->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" 
                               name="date_from" 
                               class="form-control"
                               value="{{ request('date_from') }}">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" 
                               name="date_to" 
                               class="form-control"
                               value="{{ request('date_to') }}">
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Filter
                        </button>
                    </div>
                </div>
                
                @if(request()->hasAny(['search', 'terapis_id', 'date_from', 'date_to']))
                    <div class="mt-2">
                        <a href="{{ route('admin.medical-records.all') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Reset Filter
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Records Table --}}
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-file-medical"></i> Daftar Rekam Medis ({{ $records->total() }} rekam)</h5>
        </div>
        <div class="card-body">
            
            @if($records->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Pasien</th>
                                <th>Terapis/Dokter</th>
                                <th>Keluhan</th>
                                <th>Diagnosis</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $index => $record)
                                <tr>
                                    <td>{{ $records->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ \Carbon\Carbon::parse($record->checkup_date)->format('d M Y') }}</strong>
                                        <br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($record->created_at)->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $record->patient->name ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $record->patient->email ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $record->terapis->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>{{ Str::limit($record->complaint, 50) }}</td>
                                    <td>{{ Str::limit($record->diagnosis, 50) }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.medical-records.show', $record->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Lihat Detail">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $records->links() }}
                </div>
            @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-1"></i>
                    <p class="mb-0 mt-3">
                        @if(request()->hasAny(['search', 'terapis_id', 'date_from', 'date_to']))
                            Tidak ada rekam medis yang sesuai dengan filter
                        @else
                            Belum ada rekam medis
                        @endif
                    </p>
                </div>
            @endif

        </div>
    </div>

</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection