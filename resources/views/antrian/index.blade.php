@extends('layouts.app')

@section('title', 'Antrian Online')

@section('content')
<div class="container py-5">

    <h2 class="text-center fw-bold mb-2">📋 Layanan Antrian Online</h2>
    <p class="text-center text-muted mb-5">
        Ambil nomor antrian dan pantau secara real-time tanpa perlu menunggu lama
    </p>

    {{-- ALERT STATUS ANTRIAN SAAT INI --}}
    @if($currentQueueNumber)
        <div class="alert alert-info alert-dismissible fade show mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-megaphone fs-3 me-3"></i>
                <div>
                    <strong>Antrian Saat Ini Dipanggil:</strong> 
                    <span class="fs-5 fw-bold text-primary">{{ $currentQueueNumber }}</span>
                    <br>
                    <small>Jumlah antrian menunggu: <strong>{{ $waitingCount }}</strong> orang</small>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">

        {{-- FORM BOOKING ANTRIAN --}}
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-plus"></i> Ambil Nomor Antrian</h5>
                </div>
                <div class="card-body p-4">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('booking.store') }}" id="bookingForm">
                        @csrf

                        {{-- Pilih Layanan --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-hospital"></i> Layanan/Poli 
                                <span class="text-danger">*</span>
                            </label>
                            <select name="poli" class="form-select form-select-lg" required>
                                <option value="">-- Pilih Layanan --</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->name }}" {{ old('poli') == $service->name ? 'selected' : '' }}>
                                        {{ $service->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Pilih Dokter --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-person-badge"></i> Pilih Dokter/Terapis 
                                <span class="text-danger">*</span>
                            </label>
                            <select name="dokter" class="form-select form-select-lg" required id="selectDokter">
                                <option value="">-- Pilih Dokter/Terapis --</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->name }}" 
                                            data-doctor-id="{{ $doctor->id }}"
                                            {{ old('dokter') == $doctor->name ? 'selected' : '' }}>
                                        {{ $doctor->name }} - {{ $doctor->specialization }}
                                    </option>
                                @endforeach
                            </select>
                            
                            {{-- Info Jadwal Dokter --}}
                            <div id="doctorScheduleInfo" class="mt-2" style="display: none;">
                                <div class="alert alert-info mb-0">
                                    <strong><i class="bi bi-calendar-week"></i> Jadwal Praktek:</strong>
                                    <div id="scheduleList" class="mt-2"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Pilih Tanggal (DATE PICKER) --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-calendar-event"></i> Tanggal Kunjungan 
                                <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   name="tanggal" 
                                   class="form-control form-control-lg" 
                                   required 
                                   id="selectTanggal"
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ old('tanggal') }}"
                                   disabled>
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> Pilih dokter terlebih dahulu untuk melihat tanggal yang tersedia
                            </small>
                            <div id="dateError" class="text-danger mt-1" style="display: none;">
                                <small>Tanggal ini tidak sesuai jadwal praktek dokter</small>
                            </div>
                        </div>

                        {{-- Pilih Jam (TIME PICKER) --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-clock"></i> Jam Kunjungan 
                                <span class="text-danger">*</span>
                            </label>
                            <input type="time" 
                                   name="appointment_time" 
                                   class="form-control form-control-lg" 
                                   required 
                                   id="selectTime"
                                   value="{{ old('appointment_time') }}"
                                   disabled>
                            <small class="text-muted" id="timeRangeInfo">
                                <i class="bi bi-info-circle"></i> Pilih tanggal terlebih dahulu
                            </small>
                        </div>

                        {{-- Keluhan --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-chat-left-text"></i> Keluhan (Opsional)
                            </label>
                            <textarea name="keluhan" 
                                      class="form-control" 
                                      rows="4" 
                                      placeholder="Jelaskan keluhan atau gejala yang Anda alami...">{{ old('keluhan') }}</textarea>
                        </div>

                        <hr>

                        <button type="submit" class="btn btn-primary btn-lg w-100" id="submitBtn" disabled>
                            <i class="bi bi-plus-circle"></i> Ambil Nomor Antrian
                        </button>

                    </form>

                </div>
            </div>
        </div>

        {{-- SIDEBAR INFO --}}
        <div class="col-lg-4">
            
            {{-- Info Antrian Hari Ini --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Info Antrian Hari Ini</h6>
                </div>
                <div class="card-body">
                    @if($currentQueueNumber)
                        <div class="text-center mb-3">
                            <p class="mb-1 text-muted">Nomor Antrian Dipanggil:</p>
                            <h2 class="display-4 fw-bold text-danger mb-0">{{ $currentQueueNumber }}</h2>
                        </div>
                        <hr>
                    @endif
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="bi bi-hourglass-split"></i> Menunggu:</span>
                        <strong>{{ $waitingCount }} orang</strong>
                    </div>
                </div>
            </div>

            {{-- Panduan --}}
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="bi bi-lightbulb"></i> Panduan Booking</h6>
                </div>
                <div class="card-body">
                    <ol class="mb-0 ps-3">
                        <li class="mb-2">Pilih layanan/poli yang dibutuhkan</li>
                        <li class="mb-2">Pilih dokter/terapis</li>
                        <li class="mb-2">Sistem akan menampilkan jadwal praktek</li>
                        <li class="mb-2">Pilih tanggal sesuai jadwal praktek</li>
                        <li class="mb-2">Pilih jam kunjungan sesuai jam praktek</li>
                        <li class="mb-2">Jelaskan keluhan (opsional)</li>
                        <li>Klik tombol "Ambil Nomor Antrian"</li>
                    </ol>
                </div>
            </div>

        </div>
    </div>

{{-- RIWAYAT ANTRIAN --}}
<div class="card shadow-sm mt-4">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Antrian Saya</h5>
    </div>
    <div class="card-body">
        @if($userQueues->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Antrian</th>
                            <th>Layanan</th>
                            <th>Dokter</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Status</th>
                            <th>Kedatangan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($userQueues as $queue)
                            <tr>
                                <td><strong class="text-primary">{{ $queue->kode_antrian }}</strong></td>
                                <td>{{ $queue->poli }}</td>
                                <td>{{ $queue->dokter }}</td>
                                <td>{{ \Carbon\Carbon::parse($queue->tanggal)->format('d M Y') }}</td>
                                <td>
                                    @if($queue->appointment_time)
                                        <i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($queue->appointment_time)->format('H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($queue->status == 'Menunggu') bg-warning text-dark
                                        @elseif($queue->status == 'Dipanggil') bg-info
                                        @elseif($queue->status == 'Selesai') bg-success
                                        @else bg-secondary
                                        @endif
                                    ">
                                        {{ $queue->status }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($queue->arrival_status == 'Belum Hadir') bg-secondary
                                        @elseif($queue->arrival_status == 'Sudah Hadir') bg-success
                                        @else bg-danger
                                        @endif
                                    ">
                                        {{ $queue->arrival_status }}
                                    </span>
                                    @if($queue->confirmed_at)
                                        <br><small class="text-muted">{{ \Carbon\Carbon::parse($queue->confirmed_at)->format('H:i') }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($queue->status == 'Menunggu' && $queue->tanggal == now()->toDateString())
                                        @if($queue->arrival_status == 'Belum Hadir')
                                            <form method="POST" action="{{ route('booking.confirm-arrival', $queue->id) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" title="Konfirmasi Kedatangan">
                                                    <i class="bi bi-check-circle"></i> Konfirmasi Hadir
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('booking.cancel-arrival', $queue->id) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning" title="Batalkan Konfirmasi">
                                                    <i class="bi bi-x-circle"></i> Batal
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center text-muted py-4">
                <i class="bi bi-inbox fs-1"></i>
                <p class="mb-0">Belum ada riwayat antrian</p>
            </div>
        @endif
    </div>
</div>

</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectDokter = document.getElementById('selectDokter');
    const selectTanggal = document.getElementById('selectTanggal');
    const selectTime = document.getElementById('selectTime');
    const doctorScheduleInfo = document.getElementById('doctorScheduleInfo');
    const scheduleList = document.getElementById('scheduleList');
    const dateError = document.getElementById('dateError');
    const timeRangeInfo = document.getElementById('timeRangeInfo');
    const submitBtn = document.getElementById('submitBtn');

    let currentSchedules = [];
    let availableDates = [];

    // Event: Saat dokter dipilih
    selectDokter.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const doctorId = selectedOption.getAttribute('data-doctor-id');

        // Reset
        selectTanggal.value = '';
        selectTanggal.disabled = true;
        selectTime.value = '';
        selectTime.disabled = true;
        submitBtn.disabled = true;
        dateError.style.display = 'none';

        if (!doctorId) {
            doctorScheduleInfo.style.display = 'none';
            return;
        }

        // Fetch jadwal dokter
        fetch(`/api/doctor/${doctorId}/schedule`)
            .then(response => response.json())
            .then(schedules => {
                currentSchedules = schedules;

                // Tampilkan info jadwal
                if (schedules.length > 0) {
                    let scheduleHTML = '<ul class="mb-0">';
                    schedules.forEach(schedule => {
                        scheduleHTML += `<li><strong>${schedule.day_of_week}</strong>: ${schedule.start_time.substring(0, 5)} - ${schedule.end_time.substring(0, 5)}</li>`;
                    });
                    scheduleHTML += '</ul>';
                    scheduleList.innerHTML = scheduleHTML;
                    doctorScheduleInfo.style.display = 'block';

                    // Enable date picker
                    selectTanggal.disabled = false;
                } else {
                    doctorScheduleInfo.style.display = 'none';
                    alert('Dokter ini belum memiliki jadwal praktek');
                }

                // Fetch tanggal tersedia
                return fetch(`/api/doctor/${doctorId}/available-dates`);
            })
            .then(response => response.json())
            .then(dates => {
                availableDates = dates.map(d => d.date);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat data. Silakan coba lagi.');
            });
    });

    // Event: Saat tanggal dipilih
    selectTanggal.addEventListener('change', function() {
        const selectedDate = this.value;
        selectTime.value = '';
        selectTime.disabled = true;
        dateError.style.display = 'none';
        submitBtn.disabled = true;

        if (!selectedDate) {
            return;
        }

        // Validasi tanggal apakah sesuai jadwal
        const date = new Date(selectedDate);
        const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const dayName = dayNames[date.getDay()];

        const schedule = currentSchedules.find(s => s.day_of_week === dayName);

        if (!schedule) {
            dateError.style.display = 'block';
            timeRangeInfo.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Dokter tidak praktek di hari ini';
            return;
        }

        // Check if date is in available dates
        if (!availableDates.includes(selectedDate)) {
            dateError.style.display = 'block';
            timeRangeInfo.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Kuota penuh untuk tanggal ini';
            return;
        }

        // Set time range info
        const startTime = schedule.start_time.substring(0, 5);
        const endTime = schedule.end_time.substring(0, 5);
        
        selectTime.disabled = false;
        selectTime.min = startTime;
        selectTime.max = endTime;
        
        timeRangeInfo.innerHTML = `<i class="bi bi-info-circle"></i> Jam praktek: ${startTime} - ${endTime}`;
    });

    // Event: Saat jam dipilih
    selectTime.addEventListener('change', function() {
        if (this.value && selectTanggal.value && selectDokter.value) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    });

    // Form validation before submit
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        const selectedDate = selectTanggal.value;
        const selectedTime = selectTime.value;

        if (!selectedDate || !selectedTime) {
            e.preventDefault();
            alert('Mohon lengkapi semua data');
            return false;
        }

        // Validate time is within schedule
        const date = new Date(selectedDate);
        const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const dayName = dayNames[date.getDay()];
        const schedule = currentSchedules.find(s => s.day_of_week === dayName);

        if (schedule) {
            const startTime = schedule.start_time.substring(0, 5);
            const endTime = schedule.end_time.substring(0, 5);

            if (selectedTime < startTime || selectedTime > endTime) {
                e.preventDefault();
                alert(`Jam kunjungan harus antara ${startTime} - ${endTime}`);
                return false;
            }
        }
    });
});
</script>
@endpush