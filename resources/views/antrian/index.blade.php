@extends('layouts.app')

@section('title', 'Antrian Online')

@section('content')
<div class="container py-5">

    <h2 class="text-center fw-bold mb-2">📋 Layanan Antrian Online</h2>
    <p class="text-center text-muted mb-5">
        Ambil nomor antrian dan pantau secara real-time tanpa perlu menunggu lama
    </p>

    {{-- ALERT STATUS ANTRIAN USER HARI INI --}}
    @if(isset($userTodayQueue))
        <div class="alert alert-warning alert-dismissible fade show mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle fs-3 me-3"></i>
                <div>
                    <strong>Antrian Anda Hari Ini:</strong> 
                    <span class="fs-5 fw-bold text-dark">{{ $userTodayQueue->kode_antrian }}</span>
                    <br>
                    <small>Status: <strong>{{ $userTodayQueue->status }}</strong> | 
                    Dokter: <strong>{{ $userTodayQueue->dokter }}</strong> | 
                    Jam: <strong>{{ \Carbon\Carbon::parse($userTodayQueue->appointment_time)->format('H:i') }}</strong></small>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ALERT STATUS ANTRIAN SAAT INI DIPANGGIL --}}
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

                        {{-- Pilih Tanggal (PRIORITAS PERTAMA) --}}
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
                                   value="{{ old('tanggal') }}">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> Pilih tanggal terlebih dahulu untuk melihat dokter yang tersedia
                            </small>
                        </div>

                        {{-- Pilih Dokter (MUNCUL SETELAH TANGGAL) --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-person-badge"></i> Pilih Dokter/Terapis 
                                <span class="text-danger">*</span>
                            </label>
                            <select name="dokter" class="form-select form-select-lg" required id="selectDokter" disabled>
                                <option value="">-- Pilih tanggal terlebih dahulu --</option>
                            </select>
                            
                            {{-- Info Jadwal & Kuota Dokter --}}
                            <div id="doctorInfo" class="mt-2" style="display: none;">
                                <div class="alert alert-info mb-0">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong><i class="bi bi-clock"></i> Jam Praktek:</strong>
                                            <div id="practiceHours"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <strong><i class="bi bi-people"></i> Kuota Tersedia:</strong>
                                            <div id="quotaInfo"></div>
                                        </div>
                                    </div>
                                </div>
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
                                <i class="bi bi-info-circle"></i> Pilih dokter terlebih dahulu
                            </small>
                            <div id="bookedSlotsWarning" class="alert alert-warning mt-2" style="display: none;">
                                <small><i class="bi bi-exclamation-triangle"></i> <span id="bookedSlotsText"></span></small>
                            </div>
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
                        <li class="mb-2"><strong>Pilih tanggal kunjungan</strong></li>
                        <li class="mb-2">Sistem akan menampilkan dokter yang tersedia di tanggal tersebut</li>
                        <li class="mb-2">Pilih dokter (lihat kuota tersedia)</li>
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
                            <th>Nama</th>
                            <th>NIK</th>
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
                        <td>{{ $queue->patient_name }}</td>
                        <td>{{ $queue->patient_nik ?? '-' }}</td>
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
    const selectTanggal = document.getElementById('selectTanggal');
    const selectDokter = document.getElementById('selectDokter');
    const selectTime = document.getElementById('selectTime');
    const doctorInfo = document.getElementById('doctorInfo');
    const practiceHours = document.getElementById('practiceHours');
    const quotaInfo = document.getElementById('quotaInfo');
    const timeRangeInfo = document.getElementById('timeRangeInfo');
    const submitBtn = document.getElementById('submitBtn');
    const bookedSlotsWarning = document.getElementById('bookedSlotsWarning');
    const bookedSlotsText = document.getElementById('bookedSlotsText');

    let currentDoctorData = null;
    let bookedSlots = [];

    // Event: Saat tanggal dipilih - LOAD AVAILABLE DOCTORS
    selectTanggal.addEventListener('change', function() {
        const selectedDate = this.value;
        
        // Reset
        selectDokter.innerHTML = '<option value="">-- Memuat dokter tersedia... --</option>';
        selectDokter.disabled = true;
        selectTime.value = '';
        selectTime.disabled = true;
        submitBtn.disabled = true;
        doctorInfo.style.display = 'none';
        bookedSlotsWarning.style.display = 'none';

        if (!selectedDate) {
            selectDokter.innerHTML = '<option value="">-- Pilih tanggal terlebih dahulu --</option>';
            return;
        }

        // Fetch available doctors untuk tanggal ini
        fetch(`/api/available-doctors/${selectedDate}`)
            .then(response => response.json())
            .then(doctors => {
                selectDokter.innerHTML = '<option value="">-- Pilih Dokter/Terapis --</option>';
                
                if (doctors.length === 0) {
                    selectDokter.innerHTML = '<option value="">-- Tidak ada dokter tersedia di tanggal ini --</option>';
                    selectDokter.disabled = true;
                    return;
                }

                doctors.forEach(doctor => {
                    const option = document.createElement('option');
                    option.value = doctor.name;
                    option.setAttribute('data-doctor-id', doctor.id);
                    option.setAttribute('data-start-time', doctor.start_time);
                    option.setAttribute('data-end-time', doctor.end_time);
                    option.setAttribute('data-quota-left', doctor.quota_left);
                    option.setAttribute('data-total-quota', doctor.total_quota);
                    option.textContent = `${doctor.name} - ${doctor.specialization} (Kuota: ${doctor.quota_left}/${doctor.total_quota})`;
                    selectDokter.appendChild(option);
                });

                selectDokter.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat data dokter. Silakan coba lagi.');
                selectDokter.innerHTML = '<option value="">-- Error loading doctors --</option>';
            });
    });

    // Event: Saat dokter dipilih
    selectDokter.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        selectTime.value = '';
        selectTime.disabled = true;
        submitBtn.disabled = true;
        bookedSlotsWarning.style.display = 'none';

        if (!this.value) {
            doctorInfo.style.display = 'none';
            timeRangeInfo.innerHTML = '<i class="bi bi-info-circle"></i> Pilih dokter terlebih dahulu';
            return;
        }

        const startTime = selectedOption.getAttribute('data-start-time');
        const endTime = selectedOption.getAttribute('data-end-time');
        const quotaLeft = selectedOption.getAttribute('data-quota-left');
        const totalQuota = selectedOption.getAttribute('data-total-quota');

        // Tampilkan info dokter
        practiceHours.innerHTML = `${startTime} - ${endTime}`;
        quotaInfo.innerHTML = `<span class="badge ${quotaLeft > 5 ? 'bg-success' : 'bg-warning text-dark'}">${quotaLeft}/${totalQuota} tersedia</span>`;
        doctorInfo.style.display = 'block';

        // Set time constraints
        selectTime.min = startTime;
        selectTime.max = endTime;
        selectTime.disabled = false;
        timeRangeInfo.innerHTML = `<i class="bi bi-info-circle"></i> Jam praktek: ${startTime} - ${endTime}`;

        currentDoctorData = {
            name: this.value,
            startTime: startTime,
            endTime: endTime
        };

        // Fetch booked slots
        const selectedDate = selectTanggal.value;
        fetch(`/api/booked-slots/${encodeURIComponent(this.value)}/${selectedDate}`)
            .then(response => response.json())
            .then(slots => {
                bookedSlots = slots;
                if (slots.length > 0) {
                    bookedSlotsText.innerHTML = `Jam yang sudah dibooking: <strong>${slots.join(', ')}</strong>`;
                    bookedSlotsWarning.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error fetching booked slots:', error);
            });
    });

    // Event: Saat jam dipilih - validate
    selectTime.addEventListener('change', function() {
        const selectedTime = this.value;
        
        if (!selectedTime || !currentDoctorData) {
            submitBtn.disabled = true;
            return;
        }

        // Validasi apakah jam dalam range
        if (selectedTime < currentDoctorData.startTime || selectedTime > currentDoctorData.endTime) {
            alert(`Jam harus antara ${currentDoctorData.startTime} - ${currentDoctorData.endTime}`);
            this.value = '';
            submitBtn.disabled = true;
            return;
        }

        // Validasi apakah jam sudah dibooking
        if (bookedSlots.includes(selectedTime)) {
            alert(`Jam ${selectedTime} sudah dibooking oleh pasien lain. Silakan pilih jam lain.`);
            this.value = '';
            submitBtn.disabled = true;
            return;
        }

        submitBtn.disabled = false;
    });

    // Form validation before submit
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        const selectedDate = selectTanggal.value;
        const selectedTime = selectTime.value;
        const selectedDokter = selectDokter.value;

        if (!selectedDate || !selectedTime || !selectedDokter) {
            e.preventDefault();
            alert('Mohon lengkapi semua data');
            return false;
        }

        // Final validation
        if (currentDoctorData) {
            if (selectedTime < currentDoctorData.startTime || selectedTime > currentDoctorData.endTime) {
                e.preventDefault();
                alert(`Jam kunjungan harus antara ${currentDoctorData.startTime} - ${currentDoctorData.endTime}`);
                return false;
            }
        }

        if (bookedSlots.includes(selectedTime)) {
            e.preventDefault();
            alert('Jam yang Anda pilih sudah dibooking oleh pasien lain. Silakan refresh halaman dan pilih jam lain.');
            return false;
        }
    });
});
</script>
@endpush