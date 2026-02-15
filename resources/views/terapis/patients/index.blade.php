@extends('layouts.app')

@section('title', 'Daftar Pasien')

@section('content')
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">👥 Daftar Pasien</h2>
            <p class="text-muted">Kelola data pasien Anda</p>
        </div>
        <a href="{{ route('terapis.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-people-fill"></i> Semua Pasien</h5>
        </div>
        <div class="card-body">
            
            {{-- Search Box --}}
            <div class="mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="🔍 Cari nama, NIK, atau email pasien...">
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle" id="patientTable">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Pasien</th>
                            <th>NIK</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Total Kunjungan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pasiens as $index => $pasien)
                            <tr>
                                <td>{{ $pasiens->firstItem() + $index }}</td>
                                <td>
                                    <strong>{{ $pasien->name }}</strong>
                                </td>
                                <td>{{ $pasien->nik ?? '-' }}</td>
                                <td>{{ $pasien->email }}</td>
                                <td>{{ $pasien->phone }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $pasien->patient_histories_count }} kunjungan</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('terapis.patients.show', $pasien->id) }}" class="btn btn-primary" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('terapis.medical-records.create', $pasien->id) }}" class="btn btn-success" title="Input Rekam Medis">
                                            <i class="bi bi-file-medical-fill"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1"></i>
                                    <p>Belum ada pasien</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $pasiens->links() }}
            </div>

        </div>
    </div>

</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<script>
// Enhanced search functionality with NIK
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('#patientTable tbody tr');
    
    tableRows.forEach(row => {
        const name = row.cells[1]?.textContent.toLowerCase() || '';
        const nik = row.cells[2]?.textContent.toLowerCase() || '';
        const email = row.cells[3]?.textContent.toLowerCase() || '';
        
        if (name.includes(searchValue) || nik.includes(searchValue) || email.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
@endsection