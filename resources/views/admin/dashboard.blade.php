@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Admin Dashboard - Kelola Antrian</h2>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-danger btn-sm">Logout</button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- STATISTIK --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center p-3 bg-primary text-white">
                <h3>{{ $statistik['total'] }}</h3>
                <p class="mb-0">Total Antrian</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-3 bg-warning text-dark">
                <h3>{{ $statistik['menunggu'] }}</h3>
                <p class="mb-0">Menunggu</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-3 bg-success text-white">
                <h3>{{ $statistik['selesai'] }}</h3>
                <p class="mb-0">Selesai</p>
            </div>
        </div>
    </div>

    {{-- TABEL ANTRIAN --}}
    <div class="card">
        <div class="card-header bg-brown text-white">
            <h5 class="mb-0">📋 Daftar Semua Antrian</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Pasien</th>
                            <th>Poli</th>
                            <th>Dokter</th>
                            <th>Tanggal</th>
                            <th>Keluhan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($antrians as $antrian)
                            <tr>
                                <td><strong>{{ $antrian->kode_antrian }}</strong></td>
                                <td>{{ $antrian->patient_name }}</td>
                                <td>{{ $antrian->poli }}</td>
                                <td>{{ $antrian->dokter }}</td>
                                <td>{{ $antrian->tanggal }}</td>
                                <td>{{ $antrian->keluhan ?? '-' }}</td>
                                <td>
                                    <span class="badge 
                                        @if($antrian->status == 'Menunggu') bg-warning
                                        @elseif($antrian->status == 'Dipanggil') bg-info
                                        @elseif($antrian->status == 'Selesai') bg-success
                                        @else bg-secondary
                                        @endif
                                    ">
                                        {{ $antrian->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        {{-- Update Status --}}
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal{{ $antrian->id }}">
                                            Ubah Status
                                        </button>
                                        
                                        {{-- Hapus --}}
                                        <form method="POST" action="{{ route('admin.antrian.delete', $antrian->id) }}" onsubmit="return confirm('Yakin hapus antrian ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>

                                    {{-- Modal Update Status --}}
                                    <div class="modal fade" id="updateModal{{ $antrian->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Status Antrian</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="{{ route('admin.antrian.update', $antrian->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <p><strong>Kode:</strong> {{ $antrian->kode_antrian }}</p>
                                                        <p><strong>Pasien:</strong> {{ $antrian->patient_name }}</p>
                                                        
                                                        <label class="form-label">Status Baru:</label>
                                                        <select name="status" class="form-select" required>
                                                            <option value="Menunggu" {{ $antrian->status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                                            <option value="Dipanggil" {{ $antrian->status == 'Dipanggil' ? 'selected' : '' }}>Dipanggil</option>
                                                            <option value="Selesai" {{ $antrian->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                                            <option value="Dibatalkan" {{ $antrian->status == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                                        </select>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Belum ada antrian</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $antrians->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.bg-brown {
    background-color: #8B4513;
}
</style>
@endsection