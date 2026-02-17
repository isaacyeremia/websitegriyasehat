@extends('layouts.app')

@section('title', 'Detail Rekam Medis')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-10 mx-auto">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">📄 Detail Rekam Medis</h2>
                <div>
                    {{-- Tombol Edit hanya muncul jika terapis yang login adalah pembuat rekam medis --}}
                    @if(auth()->id() == $record->terapis_id || auth()->user()->isAdmin())
                        <a href="{{ route('terapis.medical-records.edit', $record->id) }}" class="btn btn-warning me-2">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                    @endif
                    
                    <button onclick="window.print()" class="btn btn-secondary me-2">
                        <i class="bi bi-printer"></i> Print
                    </button>
                    <a href="{{ route('terapis.patients.show', $record->patient_id) }}" class="btn btn-primary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="card shadow" id="printArea">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Rekam Medis Pasien</h4>
                </div>
                <div class="card-body">
                    
                    {{-- Header Info --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Informasi Pasien</h5>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="150">Nama</th>
                                    <td>: {{ $record->patient->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>: {{ $record->patient->email }}</td>
                                </tr>
                                <tr>
                                    <th>Telepon</th>
                                    <td>: {{ $record->patient->phone }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Informasi Pemeriksaan</h5>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="150">Tanggal</th>
                                    <td>: {{ \Carbon\Carbon::parse($record->checkup_date)->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Terapis</th>
                                    <td>: {{ $record->terapis->name }}</td>
                                </tr>
                                @if($record->patientHistory)
                                <tr>
                                    <th>Kode Antrian</th>
                                    <td>: {{ $record->patientHistory->kode_antrian }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <hr>

                    {{-- Anamnesis --}}
                    <div class="mb-4">
                        <h5 class="text-primary">📋 Anamnesis & Keluhan</h5>
                        
                        <div class="mb-3">
                            <strong>Keluhan Utama:</strong>
                            <p class="ms-3">{{ $record->complaint }}</p>
                        </div>

                        @if($record->anamnesis)
                        <div class="mb-3">
                            <strong>Anamnesis Detail:</strong>
                            <p class="ms-3">{{ $record->anamnesis }}</p>
                        </div>
                        @endif

                        @if($record->riwayat_penyakit)
                        <div class="mb-3">
                            <strong>Riwayat Penyakit Dahulu:</strong>
                            <p class="ms-3">{{ $record->riwayat_penyakit }}</p>
                        </div>
                        @endif
                    </div>

                    <hr>

                    {{-- Diagnosis --}}
                    <div class="mb-4">
                        <h5 class="text-success">🔍 Diagnosis</h5>
                        
                        <div class="mb-3">
                            <strong>Diagnosis Utama:</strong>
                            <p class="ms-3">{{ $record->diagnosis }}</p>
                        </div>

                        @if($record->diagnosis_awal)
                        <div class="mb-3">
                            <strong>Diagnosis Awal:</strong>
                            <p class="ms-3">{{ $record->diagnosis_awal }}</p>
                        </div>
                        @endif

                        @if($record->diagnosis_akhir)
                        <div class="mb-3">
                            <strong>Diagnosis Akhir:</strong>
                            <p class="ms-3">{{ $record->diagnosis_akhir }}</p>
                        </div>
                        @endif
                    </div>

                    <hr>

                    {{-- Pengobatan & Tindakan --}}
                    <div class="mb-4">
                        <h5 class="text-info">💊 Pengobatan & Tindakan</h5>
                        
                        @if($record->treatment)
                        <div class="mb-3">
                            <strong>Tindakan Medis:</strong>
                            <p class="ms-3">{{ $record->treatment }}</p>
                        </div>
                        @endif

                        @if($record->pengobatan)
                        <div class="mb-3">
                            <strong>Detail Pengobatan:</strong>
                            <p class="ms-3">{{ $record->pengobatan }}</p>
                        </div>
                        @endif

                        @if($record->medicine)
                        <div class="mb-3">
                            <strong>Obat yang Diberikan:</strong>
                            <p class="ms-3" style="white-space: pre-line;">{{ $record->medicine }}</p>
                        </div>
                        @endif

                        @if($record->obat_diberikan)
                        <div class="mb-3">
                            <strong>Obat Tambahan:</strong>
                            <p class="ms-3" style="white-space: pre-line;">{{ $record->obat_diberikan }}</p>
                        </div>
                        @endif
                    </div>

                    <hr>

                    {{-- Catatan --}}
                    <div class="mb-4">
                        <h5 class="text-warning">📝 Catatan</h5>
                        
                        @if($record->doctor_note)
                        <div class="mb-3">
                            <strong>Catatan Terapis:</strong>
                            <p class="ms-3">{{ $record->doctor_note }}</p>
                        </div>
                        @endif

                        @if($record->catatan_tambahan)
                        <div class="mb-3">
                            <strong>Catatan Tambahan:</strong>
                            <p class="ms-3">{{ $record->catatan_tambahan }}</p>
                        </div>
                        @endif
                    </div>

                    <hr>

                    <div class="text-muted small">
                        <p class="mb-0">Dibuat pada: {{ $record->created_at->format('d M Y H:i') }}</p>
                        @if($record->updated_at != $record->created_at)
                        <p class="mb-0">Terakhir diupdate: {{ $record->updated_at->format('d M Y H:i') }}</p>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
@media print {
    .btn, .card-header, nav, footer {
        display: none !important;
    }
    
    #printArea {
        box-shadow: none !important;
        border: none !important;
    }
}
</style>
@endsection