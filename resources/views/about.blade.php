@extends('layouts.app')

@section('title','Tentang Griya Sehat')

@section('content')

<!-- HERO -->
<section class="hero-outer" style="padding:50px 0;">
  <div class="hero-inner text-center">
    <h1 class="fw-bold text-white">Tentang Griya Sehat</h1>
    <p class="text-white-75">
      Klinik Kesehatan Akupunktur & Pengobatan Tradisional
    </p>
  </div>
</section>

<!-- ABOUT -->
<section class="about-section">
  <div class="about-inner">
    <div class="about-left">
      <h3 class="fw-bold mb-3">Tentang Griya Sehat</h3>
      <p class="text-muted">
        Klinik Kesehatan Akupunktur, Pengobatan Herbal, Kop/Bekam, Kerokan/GuaSha,
        dan Pijat/Tuina. Kami didukung oleh tenaga medis profesional dengan
        pendekatan tradisional dan modern.
      </p>

      <ul class="text-muted">
        <li>Akupunktur & Terapi Tradisional</li>
        <li>Pengobatan Herbal & Konsultasi</li>
        <li>Kop/Bekam, Kerokan, Pijat/Tuina</li>
        <li>Jadwal praktik fleksibel</li>
      </ul>
    </div>

    <div class="about-right">
      <div class="about-card">
        <img src="{{ asset('images/fasilitas 2.jpg') }}"
             alt="Fasilitas Griya Sehat"
             style="width:100%;height:100%;object-fit:cover;">
      </div>
    </div>
  </div>
</section>

<!-- LAYANAN KAMI (CAROUSEL SLIDER) -->
<section class="section bg-light">
  <div class="container">
    <h2 class="text-center fw-bold mb-1">Layanan Kami</h2>
    <p class="text-center text-muted mb-5">Berbagai Metode Pengobatan Tradisional & Modern</p>

    <div id="layananCarousel" class="carousel slide" data-bs-ride="carousel">
      
      <!-- Indicators -->
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#layananCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#layananCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#layananCarousel" data-bs-slide-to="2"></button>
        <button type="button" data-bs-target="#layananCarousel" data-bs-slide-to="3"></button>
        <button type="button" data-bs-target="#layananCarousel" data-bs-slide-to="4"></button>
      </div>

      <!-- Slides -->
      <div class="carousel-inner">
        
        <!-- Slide 1: Akupuntur -->
        <div class="carousel-item active">
          <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
              <div class="card border-0 shadow-lg">
                <img src="{{ asset('images/layanan/akupuntur.jpg') }}" 
                     class="card-img-top" 
                     alt="Akupuntur"
                     style="height: 400px; object-fit: cover;">
                <div class="card-body text-center p-4">
                  <h4 class="fw-bold mb-3">Akupuntur</h4>
                  <p class="text-muted">
                    Terapi tusuk jarum tradisional Tiongkok untuk menyeimbangkan energi tubuh, 
                    mengatasi nyeri, stres, dan berbagai keluhan kesehatan lainnya.
                  </p>
                  <div class="d-flex justify-content-center gap-2 mt-3">
                    <span class="badge bg-primary">Nyeri Sendi</span>
                    <span class="badge bg-primary">Migrain</span>
                    <span class="badge bg-primary">Stres</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Slide 2: Pengobatan Herbal -->
        <div class="carousel-item">
          <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
              <div class="card border-0 shadow-lg">
                <img src="{{ asset('images/layanan/pengobatan_herbal.jpg') }}" 
                     class="card-img-top" 
                     alt="Pengobatan Herbal"
                     style="height: 400px; object-fit: cover;">
                <div class="card-body text-center p-4">
                  <h4 class="fw-bold mb-3">Pengobatan Herbal</h4>
                  <p class="text-muted">
                    Ramuan herbal tradisional dari bahan-bahan alami pilihan untuk 
                    meningkatkan daya tahan tubuh dan mengatasi berbagai penyakit secara alami.
                  </p>
                  <div class="d-flex justify-content-center gap-2 mt-3">
                    <span class="badge bg-success">Alami</span>
                    <span class="badge bg-success">Aman</span>
                    <span class="badge bg-success">Berkhasiat</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Slide 3: Bekam/Kop -->
        <div class="carousel-item">
          <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
              <div class="card border-0 shadow-lg">
                <img src="{{ asset('images/layanan/bekam.jpg') }}" 
                     class="card-img-top" 
                     alt="Bekam"
                     style="height: 400px; object-fit: cover;">
                <div class="card-body text-center p-4">
                  <h4 class="fw-bold mb-3">Bekam / Kop</h4>
                  <p class="text-muted">
                    Terapi bekam basah dan kering untuk mengeluarkan racun, melancarkan peredaran darah, 
                    dan meningkatkan sistem kekebalan tubuh.
                  </p>
                  <div class="d-flex justify-content-center gap-2 mt-3">
                    <span class="badge bg-danger">Detoksifikasi</span>
                    <span class="badge bg-danger">Sirkulasi Darah</span>
                    <span class="badge bg-danger">Imunitas</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Slide 4: Kerokan -->
        <div class="carousel-item">
          <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
              <div class="card border-0 shadow-lg">
                <img src="{{ asset('images/layanan/kerokan.jpg') }}" 
                     class="card-img-top" 
                     alt="Kerokan"
                     style="height: 400px; object-fit: cover;">
                <div class="card-body text-center p-4">
                  <h4 class="fw-bold mb-3">Kerokan / Gua Sha</h4>
                  <p class="text-muted">
                    Teknik kerokan tradisional dengan alat khusus untuk mengeluarkan angin, 
                    mengurangi demam, masuk angin, dan melancarkan peredaran darah.
                  </p>
                  <div class="d-flex justify-content-center gap-2 mt-3">
                    <span class="badge bg-warning text-dark">Masuk Angin</span>
                    <span class="badge bg-warning text-dark">Pegal Linu</span>
                    <span class="badge bg-warning text-dark">Demam</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Slide 5: Pijat Tuina -->
        <div class="carousel-item">
          <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
              <div class="card border-0 shadow-lg">
                <img src="{{ asset('images/layanan/pijat_tuina.jpg') }}" 
                     class="card-img-top" 
                     alt="Pijat Tuina"
                     style="height: 400px; object-fit: cover;">
                <div class="card-body text-center p-4">
                  <h4 class="fw-bold mb-3">Pijat Tuina</h4>
                  <p class="text-muted">
                    Pijat terapi tradisional Tiongkok dengan teknik khusus untuk relaksasi otot, 
                    mengatasi nyeri, dan meningkatkan kesehatan secara menyeluruh.
                  </p>
                  <div class="d-flex justify-content-center gap-2 mt-3">
                    <span class="badge bg-info">Relaksasi</span>
                    <span class="badge bg-info">Nyeri Otot</span>
                    <span class="badge bg-info">Kesehatan</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Controls -->
      <button class="carousel-control-prev" type="button" data-bs-target="#layananCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#layananCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>

  </div>
</section>

{{-- TENAGA MEDIS --}}
<section class="section">
  <div class="container">
    <h2 class="text-center fw-bold mb-1">Tenaga Medis</h2>
    <p class="text-center text-muted mb-5">Jadwal & Harga Praktisi</p>

    <div class="row g-4">
      @php
        // Ambil dokter yang aktif DAN show_in_about = true
        $terapis = \App\Models\Doctor::where('is_active', true)
                                      ->where('show_in_about', true)
                                      ->orderBy('urutan', 'asc')
                                      ->orderBy('name', 'asc')
                                      ->get();
      @endphp

      @forelse($terapis as $t)
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 p-4 text-center shadow-sm hover-card">
            <img src="{{ $t->image 
                ? '/images/tenaga-medis/'.$t->image 
                : 'https://ui-avatars.com/api/?name='.urlencode($t->name).'&size=200&background=6c757d&color=fff&rounded=true' }}"
               class="rounded-circle mx-auto mb-3"
               style="width:110px;height:110px;object-fit:cover; border: 3px solid #f0f0f0;"
               onerror="this.src='{{ asset('images/default-doctor.jpg') }}'">
          <h6 class="fw-bold mb-1">{{ $t->name }}</h6>
          @if($t->specialization)
            <p class="text-primary small mb-2">{{ $t->specialization }}</p>
          @endif
          <p class="text-muted small mb-3">
            <i class="bi bi-clock"></i> {{ $t->schedule }}
          </p>
          
          @if($t->daftar_harga && count($t->daftar_harga) > 0)
            <div class="border-top pt-3">
              <p class="small fw-bold text-start mb-2">
                <i class="bi bi-tag"></i> Daftar Harga:
              </p>
              <ul class="small text-start mb-0">
                @foreach($t->daftar_harga as $h)
                  <li>{{ $h }}</li>
                @endforeach
              </ul>
            </div>
          @else
            <div class="border-top pt-3">
              <p class="text-muted small mb-0">
                <i class="bi bi-telephone"></i> <em>Hubungi klinik untuk info harga</em>
              </p>
            </div>
          @endif
        </div>
      </div>
      @empty
        <div class="col-12">
          <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> Data tenaga medis belum tersedia.
          </div>
        </div>
      @endforelse
    </div>
  </div>
</section>

<!-- MAP -->
<section class="section">
  <div class="container text-center">
    <h4 class="fw-bold mb-3">Lokasi Kami</h4>
    <div class="map-wrapper">
      <iframe class="map-embed"
        src="https://www.google.com/maps?q=Griya+Sehat+UKDC&output=embed"
        loading="lazy"></iframe>
    </div>
  </div>
</section>

<!-- Custom CSS -->
<style>
  /* Carousel Controls */
  .carousel-control-prev,
  .carousel-control-next {
    width: 5%;
  }

  .carousel-control-prev-icon,
  .carousel-control-next-icon {
    background-color: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    padding: 20px;
  }

  .carousel-indicators button {
    background-color: #6c757d;
  }

  .carousel-indicators button.active {
    background-color: #0d6efd;
  }

  .carousel-item {
    padding: 20px 0;
    min-height: 600px;
  }

  /* Doctor Card Hover Effect */
  .hover-card {
    transition: all 0.3s ease;
    border: 1px solid #e0e0e0;
  }

  .hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12) !important;
    border-color: #007bff;
  }

  .bg-light {
    background-color: #f8f9fa !important;
  }

  @media (max-width: 768px) {
    .carousel-item {
      min-height: 550px;
    }
    
    .card-img-top {
      height: 300px !important;
    }
  }
</style>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

@endsection