@extends('layouts.app')

@section('title', 'Tambah Jadwal Praktek')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">➕ Tambah Jadwal Praktek</h2>
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
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

            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-plus"></i> Form Tambah Jadwal Praktek</h5>
                </div>
                <div class="card-body">
                    
                    <form method="POST" action="{{ route('admin.schedules.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Dokter/Terapis <span class="text-danger">*</span></label>
                            <select name="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Dokter/Terapis --</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hari Praktek <span class="text-danger">*</span></label>
                            <select name="day_of_week" class="form-select @error('day_of_week') is-invalid @enderror" required>
                                <option value="">-- Pilih Hari --</option>
                                @foreach($days as $day)
                                    <option value="{{ $day }}" {{ old('day_of_week') == $day ? 'selected' : '' }}>
                                        {{ $day }}
                                    </option>
                                @endforeach
                            </select>
                            @error('day_of_week')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Pastikan tidak ada jadwal duplikat untuk dokter di hari yang sama</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                                <input type="time" 
                                       name="start_time" 
                                       class="form-control @error('start_time') is-invalid @enderror" 
                                       value="{{ old('start_time', '08:00') }}" 
                                       required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                <input type="time" 
                                       name="end_time" 
                                       class="form-control @error('end_time') is-invalid @enderror" 
                                       value="{{ old('end_time', '17:00') }}" 
                                       required>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kuota Pasien Per Hari <span class="text-danger">*</span></label>
                            <input type="number" 
                                   name="quota" 
                                   class="form-control @error('quota') is-invalid @enderror" 
                                   value="{{ old('quota', 10) }}" 
                                   min="1" 
                                   max="100"
                                   required>
                            <small class="text-muted">Maksimal pasien yang bisa booking per hari (1-100)</small>
                            @error('quota')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="is_active" 
                                       id="is_active" 
                                       value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Aktifkan jadwal ini
                                </label>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Simpan Jadwal
                            </button>
                            <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">
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