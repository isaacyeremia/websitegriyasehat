@extends('layouts.app')

@section('title', 'Input Rekam Medis')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-10 mx-auto">
            
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="bi bi-file-medical-fill"></i> Input Rekam Medis</h4>
                </div>
                <div class="card-body">
                    
                    {{-- Info Pasien --}}
                    <div class="alert alert-info">
                        <strong>Pasien:</strong> {{ $pasien->name }}<br>
                        <strong>Email:</strong> {{ $pasien->email }}<br>
                        <strong>Telepon:</strong> {{ $pasien->phone }}
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('terapis.medical-records.store', $pasien->id) }}">
                        @csrf

                        {{-- Pilih Kunjungan (Opsional) --}}
                        <div class="mb-3">
                            <label class="form-label">Kunjungan Terkait (Opsional)</label>
                            <select name="queue_id" class="form-select">
                                <option value="">-- Pilih Kunjungan --</option>
                                @foreach($riwayat_kunjungan as $kunjungan)
                                    <option value="{{ $kunjungan->id }}">
                                        {{ $kunjungan->kode_antrian }} - {{ $kunjungan->poli }} ({{ \Carbon\Carbon::parse($kunjungan->tanggal)->format('d M Y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tanggal Pemeriksaan --}}
                        <div class="mb-3">
                            <label class="form-label">Tanggal Pemeriksaan <span class="text-danger">*</span></label>
                            <input type="date" name="checkup_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <hr>
                        <h5 class="mb-3">📋 Anamnesis & Keluhan</h5>

                        {{-- Keluhan Utama --}}
                        <div class="mb-3">
                            <label class="form-label">Keluhan Utama <span class="text-danger">*</span></label>
                            <textarea name="complaint" class="form-control" rows="3" required placeholder="Keluhan utama pasien...">{{ old('complaint') }}</textarea>
                        </div>

                        {{-- Anamnesis Detail --}}
                        <div class="mb-3">
                            <label class="form-label">Anamnesis Detail</label>
                            <textarea name="anamnesis" class="form-control" rows="4" placeholder="Detail anamnesis pasien...">{{ old('anamnesis') }}</textarea>
                            <small class="text-muted">Riwayat penyakit sekarang, onset, durasi, dll.</small>
                        </div>

                        {{-- Riwayat Penyakit --}}
                        <div class="mb-3">
                            <label class="form-label">Riwayat Penyakit Dahulu</label>
                            <textarea name="riwayat_penyakit" class="form-control" rows="3" placeholder="Riwayat penyakit yang pernah diderita...">{{ old('riwayat_penyakit') }}</textarea>
                        </div>

                        <hr>
                        <h5 class="mb-3">🔍 Diagnosis</h5>

                        {{-- Diagnosis Utama --}}
                        <div class="mb-3">
                            <label class="form-label">Diagnosis <span class="text-danger">*</span></label>
                            <input type="text" name="diagnosis" class="form-control" required placeholder="Diagnosis utama" value="{{ old('diagnosis') }}">
                        </div>

                        {{-- Diagnosis Awal --}}
                        <div class="mb-3">
                            <label class="form-label">Diagnosis Awal</label>
                            <input type="text" name="diagnosis_awal" class="form-control" placeholder="Diagnosis saat pertama kali diperiksa" value="{{ old('diagnosis_awal') }}">
                        </div>

                        {{-- Diagnosis Akhir --}}
                        <div class="mb-3">
                            <label class="form-label">Diagnosis Akhir</label>
                            <input type="text" name="diagnosis_akhir" class="form-control" placeholder="Diagnosis setelah pemeriksaan lengkap" value="{{ old('diagnosis_akhir') }}">
                        </div>

                        <hr>
                        <h5 class="mb-3">💊 Pengobatan & Tindakan</h5>

                        {{-- Tindakan Medis --}}
                        <div class="mb-3">
                            <label class="form-label">Tindakan Medis</label>
                            <textarea name="treatment" class="form-control" rows="4" placeholder="Tindakan medis yang dilakukan...">{{ old('treatment') }}</textarea>
                            <small class="text-muted">Contoh: Akupunktur, bekam, pijat terapi, dll.</small>
                        </div>

                        {{-- Pengobatan Detail --}}
                        <div class="mb-3">
                            <label class="form-label">Detail Pengobatan</label>
                            <textarea name="pengobatan" class="form-control" rows="3" placeholder="Detail pengobatan yang diberikan...">{{ old('pengobatan') }}</textarea>
                        </div>

                        {{-- Obat yang Diberikan --}}
                        <div class="mb-3">
                            <label class="form-label">Obat yang Diberikan</label>
                            <textarea name="medicine" class="form-control" rows="3" placeholder="Nama obat, dosis, aturan pakai...">{{ old('medicine') }}</textarea>
                        </div>

                        {{-- Obat Detail --}}
                        <div class="mb-3">
                            <label class="form-label">Detail Obat Tambahan</label>
                            <textarea name="obat_diberikan" class="form-control" rows="3" placeholder="Obat herbal atau suplemen tambahan...">{{ old('obat_diberikan') }}</textarea>
                        </div>

                        <hr>
                        <h5 class="mb-3">📝 Catatan</h5>

                        {{-- Catatan Dokter --}}
                        <div class="mb-3">
                            <label class="form-label">Catatan Terapis</label>
                            <textarea name="doctor_note" class="form-control" rows="3" placeholder="Catatan penting untuk pasien...">{{ old('doctor_note') }}</textarea>
                        </div>

                        {{-- Catatan Tambahan --}}
                        <div class="mb-3">
                            <label class="form-label">Catatan Tambahan</label>
                            <textarea name="catatan_tambahan" class="form-control" rows="3" placeholder="Catatan lain yang perlu dicatat...">{{ old('catatan_tambahan') }}</textarea>
                        </div>

                        <hr>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Simpan Rekam Medis
                            </button>
                            <a href="{{ route('terapis.patients.show', $pasien->id) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection