@extends('layouts.app')

@section('title', 'Detail Pasien')

@section('content')
<div class="container-fluid py-4">
    
        <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">📋 Detail Pasien</h2>
            <p class="text-muted">Riwayat kunjungan dan rekam medis</p>
        </div>
                <a href="{{ route('terapis.patients.index') }}" class="btn btn-secondary">
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

    {{-- Info Pasien --}}
    <div class="card shadow mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-person-circle"></i> Informasi Pasien</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
    <table class="table table-borderless">
        <tr>
            <th width="150">Nama Lengkap</th>
            <td>: <strong>{{ $pasien->name }}</strong></td>
        </tr>
        <tr>
            <th>NIK/KTP</th>
            <td>: {{ $pasien->nik ?? '-' }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>: {{ $pasien->email }}</td>
        </tr>
        <tr>
            <th>Telepon</th>
            <td>: {{ $pasien->phone }}</td>
        </tr>
        <tr>
            <th>Alamat</th>
            <td>: {{ $pasien->address ?? '-' }}</td>
        </tr>
    </table>
</div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('terapis.medical-records.create', $pasien->id) }}" class="btn btn-success btn-lg">
                        <i class="bi bi-plus-circle"></i> Input Rekam Medis Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Riwayat Kunjungan --}}
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Riwayat Kunjungan</h5>
                </div>
                <div class="card-body">
                    @forelse($riwayat as $data)
                        <div class="border-bottom py-2">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $data->kode_antrian }}</strong>
                                <span class="badge 
                                    @if($data->status == 'Menunggu') bg-warning text-dark
                                    @elseif($data->status == 'Selesai') bg-success
                                    @else bg-info
                                    @endif
                                ">
                                    {{ $data->status }}
                                </span>
                            </div>
                            <small class="text-muted">
                                {{ $data->poli }} - {{ $data->dokter }}<br>
                                {{ \Carbon\Carbon::parse($data->tanggal)->format('d M Y') }}
                            </small>
                        </div>
                    @empty
                        <p class="text-muted text-center">Belum ada riwayat kunjungan</p>
                    @endforelse
                    
                    <div class="mt-3">
                        {{ $riwayat->links() }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Rekam Medis --}}
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-file-medical"></i> Rekam Medis</h5>
                </div>
                <div class="card-body">
                    @forelse($rekam_medis as $record)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <strong>{{ \Carbon\Carbon::parse($record->checkup_date)->format('d M Y') }}</strong>
                                <small class="text-muted">oleh {{ $record->terapis->name }}</small>
                            </div>
                            <p class="mb-1"><strong>Keluhan:</strong> {{ Str::limit($record->complaint, 80) }}</p>
                            <p class="mb-1"><strong>Diagnosis:</strong> {{ $record->diagnosis }}</p>
                            <a href="{{ route('terapis.medical-records.show', $record->id) }}" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-eye"></i> Lihat Detail
                            </a>
                        </div>
                    @empty
                        <p class="text-muted text-center">Belum ada rekam medis</p>
                    @endforelse
                    
                    <div class="mt-3">
                        {{ $rekam_medis->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection