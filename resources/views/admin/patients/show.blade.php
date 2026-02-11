@extends('layouts.app')

@section('title', 'Detail Pasien')

@section('content')
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">📋 Detail Data Pasien</h2>
            <p class="text-muted">Informasi lengkap pasien</p>
        </div>
        <div>
            <a href="{{ route('admin.patients.edit', $pasien->id) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil"></i> Edit Data
            </a>
            <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary">
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
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-person-circle"></i> Informasi Pasien</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">ID</th>
                            <td>: {{ $pasien->id }}</td>
                        </tr>
                        <tr>
                            <th>Nama Lengkap</th>
                            <td>: <strong>{{ $pasien->name }}</strong></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>: {{ $pasien->email }}</td>
                        </tr>
                        <tr>
                            <th>Telepon</th>
                            <td>: {{ $pasien->phone }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Alamat</th>
                            <td>: {{ $pasien->address ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Terdaftar Sejak</th>
                            <td>: {{ $pasien->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Total Kunjungan</th>
                            <td>: <span class="badge bg-info">{{ $riwayat->count() }} kali</span></td>
                        </tr>
                        <tr>
                            <th>Total Rekam Medis</th>
                            <td>: <span class="badge bg-success">{{ $rekam_medis->count() }} rekam</span></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            {{-- Tombol Input Rekam Medis & Hapus --}}
            <hr>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.medical-records.create', $pasien->id) }}" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Input Rekam Medis Baru
                </a>
                
                <form method="POST" action="{{ route('admin.patients.destroy', $pasien->id) }}" onsubmit="return confirm('⚠️ PERHATIAN!\n\nHapus data pasien {{ $pasien->name }}?\n\nSemua riwayat kunjungan dan rekam medis akan TERHAPUS PERMANEN!\n\nApakah Anda yakin?')" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Hapus Data Pasien
                    </button>
                </form>
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
                        <div class="border rounded p-3 mb-2">
                            <div class="d-flex justify-content-between mb-2">
                                <strong class="text-primary">{{ $data->kode_antrian }}</strong>
                                <span class="badge 
                                    @if($data->status == 'Menunggu') bg-warning text-dark
                                    @elseif($data->status == 'Selesai') bg-success
                                    @elseif($data->status == 'Dipanggil') bg-info
                                    @else bg-secondary
                                    @endif
                                ">
                                    {{ $data->status }}
                                </span>
                            </div>
                            <p class="mb-1 small"><strong>Poli:</strong> {{ $data->poli }}</p>
                            <p class="mb-1 small"><strong>Dokter:</strong> {{ $data->dokter }}</p>
                            <p class="mb-1 small"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($data->tanggal)->format('d M Y') }}</p>
                            <p class="mb-0 small"><strong>Keluhan:</strong> {{ $data->keluhan ?? '-' }}</p>
                        </div>
                    @empty
                        <p class="text-muted text-center">Belum ada riwayat kunjungan</p>
                    @endforelse
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
                        <div class="border rounded p-3 mb-2">
                            <div class="d-flex justify-content-between mb-2">
                                <strong>{{ \Carbon\Carbon::parse($record->checkup_date)->format('d M Y') }}</strong>
                                <small class="text-muted">oleh {{ $record->terapis->name }}</small>
                            </div>
                            <p class="mb-1 small"><strong>Keluhan:</strong> {{ Str::limit($record->complaint, 100) }}</p>
                            <p class="mb-1 small"><strong>Diagnosis:</strong> {{ $record->diagnosis }}</p>
                            
                            @if($record->medicine)
                            <p class="mb-2 small"><strong>Obat:</strong> {{ Str::limit($record->medicine, 80) }}</p>
                            @endif

                            {{-- FIX: Ganti route dari terapis ke admin --}}
                            <a href="{{ route('admin.medical-records.show', $record->id) }}" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-eye"></i> Lihat Detail
                            </a>
                        </div>
                    @empty
                        <p class="text-muted text-center">Belum ada rekam medis</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection