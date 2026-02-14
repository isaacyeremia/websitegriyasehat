@extends('layouts.app')

@section('title','Beranda')

@section('content')

{{-- Toast Notification - Auto dismiss, floating --}}
@if(session('success'))
<div id="toast-success" class="toast-floating toast-success">
    <div class="toast-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
        </svg>
    </div>
    <span class="toast-message">{{ session('success') }}</span>
    <div class="toast-progress"></div>
</div>
@endif

@if(session('error'))
<div id="toast-error" class="toast-floating toast-error">
    <div class="toast-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
        </svg>
    </div>
    <span class="toast-message">{{ session('error') }}</span>
    <div class="toast-progress toast-progress-error"></div>
</div>
@endif

<section class="hero-outer">
  <div class="hero-inner">
    <div class="hero-row">
      <div class="hero-left">
        <h1 class="hero-title">WELLNESS FOR EVERYONE</h1>
        <p class="hero-desc">
          Layanan kesehatan terpadu dengan teknologi modern untuk kenyamanan dan kemudahan Anda.
        </p>

        <div class="hero-cta">
          {{-- Selalu tampilkan Booking Antrian; redirect ke login jika belum auth --}}
          <a href="{{ auth()->check() ? route('booking.index') : route('login') }}" class="btn btn-brown">
            <i class="bi bi-calendar-check me-2"></i>Booking Antrian
          </a>
        </div>

      </div>

      <div class="hero-right">
        <div class="hero-card">
          <img 
            src="{{ asset('images/CISAT-traditional-chinese-medicine5.jpg') }}"
            alt="Dokter Griya Sehat"
            style="width:100%;height:100%;object-fit:contain;"
          >
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container text-center">
    <h3 style="font-weight:700">Our Service</h3>
    <p style="color:var(--muted); max-width:720px; margin:8px auto 0;">
      Berbagai layanan kesehatan yang dapat Anda akses dengan mudah
    </p>

    <div class="services-row" style="margin-top:36px;">

      <div class="service-card">
        <div class="icon">🩺</div>
        <h5>Konsultasi</h5>
        <p class="text-muted small">
          Konsultasi dengan dokter profesional secara online maupun offline.
        </p>
      </div>

      <div class="service-card">
        <div class="icon">📇</div>
        <h5>Katalog Apotek</h5>
        <p class="text-muted small">
          Lihat daftar obat dan produk kesehatan yang tersedia di Griya Sehat.
        </p>
        <a href="{{ route('apotek.index') }}" class="btn btn-sm btn-outline-brown mt-2">
          <i class="bi bi-arrow-right-circle me-1"></i>Lihat Katalog
        </a>
      </div>

      <div class="service-card">
        <div class="icon">📅</div>
        <h5>Cek Antrian Online</h5>
        <p class="text-muted small">
          Pantau antrian secara real-time tanpa perlu menunggu lama.
        </p>
        <a href="{{ auth()->check() ? route('booking.index') : route('login') }}" class="btn btn-sm btn-brown mt-2">
          <i class="bi bi-calendar-check me-1"></i>Booking Sekarang
        </a>
      </div>

    </div>
  </div>
</section>

<section class="about-section">
  <div class="about-inner">

    <div class="about-left">
      <h3 style="font-weight:800">Tentang Griya Sehat</h3>
      <p style="color:var(--muted); margin-top:14px;">
        Klinik Kesehatan Akupunktur, Pengobatan Herbal, Kop/Bekam, Kerokan, Pijat/Tuina. 
        Dengan tim dokter spesialis dan fasilitas lengkap, kami berkomitmen memberikan 
        pelayanan kesehatan terpadu yang mudah diakses oleh seluruh masyarakat.
      </p>

      <div style="margin-top:18px;">
        <a href="{{ route('about') }}" class="btn btn-brown">
          <i class="bi bi-info-circle me-2"></i>Selengkapnya
        </a>
      </div>
    </div>

    <div class="about-right">
      <div class="about-card">
        <div id="fasilitasCarousel" class="carousel slide h-100" data-bs-ride="carousel">
          <div class="carousel-inner h-100">
            <div class="carousel-item active h-100">
              <img
                src="{{ asset('images/fasilitas 1.jpg') }}"
                class="d-block w-100 h-100"
                style="object-fit:cover; border-radius:12px;"
                alt="Fasilitas Terapi"
              >
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
/* ======================================================
   TOAST NOTIFICATION - Floating, Auto-dismiss
   ====================================================== */
.toast-floating {
  position: fixed;
  top: 80px;
  right: 24px;
  z-index: 99999;
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 16px 20px 20px;
  border-radius: 14px;
  min-width: 300px;
  max-width: 400px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.15), 0 4px 12px rgba(0,0,0,0.08);
  font-size: 0.95rem;
  font-weight: 500;
  overflow: hidden;
  animation: toastIn 0.45s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}

.toast-success {
  background: #ffffff;
  color: #14532d;
  border: 1px solid #bbf7d0;
  border-left: 5px solid #22c55e;
}

.toast-error {
  background: #ffffff;
  color: #7f1d1d;
  border: 1px solid #fecaca;
  border-left: 5px solid #ef4444;
}

.toast-icon {
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: 50%;
}

.toast-success .toast-icon {
  color: #16a34a;
  background: #dcfce7;
}

.toast-error .toast-icon {
  color: #dc2626;
  background: #fee2e2;
}

.toast-message {
  flex: 1;
  line-height: 1.45;
}

/* Progress bar sweeps across the bottom */
.toast-progress {
  position: absolute;
  bottom: 0;
  left: 0;
  height: 4px;
  width: 100%;
  background: #22c55e;
  border-radius: 0 0 14px 14px;
  transform-origin: left;
  animation: toastProgress 3.5s linear forwards;
}

.toast-progress-error {
  background: #ef4444;
}

@keyframes toastIn {
  0%   { opacity: 0; transform: translateX(80px) scale(0.88); }
  100% { opacity: 1; transform: translateX(0)    scale(1); }
}

@keyframes toastOut {
  0%   { opacity: 1; transform: translateX(0)    scale(1); }
  100% { opacity: 0; transform: translateX(80px) scale(0.88); }
}

@keyframes toastProgress {
  from { transform: scaleX(1); }
  to   { transform: scaleX(0); }
}

/* ======================================================
   BUTTONS
   ====================================================== */
.btn-brown {
  background-color: #8B4513;
  color: white;
  border: none;
  padding: 12px 28px;
  border-radius: 6px;
  font-weight: 600;
  text-decoration: none;
  display: inline-block;
  transition: all 0.3s ease;
}

.btn-brown:hover {
  background-color: #6F3609;
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(139, 69, 19, 0.3);
}

.btn-outline-brown {
  background-color: transparent;
  color: #8B4513;
  border: 2px solid #8B4513;
  padding: 10px 26px;
  border-radius: 6px;
  font-weight: 600;
  text-decoration: none;
  display: inline-block;
  transition: all 0.3s ease;
}

.btn-outline-brown:hover {
  background-color: #8B4513;
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(139, 69, 19, 0.3);
}

/* ======================================================
   HERO
   ====================================================== */
.hero-outer { padding: 60px 0; }

.hero-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.hero-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 40px;
  align-items: center;
}

.hero-title {
  font-size: 48px;
  font-weight: 800;
  color: #333;
  margin-bottom: 20px;
}

.hero-desc {
  font-size: 18px;
  color: #666;
  margin-bottom: 30px;
  line-height: 1.6;
}

.hero-cta {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

.hero-card {
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 8px 24px rgba(0,0,0,0.1);
}

/* ======================================================
   SERVICES
   ====================================================== */
.section {
  padding: 80px 20px;
  background-color: #f8f9fa;
}

.services-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 30px;
  max-width: 1200px;
  margin: 0 auto;
}

.service-card {
  background: white;
  padding: 40px 30px;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.service-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}

.service-card .icon { font-size: 48px; margin-bottom: 20px; }
.service-card h5   { font-weight: 700; margin-bottom: 12px; color: #333; }
.service-card p    { color: #666; line-height: 1.6; margin: 0; }

/* ======================================================
   ABOUT
   ====================================================== */
.about-section { padding: 80px 20px; }

.about-inner {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: center;
}

.about-card {
  border-radius: 12px;
  overflow: hidden;
  height: 400px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.1);
}

/* ======================================================
   RESPONSIVE
   ====================================================== */
@media (max-width: 768px) {
  .hero-row, .about-inner { grid-template-columns: 1fr; }
  .hero-title  { font-size: 36px; }
  .services-row { grid-template-columns: 1fr; }
  .hero-cta    { flex-direction: column; }
  .hero-cta .btn { width: 100%; text-align: center; }

  .toast-floating {
    top: auto;
    bottom: 24px;
    right: 16px;
    left: 16px;
    min-width: unset;
    max-width: unset;
  }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Auto-dismiss all toast notifications after 3.5 s
    document.querySelectorAll('.toast-floating').forEach(function (toast) {
        setTimeout(function () {
            toast.style.animation = 'toastOut 0.4s cubic-bezier(0.4, 0, 1, 1) forwards';
            setTimeout(function () { toast.remove(); }, 420);
        }, 3500);
    });
});
</script>
@endpush

@endsection