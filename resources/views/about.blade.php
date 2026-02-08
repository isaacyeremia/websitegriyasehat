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

@endsection
