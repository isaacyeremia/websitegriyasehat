@extends('layouts.app')
@section('title', $promosi->judul)

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
:root {
  --brown:      #8B4513;
  --brown-dark: #6F3609;
  --cream:      #FDF6EE;
  --text:       #2C1810;
  --muted:      #7A6655;
  --border:     #E8D5C0;
}

/* ── Breadcrumb ── */
.detail-breadcrumb {
  padding: 14px 20px;
  font-size: .82rem;
  color: var(--muted);
  border-bottom: 1px solid #f0f0f0;
  background: #fff;
}
.detail-breadcrumb a { color: var(--muted); text-decoration: none; }
.detail-breadcrumb a:hover { color: var(--brown); }
.detail-breadcrumb .sep { margin: 0 6px; }
.detail-breadcrumb .current { color: var(--text); font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px; display: inline-block; vertical-align: bottom; }

/* ── Header info ── */
.detail-header {
  padding: 20px 20px 0;
  background: #fff;
}
.detail-title {
  font-size: 1.3rem;
  font-weight: 800;
  color: var(--text);
  line-height: 1.3;
  margin: 0 0 10px;
}
.detail-meta {
  display: flex; flex-direction: column; gap: 6px;
  margin-bottom: 14px;
}
.detail-meta-row {
  display: flex; align-items: center; gap: 8px;
  font-size: .85rem; color: var(--muted);
}
.detail-meta-row i { color: var(--brown); font-size: 1rem; }
.detail-price-wrap {
  display: flex; align-items: center; gap: 12px;
  padding: 14px 20px;
  background: #fff;
  border-bottom: 1px solid #f0f0f0;
}
.detail-price-ori {
  font-size: .88rem; color: #aaa;
  text-decoration: line-through;
}
.detail-price-promo {
  font-size: 1.4rem; font-weight: 900;
  color: var(--brown);
}
.detail-hemat-badge {
  background: #fff3e0; color: var(--brown);
  font-size: .75rem; font-weight: 700;
  padding: 3px 10px; border-radius: 20px;
}

/* ── Poster gambar ── */
.detail-poster-wrap {
  background: #f5f5f5;
  padding: 0;
}
.detail-poster-wrap img {
  width: 100%;
  height: auto;
  display: block;
}

/* ── Konten detail ── */
.detail-content {
  background: #fff;
  padding: 0 20px 32px;
}

.detail-section {
  padding: 20px 0 0;
}
.detail-section-title {
  font-size: 1rem;
  font-weight: 800;
  color: var(--brown);
  margin: 0 0 12px;
  display: flex; align-items: center; gap: 7px;
}
.detail-section-title i { font-size: 1.1rem; }

/* Overview */
.detail-overview {
  background: #f0f7ff;
  border-radius: 10px;
  padding: 16px;
  font-size: .9rem;
  color: var(--text);
  line-height: 1.7;
}

/* Manfaat list */
.detail-benefits {
  list-style: none; padding: 0; margin: 0;
  display: flex; flex-direction: column; gap: 8px;
}
.detail-benefits li {
  display: flex; align-items: flex-start; gap: 10px;
  font-size: .9rem; color: var(--text); line-height: 1.45;
}
.detail-benefits li .check {
  flex-shrink: 0; width: 20px; height: 20px;
  background: var(--brown); color: #fff;
  border-radius: 50%; display: flex; align-items: center; justify-content: center;
  font-size: .65rem; margin-top: 1px;
}

/* Bonus */
.detail-bonus {
  background: #fffbeb; border: 1px solid #fde68a;
  border-radius: 10px; padding: 14px 16px;
  font-size: .9rem; color: #555;
  display: flex; align-items: flex-start; gap: 10px;
}
.detail-bonus .bonus-icon { font-size: 1.3rem; flex-shrink: 0; }

/* Syarat divider */
.detail-divider {
  height: 8px; background: #f5f5f5;
  margin: 20px -20px 0;
}

/* CTA tombol booking */
.detail-cta-wrap {
  position: sticky;
  bottom: 0;
  background: #fff;
  border-top: 1px solid #e9ecef;
  padding: 14px 20px;
  z-index: 100;
}
.detail-cta-btn {
  display: flex; align-items: center; justify-content: center; gap: 8px;
  width: 100%;
  background: var(--brown); color: #fff;
  border: none; border-radius: 10px;
  padding: 14px; font-size: 1rem; font-weight: 700;
  text-decoration: none;
  transition: background .2s;
}
.detail-cta-btn:hover { background: var(--brown-dark); color: #fff; }

/* ── Desktop: tampilkan seperti halaman biasa (lebih lebar) ── */
@media (min-width: 769px) {
  .detail-wrap {
    max-width: 720px;
    margin: 40px auto;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 24px rgba(0,0,0,.1);
    border: 1px solid var(--border);
  }
  .detail-title { font-size: 1.6rem; }
  .detail-price-promo { font-size: 1.8rem; }
  .detail-cta-wrap {
    position: static;
    border-top: none;
    padding: 0 20px 32px;
    background: #fff;
  }
  .detail-cta-btn { border-radius: 10px; }
}
</style>

<div class="detail-wrap">

  {{-- Breadcrumb --}}
  <div class="detail-breadcrumb">
    <a href="{{ route('home') }}">Beranda</a>
    <span class="sep">›</span>
    <a href="{{ route('home') }}#promo">Promo</a>
    <span class="sep">›</span>
    <span class="current">{{ $promosi->judul }}</span>
  </div>

  {{-- Header --}}
  <div class="detail-header">
    <h1 class="detail-title">{{ $promosi->judul }}</h1>
    <div class="detail-meta">
      @if($promosi->subjudul)
      <div class="detail-meta-row">
        <i class="bi bi-tag"></i>
        <span>{{ $promosi->subjudul }}</span>
      </div>
      @endif
      @if($promosi->berlaku_hingga)
      <div class="detail-meta-row">
        <i class="bi bi-calendar-event"></i>
        <span>Berlaku Sampai {{ $promosi->berlaku_hingga->translatedFormat('d F Y') }}</span>
      </div>
      @endif
    </div>
  </div>

  {{-- Harga --}}
  @if($promosi->harga_promo)
  <div class="detail-price-wrap">
    @if($promosi->harga_asli)
      <span class="detail-price-ori">{{ $promosi->hargaAsliFormatted() }}</span>
    @endif
    <span class="detail-price-promo">{{ $promosi->hargaPromoFormatted() }}</span>
    @if($promosi->harga_asli && $promosi->harga_promo)
      @php $hemat = $promosi->harga_asli - $promosi->harga_promo; @endphp
      @if($hemat > 0)
        <span class="detail-hemat-badge">Hemat {{ 'Rp ' . number_format($hemat,0,',','.') }}</span>
      @endif
    @endif
  </div>
  @endif

  {{-- Poster --}}
  @if($promosi->gambar)
  <div class="detail-poster-wrap">
    <img src="{{ $promosi->gambarUrl() }}" alt="{{ $promosi->judul }}">
  </div>
  @endif

  {{-- Konten --}}
  <div class="detail-content">

    {{-- Deskripsi / Overview --}}
    @if($promosi->deskripsi)
    <div class="detail-section">
      <h2 class="detail-section-title"><i class="bi bi-info-circle"></i> Overview</h2>
      <div class="detail-overview">{{ $promosi->deskripsi }}</div>
    </div>
    @endif

    {{-- Manfaat --}}
    @if($promosi->manfaat && count($promosi->manfaat))
    <div class="detail-section">
      <h2 class="detail-section-title"><i class="bi bi-check2-circle"></i> Manfaat Paket</h2>
      <ul class="detail-benefits">
        @foreach($promosi->manfaat as $m)
        <li>
          <span class="check"><i class="bi bi-check"></i></span>
          <span>{{ $m }}</span>
        </li>
        @endforeach
      </ul>
    </div>
    @endif

    {{-- Definisi / Cara Kerja --}}
    @if($promosi->definisi)
    <div class="detail-section">
      <h2 class="detail-section-title"><i class="bi bi-lightbulb"></i> Cara Kerja</h2>
      <div class="detail-overview" style="background:#f0fff4;">{{ $promosi->definisi }}</div>
    </div>
    @endif

    {{-- Bonus --}}
    @if($promosi->bonus)
    <div class="detail-section">
      <div class="detail-bonus">
        <span class="bonus-icon">🎁</span>
        <div>
          <strong>Bonus:</strong><br>
          {{ $promosi->bonus }}
        </div>
      </div>
    </div>
    @endif

  </div>

  {{-- CTA Booking --}}
  <div class="detail-cta-wrap">
    <a href="{{ auth()->check() ? route('booking.index') : route('login') }}" class="detail-cta-btn">
      <i class="bi bi-calendar-check"></i>
      Booking Sekarang
    </a>
  </div>

</div>

@endsection