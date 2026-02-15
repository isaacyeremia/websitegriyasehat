@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container py-5">

    <h2 class="text-center fw-bold mb-2">Profil Saya</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">

        {{-- DATA PROFIL --}}
        <div class="col-md-6">
            <div class="card p-4 mb-4 shadow-sm">
                <h5 class="fw-bold mb-3">Data Profil</h5>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NIK/KTP</label>
                        <input type="text" 
                               name="nik" 
                               class="form-control" 
                               value="{{ old('nik', $user->nik) }}" 
                               maxlength="16" 
                               pattern="[0-9]{16}"
                               placeholder="16 digit NIK">
                        @error('nik')<div class="text-danger small">{{ $message }}</div>@enderror
                        <small class="text-muted">16 digit NIK sesuai KTP</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required>
                        @error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control" rows="3">{{ old('address', $user->address) }}</textarea>
                        @error('address')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-brown w-100">Simpan Perubahan</button>

                </form>
            </div>
        </div>

        {{-- RIWAYAT PASIEN --}}
        <div class="col-md-6">
            <div class="card p-4 mb-4 shadow-sm">
                <h5 class="fw-bold mb-3">Riwayat Pasien</h5>

                @if($histories->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>NIK</th>
                                    <th>Tanggal</th>
                                    <th>Layanan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($histories as $h)
                                <tr>
                                    <td>{{ $h->patient_name }}</td>
                                    <td>{{ $h->patient_nik ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($h->tanggal)->format('d M Y') }}</td>
                                    <td>{{ $h->poli }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($h->status == 'Menunggu') bg-warning text-dark
                                            @elseif($h->status == 'Selesai') bg-success
                                            @else bg-info
                                            @endif
                                        ">
                                            {{ $h->status }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">Belum ada riwayat pasien</p>
                @endif
            </div>
        </div>

    </div>

    {{-- REKAM MEDIS --}}
    <div class="card p-4 shadow-sm">
        <h5 class="fw-bold mb-3">📋 Rekam Medis Saya</h5>

        @if($rekam_medis->count() > 0)
            <div class="row">
                @foreach($rekam_medis as $record)
                    <div class="col-md-6 mb-3">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <div class="d-flex justify-content-between">
                                    <strong>
                                        {{ $record->checkup_date ? \Carbon\Carbon::parse($record->checkup_date)->format('d M Y') : \Carbon\Carbon::parse($record->created_at)->format('d M Y') }}
                                    </strong>
                                    <small>oleh {{ $record->terapis->name ?? 'Terapis' }}</small>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="mb-2"><strong>NIK:</strong> {{ $user->nik ?? '-' }}</p>
                                <p class="mb-2"><strong>Keluhan:</strong> {{ Str::limit($record->complaint, 100) }}</p>
                                <p class="mb-2"><strong>Diagnosis:</strong> {{ $record->diagnosis }}</p>
                                
                                @if($record->medicine)
                                <p class="mb-2"><strong>Obat:</strong> {{ Str::limit($record->medicine, 80) }}</p>
                                @endif

                                <a href="{{ route('profile.medical-record.show', $record->id) }}" class="btn btn-sm btn-outline-success mt-2">
                                    <i class="bi bi-eye"></i> Lihat Detail Lengkap
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted text-center">Belum ada rekam medis</p>
        @endif
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
</style>

@push('scripts')
<script>
// Validasi NIK hanya angka
document.querySelector('input[name="nik"]')?.addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});
</script>
@endpush
@endsection