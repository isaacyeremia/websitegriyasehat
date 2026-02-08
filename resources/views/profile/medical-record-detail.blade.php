@extends('layouts.app')

@section('title', 'Detail Rekam Medis')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-10 mx-auto">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">📄 Detail Rekam Medis Saya</h2>
                <div>
                    <button onclick="window.print()" class="btn btn-secondary me-2">
                        <i class="bi bi-printer"></i> Print
                    </button>
                    <a href="{{ route('profile') }}" class="btn btn-brown">
                        <i class="bi bi-arrow-left"></i> Kembali ke Profile
                    </a>
                </div>
            </div>

            <div class="card shadow" id="printArea">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Rekam Medis</h4>
                </div>
                <div class="card-body">
                    
                    {{-- Header Info --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Informasi Pemeriksaan</h5>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="150">Tanggal</th>
                                    <td>: {{ $record->checkup_date ? \Carbon\Carbon::parse($record->checkup_date)->format('d M Y') : \Carbon\Carbon::parse($record->created_at)->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Terapis</th>
                                    <td>: {{ $record->terapis->name ?? 'Terapis' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    {{-- Keluhan --}}
                    <div class="mb-4">
                        <h5 class="text-primary">📋 Keluhan & Anamnesis</h5>
                        
                        <div class="mb-3">
                            <strong>Keluhan Utama:</strong>
                            <p class="ms-3 bg-light p-3 rounded">{{ $record->complaint }}</p>
                        </div>

                        @if($record->anamnesis)
                        <div class="mb-3">
                            <strong>Anamnesis Detail:</strong>
                            <p class="ms-3 bg-light p-3 rounded">{{ $record->anamnesis }}</p>
                        </div>
                        @endif

                        @if($record->riwayat_penyakit)
                        <div class="mb-3">
                            <strong>Riwayat Penyakit:</strong>
                            <p class="ms-3 bg-light p-3 rounded">{{ $record->riwayat_penyakit }}</p>
                        </div>
                        @endif
                    </div>

                    <hr>

                    {{-- Diagnosis --}}
                    <div class="mb-4">
                        <h5 class="text-success">🔍 Diagnosis</h5>
                        
                        <div class="mb-3">
                            <strong>Diagnosis:</strong>
                            <p class="ms-3 bg-light p-3 rounded">{{ $record->diagnosis }}</p>
                        </div>

                        @if($record->diagnosis_awal)
                        <div class="mb-3">
                            <strong>Diagnosis Awal:</strong>
                            <p class="ms-3 bg-light p-3 rounded">{{ $record->diagnosis_awal }}</p>
                        </div>
                        @endif

                        @if($record->diagnosis_akhir)
                        <div class="mb-3">
                            <strong>Diagnosis Akhir:</strong>
                            <p class="ms-3 bg-light p-3 rounded">{{ $record->diagnosis_akhir }}</p>
                        </div>
                        @endif
                    </div>

                    <hr>

                    {{-- Pengobatan --}}
                    <div class="mb-4">
                        <h5 class="text-info">💊 Pengobatan & Tindakan</h5>
                        
                        @if($record->treatment)
                        <div class="mb-3">
                            <strong>Tindakan Medis:</strong>
                            <p class="ms-3 bg-light p-3 rounded">{{ $record->treatment }}</p>
                        </div>
                        @endif

                        @if($record->pengobatan)
                        <div class="mb-3">
                            <strong>Pengobatan:</strong>
                            <p class="ms-3 bg-light p-3 rounded">{{ $record->pengobatan }}</p>
                        </div>
                        @endif

                        @if($record->medicine)
                        <div class="mb-3">
                            <strong>Obat yang Diberikan:</strong>
                            <div class="ms-3 bg-light p-3 rounded" style="white-space: pre-line;">{{ $record->medicine }}</div>
                        </div>
                        @endif

                        @if($record->obat_diberikan)
                        <div class="mb-3">
                            <strong>Obat Tambahan:</strong>
                            <div class="ms-3 bg-light p-3 rounded" style="white-space: pre-line;">{{ $record->obat_diberikan }}</div>
                        </div>
                        @endif
                    </div>

                    <hr>

                    {{-- Catatan --}}
                    @if($record->doctor_note || $record->catatan_tambahan)
                    <div class="mb-4">
                        <h5 class="text-warning">📝 Catatan</h5>
                        
                        @if($record->doctor_note)
                        <div class="mb-3">
                            <strong>Catatan Terapis:</strong>
                            <p class="ms-3 bg-light p-3 rounded">{{ $record->doctor_note }}</p>
                        </div>
                        @endif

                        @if($record->catatan_tambahan)
                        <div class="mb-3">
                            <strong>Catatan Tambahan:</strong>
                            <p class="ms-3 bg-light p-3 rounded">{{ $record->catatan_tambahan }}</p>
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="alert alert-info mt-4">
                        <small>
                            <i class="bi bi-info-circle"></i> 
                            Rekam medis ini dibuat pada {{ $record->created_at->format('d M Y H:i') }} oleh {{ $record->terapis->name ?? 'Terapis' }}
                        </small>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
.btn-brown {
    background-color: #8B4513;
    color: white;
    border: none;
}
.btn-brown:hover {
    background-color: #6F3609;
    color: white;
}

@media print {
    .btn, .card-header, nav, footer, .alert {
        display: none !important;
    }
    
    #printArea {
        box-shadow: none !important;
        border: none !important;
    }
}
</style>
@endsection