@extends('layouts.app')

@section('title','Beranda')

@section('content')

{{-- Toast Notification --}}
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

{{-- ===================== HERO ===================== --}}
<section class="hero-outer">
  <div class="hero-inner">
    <div class="hero-row">
      <div class="hero-left">
        <span class="hero-badge">Klinik Kesehatan Tradisional</span>
        <h1 class="hero-title">WELLNESS FOR EVERYONE</h1>
        <p class="hero-desc">
          Layanan kesehatan terpadu dengan teknologi modern untuk kenyamanan dan kemudahan Anda.
        </p>
        <div class="hero-cta">
          <a href="{{ auth()->check() ? route('booking.index') : route('login') }}" class="btn btn-brown">
            <i class="bi bi-calendar-check me-2"></i>Booking Antrian
          </a>
        </div>
      </div>
      <div class="hero-right">
        <div class="hero-card">
          <img src="{{ asset('images/CISAT-traditional-chinese-medicine5.jpg') }}" alt="Dokter Griya Sehat">
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ===================== PROMO SECTION ===================== --}}
@if(isset($promosis) && $promosis->isNotEmpty())
<section class="promo-section">
  <div class="section-container">

    {{-- HEADER --}}
    <div class="section-header">
      <h2 class="section-title">Promo</h2>
      <p class="section-subtitle">Penawaran spesial untuk Anda</p>
    </div>

    {{-- DESKTOP: grid card (>768px) --}}
    <div class="promo-grid">
      @foreach($promosis as $promo)
      <div class="promo-card">
        @if($promo->gambar)
        <div class="promo-card-img-wrap">
          <img src="{{ $promo->gambarUrl() }}" alt="{{ $promo->judul }}" class="promo-card-img">
          @if($promo->berlaku_hingga)
            <span class="promo-card-date-badge"><i class="bi bi-clock"></i> {{ $promo->berlaku_hingga->format('d M Y') }}</span>
          @endif
          @if($promo->harga_promo)
            <span class="promo-card-price-badge">{{ $promo->hargaPromoFormatted() }}</span>
          @endif
        </div>
        @endif
        <div class="promo-card-body">
          <h3 class="promo-card-title">{{ $promo->judul }}</h3>
          @if($promo->subjudul)<p class="promo-card-subtitle">{{ $promo->subjudul }}</p>@endif
          @if($promo->manfaat && count($promo->manfaat))
          <ul class="promo-card-benefits">
            @foreach($promo->manfaat as $m)<li>{{ $m }}</li>@endforeach
          </ul>
          @endif
          @if($promo->deskripsi)<p class="promo-card-desc">{{ $promo->deskripsi }}</p>@endif
          @if($promo->bonus)<div class="promo-card-bonus">🎁 {{ $promo->bonus }}</div>@endif
        </div>
      </div>
      @endforeach
    </div>

    {{-- MOBILE: list horizontal (<768px) --}}
    <div class="promo-list">
      @foreach($promosis as $promo)
      <div class="promo-item">
        <a href="{{ route('promosi.show', $promo->id) }}" class="promo-item-link">
          <div class="promo-item-img-wrap">
            @if($promo->gambar)
              <img src="{{ $promo->gambarUrl() }}" alt="{{ $promo->judul }}" class="promo-item-img">
            @else
              <div class="promo-item-img-placeholder"><i class="bi bi-image"></i></div>
            @endif
          </div>
          <div class="promo-item-body">
            <h3 class="promo-item-title">{{ $promo->judul }}</h3>
            @if($promo->subjudul)<p class="promo-item-subtitle">{{ $promo->subjudul }}</p>@endif
            <div class="promo-item-tags">
              @if($promo->berlaku_hingga)
                <span class="promo-tag promo-tag-date">{{ $promo->berlaku_hingga->format('d M Y') }}</span>
              @endif
              @if($promo->harga_asli)
                <span class="promo-tag promo-tag-price-ori">{{ $promo->hargaAsliFormatted() }}</span>
              @endif
              @if($promo->harga_promo)
                <span class="promo-tag promo-tag-price">{{ $promo->hargaPromoFormatted() }}</span>
              @endif
            </div>
          </div>
          <div class="promo-item-arrow"><i class="bi bi-chevron-right"></i></div>
        </a>
      </div>
      @endforeach
    </div>

  </div>
</section>
@endif

{{-- ===================== SERVICES ===================== --}}
<section class="services-section">
  <div class="section-container">
    <div class="section-header">
      <h2 class="section-title">Our Service</h2>
      <p class="section-subtitle">Berbagai layanan kesehatan yang dapat Anda akses dengan mudah</p>
    </div>
    <div class="services-grid">
      <div class="service-card">
        <div class="service-icon">🩺</div>
        <h5 class="service-name">Konsultasi</h5>
        <p class="service-desc">Konsultasi dengan dokter profesional secara online maupun offline.</p>
      </div>
      <div class="service-card">
        <div class="service-icon">📇</div>
        <h5 class="service-name">Katalog Apotek</h5>
        <p class="service-desc">Lihat daftar obat dan produk kesehatan yang tersedia di Griya Sehat.</p>
        <a href="{{ route('apotek.index') }}" class="btn btn-outline-brown mt-3">
          <i class="bi bi-arrow-right-circle me-1"></i>Lihat Katalog
        </a>
      </div>
      <div class="service-card">
        <div class="service-icon">📅</div>
        <h5 class="service-name">Cek Antrian Online</h5>
        <p class="service-desc">Pantau antrian secara real-time tanpa perlu menunggu lama.</p>
        <a href="{{ auth()->check() ? route('booking.index') : route('login') }}" class="btn btn-brown mt-3">
          <i class="bi bi-calendar-check me-1"></i>Booking Sekarang
        </a>
      </div>
    </div>
  </div>
</section>

{{-- ===================== ABOUT ===================== --}}
<section class="about-section">
  <div class="section-container">
    <div class="about-grid">
      <div class="about-content">
        <span class="hero-badge">Tentang Kami</span>
        <h2 class="about-title">Tentang Griya Sehat</h2>
        <p class="about-desc">
          Klinik Kesehatan Akupunktur, Pengobatan Herbal, Kop/Bekam, Kerokan, Pijat/Tuina.
          Dengan tim dokter spesialis dan fasilitas lengkap, kami berkomitmen memberikan
          pelayanan kesehatan terpadu yang mudah diakses oleh seluruh masyarakat.
        </p>
        <a href="{{ route('about') }}" class="btn btn-brown">
          <i class="bi bi-info-circle me-2"></i>Selengkapnya
        </a>
      </div>
      <div class="about-media">
        <div class="about-card">
          <div id="fasilitasCarousel" class="carousel slide h-100" data-bs-ride="carousel">
            <div class="carousel-inner h-100">
              <div class="carousel-item active h-100">
                <img src="{{ asset('images/fasilitas 1.jpg') }}"
                  class="d-block w-100 h-100"
                  style="object-fit:cover; border-radius:16px;"
                  alt="Fasilitas Terapi">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
:root {
  --brown:        #8B4513;
  --brown-dark:   #6F3609;
  --brown-deeper: #5C2D0A;
  --brown-light:  #C4813A;
  --cream:        #FDF6EE;
  --text:         #2C1810;
  --muted:        #7A6655;
  --border:       #E8D5C0;
  --shadow-sm:    0 2px 8px rgba(139,69,19,.08);
  --shadow-md:    0 6px 20px rgba(139,69,19,.12);
  --shadow-lg:    0 12px 40px rgba(139,69,19,.16);
  --radius-sm:    8px;
  --radius-md:    12px;
  --radius-lg:    16px;
}

/* Toast */
.toast-floating {
  position: fixed; top: 80px; right: 24px; z-index: 99999;
  display: flex; align-items: center; gap: 12px;
  padding: 16px 20px 22px; border-radius: 14px;
  min-width: 300px; max-width: 400px;
  box-shadow: 0 10px 40px rgba(0,0,0,.15), 0 4px 12px rgba(0,0,0,.08);
  font-size: .95rem; font-weight: 500; overflow: hidden;
  animation: toastIn .45s cubic-bezier(.34,1.56,.64,1) forwards;
}
.toast-success { background:#fff; color:#14532d; border:1px solid #bbf7d0; border-left:5px solid #22c55e; }
.toast-error   { background:#fff; color:#7f1d1d; border:1px solid #fecaca; border-left:5px solid #ef4444; }
.toast-icon { flex-shrink:0; display:flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:50%; }
.toast-success .toast-icon { color:#16a34a; background:#dcfce7; }
.toast-error   .toast-icon { color:#dc2626; background:#fee2e2; }
.toast-message { flex:1; line-height:1.45; }
.toast-progress {
  position:absolute; bottom:0; left:0; height:4px; width:100%; background:#22c55e;
  border-radius:0 0 14px 14px; transform-origin:left;
  animation:toastProgress 3.5s linear forwards;
}
.toast-progress-error { background:#ef4444; }
@keyframes toastIn    { 0%{opacity:0;transform:translateX(80px) scale(.88)} 100%{opacity:1;transform:translateX(0) scale(1)} }
@keyframes toastOut   { 0%{opacity:1;transform:translateX(0) scale(1)} 100%{opacity:0;transform:translateX(80px) scale(.88)} }
@keyframes toastProgress { from{transform:scaleX(1)} to{transform:scaleX(0)} }

/* Buttons */
.btn-brown {
  background:var(--brown); color:#fff; border:2px solid var(--brown);
  padding:11px 28px; border-radius:var(--radius-sm); font-weight:600;
  font-size:.9rem; text-decoration:none; display:inline-flex; align-items:center;
  transition:background .25s, transform .25s, box-shadow .25s; cursor:pointer;
}
.btn-brown:hover { background:var(--brown-dark); border-color:var(--brown-dark); color:#fff; transform:translateY(-2px); box-shadow:var(--shadow-md); }
.btn-outline-brown {
  background:transparent; color:var(--brown); border:2px solid var(--brown);
  padding:9px 22px; border-radius:var(--radius-sm); font-weight:600; font-size:.88rem;
  text-decoration:none; display:inline-flex; align-items:center; transition:all .25s;
}
.btn-outline-brown:hover { background:var(--brown); color:#fff; transform:translateY(-2px); box-shadow:var(--shadow-md); }

/* Layout */
.section-container { max-width:1160px; margin:0 auto; padding:0 24px; }
.section-header { text-align:center; margin-bottom:40px; }
.section-title { font-size:2rem; font-weight:800; color:var(--text); margin:0 0 10px; letter-spacing:-.02em; }
.section-subtitle { color:var(--muted); font-size:.95rem; max-width:520px; margin:0 auto; line-height:1.6; }

/* Hero */
.hero-outer {
  background-color:var(--cream);
  background-image: radial-gradient(circle, rgba(139,69,19,.12) 1.5px, transparent 1.5px);
  background-size:28px 28px; padding:72px 24px;
  border-bottom:1px solid var(--border); position:relative;
}
.hero-outer::after {
  content:''; position:absolute; bottom:0; left:0; right:0; height:120px;
  background:linear-gradient(to bottom, transparent, var(--cream)); pointer-events:none;
}
.hero-inner { max-width:1160px; margin:0 auto; }
.hero-row { display:grid; grid-template-columns:1fr 1fr; gap:60px; align-items:center; }
.hero-badge {
  display:inline-block; background:rgba(139,69,19,.1); color:var(--brown);
  border:1px solid rgba(139,69,19,.2); padding:5px 14px; border-radius:20px;
  font-size:.78rem; font-weight:700; letter-spacing:.06em; text-transform:uppercase; margin-bottom:18px;
}
.hero-title { font-size:3rem; font-weight:900; color:var(--text); line-height:1.1; letter-spacing:-.03em; margin:0 0 18px; }
.hero-desc { font-size:1rem; color:var(--muted); line-height:1.7; margin:0 0 32px; max-width:420px; }
.hero-cta { display:flex; gap:12px; flex-wrap:wrap; }
.hero-card { border-radius:var(--radius-lg); overflow:hidden; box-shadow:var(--shadow-lg); aspect-ratio:4/3; background:var(--brown-deeper); }
.hero-card img { width:100%; height:100%; object-fit:cover; display:block; }

/* ======================================================
   PROMO — Desktop: grid card | Mobile: list horizontal
   ====================================================== */
.promo-section {
  padding: 72px 24px;
  background: #f8f9fa;
  border-bottom: 1px solid var(--border);
}

/* ── DESKTOP GRID (default, tampil di >768px) ── */
.promo-grid {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 24px;
}
.promo-card {
  background: #fff;
  border-radius: 14px;
  overflow: hidden;
  box-shadow: 0 2px 12px rgba(0,0,0,.07);
  border: 1px solid #e9ecef;
  transition: transform .28s, box-shadow .28s;
  display: flex;
  flex-direction: column;
  flex: 0 1 280px;
  max-width: 320px;
  min-width: 240px;
  width: 100%;
}
.promo-card:hover { transform: translateY(-5px); box-shadow: 0 10px 32px rgba(0,0,0,.12); }
.promo-card-img-wrap { position: relative; width: 100%; background: #f0f0f0; overflow: hidden; }
.promo-card-img { width: 100%; height: auto; display: block; object-fit: contain; }
.promo-card-date-badge {
  position: absolute; bottom: 10px; left: 10px;
  background: rgba(0,0,0,.55); color: #fff;
  font-size: .72rem; font-weight: 600; padding: 4px 10px;
  border-radius: 20px; display: inline-flex; align-items: center; gap: 4px;
}
.promo-card-price-badge {
  position: absolute; top: 10px; right: 10px;
  background: var(--brown); color: #fff;
  font-size: .8rem; font-weight: 800; padding: 5px 12px; border-radius: 20px;
}
.promo-card-body { padding: 18px 20px 20px; display: flex; flex-direction: column; gap: 8px; flex: 1; }
.promo-card-title { font-size: 1rem; font-weight: 800; color: var(--text); margin: 0; line-height: 1.3; }
.promo-card-subtitle { font-size: .83rem; color: var(--muted); margin: 0; font-style: italic; }
.promo-card-benefits { list-style: none; padding: 0; margin: 4px 0 0; display: flex; flex-direction: column; gap: 4px; }
.promo-card-benefits li { font-size: .82rem; color: #444; padding-left: 18px; position: relative; line-height: 1.45; }
.promo-card-benefits li::before { content: '✓'; position: absolute; left: 0; color: var(--brown); font-weight: 800; font-size: .78rem; }
.promo-card-desc { font-size: .82rem; color: var(--muted); margin: 0; line-height: 1.55; }
.promo-card-bonus { font-size: .8rem; color: #555; background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 8px 12px; margin-top: 4px; }

/* Mobile list — SEMBUNYIKAN di desktop */
.promo-list { display: none; }

/* ── MOBILE LIST (tampil di ≤768px) ── */
.promo-item {
  border-bottom: 1px solid #efefef;
}
.promo-item:last-child { border-bottom: none; }
.promo-item-link {
  display: flex; align-items: flex-start; gap: 14px;
  padding: 16px 0;
  text-decoration: none; color: inherit;
  transition: background .18s;
  width: 100%;
}
.promo-item-link:hover, .promo-item-link:active { background: #f7f7f7; color: inherit; }
.promo-item-img-wrap {
  flex-shrink: 0;
  width: 130px;
  border-radius: 10px;
  overflow: hidden;
  background: #f5f5f5;
  /* Tinggi mengikuti gambar, tidak crop */
  align-self: flex-start;
}
.promo-item-img {
  width: 100%;
  height: auto;          /* ← gambar penuh tidak terpotong */
  display: block;
  object-fit: contain;
}
.promo-item-img-placeholder {
  width: 100%; height: 100%; display: flex; align-items: center;
  justify-content: center; font-size: 1.6rem; color: #ccc;
}
.promo-item-body { flex: 1; display: flex; flex-direction: column; gap: 6px; min-width: 0; }
.promo-item-title { font-size: .95rem; font-weight: 700; color: var(--text); margin: 0; line-height: 1.3; }
.promo-item-subtitle { font-size: .78rem; color: var(--muted); margin: 0; }
.promo-item-tags { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 2px; }
.promo-tag {
  display: inline-flex; align-items: center;
  font-size: .72rem; font-weight: 600; padding: 3px 10px; border-radius: 20px;
}
.promo-tag-date     { background: #e8f4ff; color: #1a6fb5; }
.promo-tag-price    { background: #fff3e0; color: var(--brown); }
.promo-tag-price-ori { background: #f5f5f5; color: #999; text-decoration: line-through; }
.promo-item-arrow {
  flex-shrink: 0; display: flex; align-items: center;
  color: #ccc; font-size: .9rem; padding-top: 4px;
  align-self: center;
}

/* Services */
.services-section { padding:88px 24px; background:#fff; }
.services-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:28px; }
.service-card {
  background:var(--cream); border:1px solid var(--border); border-top:3px solid var(--brown-light);
  padding:40px 32px; border-radius:var(--radius-lg); display:flex; flex-direction:column;
  align-items:flex-start; transition:transform .3s, box-shadow .3s, border-color .3s, border-top-color .3s;
}
.service-card:hover { transform:translateY(-6px); box-shadow:var(--shadow-lg); border-color:rgba(139,69,19,.25); border-top-color:var(--brown); }
.service-icon { font-size:2.5rem; margin-bottom:20px; line-height:1; }
.service-name { font-size:1.05rem; font-weight:800; color:var(--text); margin:0 0 10px; letter-spacing:-.01em; }
.service-desc { color:var(--muted); font-size:.88rem; line-height:1.65; margin:0; flex:1; }

/* About */
.about-section { padding:88px 24px; background:var(--cream); border-top:1px solid var(--border); border-bottom:1px solid var(--border); }
.about-grid { display:grid; grid-template-columns:1fr 1fr; gap:72px; align-items:center; }
.about-title { font-size:2rem; font-weight:800; color:var(--text); margin:0 0 16px; letter-spacing:-.02em; }
.about-desc { color:var(--muted); font-size:.95rem; line-height:1.75; margin:0 0 28px; }
.about-card { border-radius:var(--radius-lg); overflow:hidden; height:420px; box-shadow:var(--shadow-lg); }

/* ======================================================
   RESPONSIVE
   ====================================================== */
@media (max-width: 900px) {
  .hero-row, .about-grid { grid-template-columns:1fr; gap:40px; }
  .hero-title { font-size:2.2rem; }
  .about-media { order:-1; }
  .about-card  { height:300px; }
  .services-grid { grid-template-columns:1fr; gap:20px; }
}

/* Di bawah 768px: sembunyikan grid, tampilkan list */
@media (max-width: 768px) {
  .promo-section { padding: 32px 16px; background: #fff; }
  .promo-grid { display: none; }        /* sembunyikan grid card */
  .promo-list { display: flex; flex-direction: column; } /* tampilkan list */
}

@media (max-width: 600px) {
  .hero-outer, .services-section, .about-section { padding:48px 16px; }
  .hero-title { font-size:1.75rem; }
  .hero-desc { font-size:.9rem; }
  .hero-cta { flex-direction:column; }
  .hero-cta .btn { width:100%; justify-content:center; }
  .toast-floating { top:auto; bottom:24px; right:16px; left:16px; min-width:unset; max-width:unset; }
}

@media (max-width: 420px) {
  .promo-item-img-wrap { width: 108px; height: 88px; }
  .promo-item-title { font-size: .88rem; }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toast-floating').forEach(function (toast) {
        setTimeout(function () {
            toast.style.animation = 'toastOut 0.4s cubic-bezier(0.4,0,1,1) forwards';
            setTimeout(function () { toast.remove(); }, 420);
        }, 3500);
    });
});
</script>
@endpush

@endsection