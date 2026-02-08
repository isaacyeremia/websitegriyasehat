@extends('layouts.app')

@section('title','Antrian Online')

@section('content')

<div class="container py-5">

    <h2 class="text-center fw-bold mb-2">Layanan Antrian Online</h2>
    <p class="text-center text-muted mb-5">
        Ambil nomor antrian dan pantau secara real-time tanpa perlu menunggu lama
    </p>

    <div class="row">

        {{-- ANTRIAN SAAT INI --}}
        <div class="col-md-5">
            <div class="card p-4 mb-4 shadow-sm">
                <h5 class="fw-bold mb-3">📌 Antrian Saat Ini</h5>

                @if($antrianSekarang)
                    <div class="text-center">
                        <h1 class="display-4 fw-bold text-danger">
                            {{ $antrianSekarang->kode_antrian }}
                        </h1>

                        <p class="mb-1">
                            <strong>Layanan:</strong> {{ $antrianSekarang->poli }} <br>
                            <strong>Dokter:</strong> {{ $antrianSekarang->dokter }} <br>
                            <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($antrianSekarang->tanggal)->format('d M Y') }}
                        </p>
                        
                        <span class="badge bg-warning text-dark fs-6 mt-2">
                            {{ $antrianSekarang->status }}
                        </span>
                    </div>
                @else
                    <p class="text-muted text-center">Belum ada antrian aktif</p>
                @endif
            </div>
        </div>

        {{-- AMBIL ANTRIAN --}}
        <div class="col-md-7">
            <div class="card p-4 mb-4 shadow-sm">
                <h5 class="fw-bold mb-3">📝 Ambil Antrian Baru</h5>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('booking.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Layanan <span class="text-danger">*</span></label>
                        <select name="layanan" class="form-select" required>
                            <option value="">-- Pilih Layanan --</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                        @error('layanan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Dokter <span class="text-danger">*</span></label>
                        <select name="dokter" class="form-select" required id="selectDokter">
                            <option value="">-- Pilih Dokter --</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" data-schedule="{{ $doctor->schedule }}">
                                    {{ $doctor->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted" id="doctorSchedule"></small>
                        @error('dokter')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control" required min="{{ date('Y-m-d') }}">
                        @error('tanggal')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keluhan (opsional)</label>
                        <textarea name="keluhan" class="form-control" rows="3" placeholder="Jelaskan keluhan Anda..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-brown w-100">
                        <i class="bi bi-plus-circle"></i> Ambil Nomor Antrian
                    </button>

                </form>
            </div>
        </div>
    </div>

    {{-- RIWAYAT --}}
    <div class="card p-4 shadow-sm">
        <h5 class="fw-bold mb-3">📜 Riwayat Antrian Saya</h5>

        @if($riwayat->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Layanan</th>
                            <th>Dokter</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riwayat as $data)
                            <tr>
                                <td><strong>{{ $data->kode_antrian }}</strong></td>
                                <td>{{ $data->poli }}</td>
                                <td>{{ $data->dokter }}</td>
                                <td>{{ \Carbon\Carbon::parse($data->tanggal)->format('d M Y') }}</td>
                                <td>
                                    <span class="badge 
                                        @if($data->status == 'Menunggu') bg-warning text-dark
                                        @elseif($data->status == 'Dipanggil') bg-info
                                        @elseif($data->status == 'Selesai') bg-success
                                        @else bg-secondary
                                        @endif
                                    ">
                                        {{ $data->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted text-center">Belum ada riwayat antrian</p>
        @endif
    </div>

</div>

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

@endsection

@push('scripts')
<script>
// Tampilkan jadwal dokter saat dipilih
document.getElementById('selectDokter').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const schedule = selectedOption.getAttribute('data-schedule');
    const scheduleDisplay = document.getElementById('doctorSchedule');
    
    if (schedule) {
        scheduleDisplay.textContent = '📅 Jadwal Praktik: ' + schedule;
    } else {
        scheduleDisplay.textContent = '';
    }
});
</script>
@endpush