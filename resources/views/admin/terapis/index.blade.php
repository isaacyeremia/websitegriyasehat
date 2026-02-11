@extends('layouts.app')

@section('title', 'Manajemen Terapis')

@section('content')
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">👨‍⚕️ Manajemen Terapis</h2>
            <p class="text-muted">Kelola akun terapis</p>
        </div>
        <div>
            <a href="{{ route('admin.terapis.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Tambah Terapis Baru
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-people-fill"></i> Daftar Terapis</h5>
        </div>
        <div class="card-body">
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Total Rekam Medis</th>
                            <th>Terdaftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($terapis as $index => $t)
                            <tr>
                                <td>{{ $terapis->firstItem() + $index }}</td>
                                <td>
                                    <strong>{{ $t->name }}</strong>
                                </td>
                                <td>{{ $t->email }}</td>
                                <td>{{ $t->phone }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $t->medical_records_as_terapis_count }} rekam medis
                                    </span>
                                </td>
                                <td>{{ $t->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.terapis.edit', $t->id) }}" class="btn btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.terapis.destroy', $t->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus terapis {{ $t->name }}? Semua rekam medis yang dibuat akan ikut terhapus!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1"></i>
                                    <p>Belum ada terapis terdaftar</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $terapis->links() }}
            </div>

        </div>
    </div>

</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection