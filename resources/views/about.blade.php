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

<!-- TENAGA MEDIS -->
<section class="section">
  <div class="container">
    <h2 class="text-center fw-bold mb-1">Tenaga Medis</h2>
    <p class="text-center text-muted mb-5">Jadwal & Harga Praktisi</p>

    <div class="row g-4">

      @php
        $doctors = [
          [
            'foto'=>'catty.jpg',
            'nama'=>'Catty Santoso, B.Med',
            'jadwal'=>'Jumat | 13.00 – 17.00',
            'harga'=>[
              'Pendaftaran Rp50.000',
              'Akupuntur Biasa (20–30 menit) Rp175.000',
              'Akupuntur Kilat (5–10 menit) Rp50.000 / keluhan',
              'Akupuntur Mengeluarkan Darah Rp100.000',
              'Kop Tinggal Rp50.000',
              'Kop Kilat Rp50.000',
              'Kop Jalan Rp100.000',
              'Kop Mengeluarkan Darah Rp150.000'
            ]
          ],
          [
            'foto'=>'retnawati.jpg',
            'nama'=>'Retnawati, B.Med., B.Ed',
            'jadwal'=>'Selasa & Kamis | 13.00 – 18.00',
            'harga'=>[
              'Akupuntur Rp175.000',
              'Akupuntur Cepat Rp50.000',
              'Kop Jalan Rp50.000',
              'Kop Tinggal Rp50.000',
              'Kop Lengkap Rp100.000',
              'Konsultasi + Resep Rp50.000',
              'Pendaftaran Rp25.000'
            ]
          ],
          [
            'foto'=>'alfredo.jpg',
            'nama'=>'Alfredo Aldo E. P. Tjundawan, B.Med., M.MED.',
            'jadwal'=>'Senin 08.00–13.00 | Rabu & Jumat 18.00–21.00',
            'harga'=>[
              'Pendaftaran Rp50.000',
              'Akupuntur Biasa Rp175.000',
              'Akupuntur Kilat Rp50.000 / keluhan',
              'Akupuntur Mengeluarkan Darah Rp100.000',
              'Kop Tinggal Rp50.000',
              'Kop Kilat Rp50.000',
              'Kop Jalan Rp100.000',
              'Kop Mengeluarkan Darah Rp150.000'
            ]
          ],
          [
            'foto'=>'impian.jpg',
            'nama'=>'Impian Delillah Jazmine, S.Tr.Battra',
            'jadwal'=>'Selasa & Kamis | 18.00 – 21.00',
            'harga'=>[
              'Totok Wajah (25 menit) Rp75.000',
              'Pengobatan Tradisional Lengkap Rp150.000',
              'Kop Rp100.000',
              'Pendaftaran Rp25.000'
            ]
          ],
          [
            'foto'=>'fadilla.jpg',
            'nama'=>'Fadilla Ilmi Zarkasi, S.Tr.Battra',
            'jadwal'=>'Selasa & Jumat | 18.00 – 21.00',
            'harga'=>[
              'Pengobatan Tradisional Rp150.000',
              'Pengobatan Tradisional Khusus ABK Rp125.000',
              'Kop Rp50.000 / bagian',
              'Pendaftaran Rp25.000'
            ]
          ],
        ];
      @endphp

      @foreach($doctors as $d)
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 p-4 text-center">
          <img src="{{ asset('images/tenaga-medis/'.$d['foto']) }}"
               class="rounded-circle mx-auto mb-3"
               style="width:110px;height:110px;object-fit:cover;">
          <h6 class="fw-bold">{{ $d['nama'] }}</h6>
          <p class="text-muted small">{{ $d['jadwal'] }}</p>
          <ul class="small text-start">
            @foreach($d['harga'] as $h)
              <li>{{ $h }}</li>
            @endforeach
          </ul>
        </div>
      </div>
      @endforeach

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

<!-- Custom CSS untuk Carousel -->
<style>
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

@endsection