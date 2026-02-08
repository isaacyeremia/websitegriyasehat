@extends('layouts.app')

@section('title', 'Dashboard Terapis')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-3">Dashboard Terapis</h2>
            <p class="text-muted">Selamat datang, {{ Auth::user()->name }}</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Pasien</p>
                            <h3 class="mb-0">{{ $totalPasien }}</h3>
                        </div>
                        <div class="text-primary fs-1">👥</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Antrian</p>
                            <h3 class="mb-0">{{ $totalAntrian }}</h3>
                        </div>
                        <div class="text-success fs-1">📋</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Antrian Hari Ini</p>
                            <h3 class="mb-0">{{ $antrianHariIni }}</h3>
                        </div>
                        <div class="text-warning fs-1">📅</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Sedang Menunggu</p>
                            <h3 class="mb-0">{{ $antrianMenunggu }}</h3>
                        </div>
                        <div class="text-info fs-1">⏳</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Queues -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Antrian Terbaru</h5>
                </div>
                <div class="card-body">
                    @if($recentQueues->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Kode Antrian</th>
                                        <th>Nama Pasien</th>
                                        <th>Layanan</th>
                                        <th>Dokter</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentQueues as $queue)
                                        <tr>
                                            <td><strong>{{ $queue->kode_antrian }}</strong></td>
                                            <td>{{ $queue->patient_name }}</td>
                                            <td>{{ $queue->service }}</td>
                                            <td>{{ $queue->dokter }}</td>
                                            <td>{{ \Carbon\Carbon::parse($queue->tanggal)->format('d M Y') }}</td>
                                            <td>
                                                @if($queue->status == 'Menunggu')
                                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                                @elseif($queue->status == 'Sedang Dilayani')
                                                    <span class="badge bg-info">Sedang Dilayani</span>
                                                @elseif($queue->status == 'Selesai')
                                                    <span class="badge bg-success">Selesai</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $queue->status }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">Belum ada antrian</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2">
                        <a href="{{ route('terapis.patients.index') }}" class="btn btn-primary">
                            👥 Lihat Semua Pasien
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
}
</style>
@endsection