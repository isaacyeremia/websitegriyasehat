@extends('layouts.app')

@section('title', 'Manajemen Jadwal Praktek')

@section('content')
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">📅 Manajemen Jadwal Praktek</h2>
            <p class="text-muted">Kelola jadwal praktek dan kuota dokter/terapis</p>
        </div>
        <div>
            <a href="{{ route('admin.schedules.create') }}" class="btn btn-success me-2">
                <i class="bi bi-plus-circle"></i> Tambah Jadwal
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
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

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-calendar-week"></i> Semua Jadwal Praktek</h5>
        </div>
        <div class="card-body">
            @if(isset($schedules) && $schedules->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Dokter/Terapis</th>
                                <th>Hari</th>
                                <th>Jam Praktek</th>
                                <th>Kuota</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedules as $index => $schedule)
                                <tr>
                                    <td>{{ $schedules->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $schedule->doctor->name ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $schedule->doctor->specialization ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $schedule->day_of_week }}</span>
                                    </td>
                                    <td>
                                        <i class="bi bi-clock"></i> 
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $schedule->quota >= 10 ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ $schedule->quota ?? 10 }} pasien/hari
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.schedules.toggle', $schedule->id) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $schedule->is_active ? 'btn-success' : 'btn-secondary' }}">
                                                @if($schedule->is_active)
                                                    <i class="bi bi-check-circle"></i> Aktif
                                                @else
                                                    <i class="bi bi-x-circle"></i> Nonaktif
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="btn btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.schedules.destroy', $schedule->id) }}" onsubmit="return confirm('Yakin hapus jadwal ini?')" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $schedules->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x" style="font-size: 4rem; color: #ccc;"></i>
                    <h5 class="text-muted mt-3">Belum ada jadwal praktek</h5>
                    <p class="text-muted">Klik tombol "Tambah Jadwal" untuk membuat jadwal praktek baru</p>
                    <a href="{{ route('admin.schedules.create') }}" class="btn btn-success mt-2">
                        <i class="bi bi-plus-circle"></i> Tambah Jadwal Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>

</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection