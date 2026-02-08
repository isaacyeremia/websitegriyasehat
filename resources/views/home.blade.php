@extends('layouts.app')

@section('title','Beranda')

@section('content')

<section class="hero-outer">
  <div class="hero-inner">
    <div class="hero-row">
      <div class="hero-left">
        <h1 class="hero-title">WELLNESS FOR EVERYONE</h1>
        <p class="hero-desc">
          Layanan kesehatan terpadu dengan teknologi modern untuk kenyamanan dan kemudahan Anda.
        </p>

        <div class="hero-cta">
          <a href="{{ route('booking.index') }}" class="btn btn-brown">
            Booking Antrian
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
      </div>

      <div class="service-card">
        <div class="icon">📅</div>
        <h5>Cek Antrian Online</h5>
        <p class="text-muted small">
          Pantau antrian secara real-time tanpa perlu menunggu lama.
        </p>
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
        <a href="{{ route('about') }}" class="btn btn-brown">Selengkapnya</a>
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

<style>
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

.hero-outer {
  padding: 60px 0;
}

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

.hero-card {
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 8px 24px rgba(0,0,0,0.1);
}

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

.service-card .icon {
  font-size: 48px;
  margin-bottom: 20px;
}

.service-card h5 {
  font-weight: 700;
  margin-bottom: 12px;
  color: #333;
}

.service-card p {
  color: #666;
  line-height: 1.6;
  margin: 0;
}

.about-section {
  padding: 80px 20px;
}

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

@media (max-width: 768px) {
  .hero-row,
  .about-inner {
    grid-template-columns: 1fr;
  }
  
  .hero-title {
    font-size: 36px;
  }
  
  .services-row {
    grid-template-columns: 1fr;
  }
}
</style>

@endsection