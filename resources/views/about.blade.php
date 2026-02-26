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

{{-- ============================================================ --}}
{{-- LAYANAN KAMI - 3 cards visible, center = active, pure JS    --}}
{{-- ============================================================ --}}
<section class="layanan-section">
  <div class="container">
    <h2 class="text-center fw-bold mb-1">Layanan Kami</h2>
    <p class="text-center text-muted mb-5">Berbagai Metode Pengobatan Tradisional &amp; Modern</p>

    @php
      $layanan = [
        ['img'=>'akupuntur.jpg',        'judul'=>'Akupuntur',         'desc'=>'Terapi tusuk jarum tradisional Tiongkok untuk menyeimbangkan energi tubuh, mengatasi nyeri, stres, dan berbagai keluhan kesehatan lainnya.',          'tags'=>[['label'=>'Nyeri Sendi','c'=>'blue'],['label'=>'Migrain','c'=>'blue'],['label'=>'Stres','c'=>'blue']]],
        ['img'=>'pengobatan_herbal.jpg', 'judul'=>'Pengobatan Herbal', 'desc'=>'Ramuan herbal tradisional dari bahan-bahan alami pilihan untuk meningkatkan daya tahan tubuh dan mengatasi berbagai penyakit secara alami.',          'tags'=>[['label'=>'Alami','c'=>'green'],['label'=>'Aman','c'=>'green'],['label'=>'Berkhasiat','c'=>'green']]],
        ['img'=>'bekam.jpg',             'judul'=>'Bekam / Kop',       'desc'=>'Terapi bekam basah dan kering untuk mengeluarkan racun, melancarkan peredaran darah, dan meningkatkan sistem kekebalan tubuh.',                       'tags'=>[['label'=>'Detoksifikasi','c'=>'red'],['label'=>'Sirkulasi Darah','c'=>'red'],['label'=>'Imunitas','c'=>'red']]],
        ['img'=>'kerokan.jpg',           'judul'=>'Kerokan / Gua Sha', 'desc'=>'Teknik kerokan tradisional dengan alat khusus untuk mengeluarkan angin, mengurangi demam, masuk angin, dan melancarkan peredaran darah.',             'tags'=>[['label'=>'Masuk Angin','c'=>'yellow'],['label'=>'Pegal Linu','c'=>'yellow'],['label'=>'Demam','c'=>'yellow']]],
        ['img'=>'pijat_tuina.jpg',       'judul'=>'Pijat Tuina',       'desc'=>'Pijat terapi tradisional Tiongkok dengan teknik khusus untuk relaksasi otot, mengatasi nyeri, dan meningkatkan kesehatan secara menyeluruh.',          'tags'=>[['label'=>'Relaksasi','c'=>'teal'],['label'=>'Nyeri Otot','c'=>'teal'],['label'=>'Kesehatan','c'=>'teal']]],
      ];
    @endphp

    {{-- Data cards sebagai JSON untuk JS --}}
    <div id="layananData" style="display:none" data-cards='@json($layanan)'></div>

    {{-- Carousel UI --}}
    <div class="lc-outer">
      <button class="lc-btn lc-btn-prev" id="lcPrev">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
      </button>

      <div class="lc-viewport">
        {{-- 3 card slots: prev | active | next --}}
        <div class="lc-slot lc-slot-prev"  id="lcSlotPrev"></div>
        <div class="lc-slot lc-slot-active" id="lcSlotActive"></div>
        <div class="lc-slot lc-slot-next"  id="lcSlotNext"></div>
      </div>

      <button class="lc-btn lc-btn-next" id="lcNext">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
      </button>
    </div>

    {{-- Dots --}}
    <div class="lc-dots" id="lcDots">
      @foreach($layanan as $i => $l)
        <button class="lc-dot {{ $i===0?'active':'' }}" data-i="{{ $i }}"></button>
      @endforeach
    </div>
  </div>
</section>

<style>
.layanan-section { padding: 56px 0 48px; background: #fff; }

/* Outer — full container width, relative for absolute arrows */
.lc-outer {
  position: relative;
  width: 100%;
}

/* Viewport — 3-column, clips overflow */
.lc-viewport {
  display: grid;
  grid-template-columns: 1fr 2fr 1fr;
  gap: 20px;
  overflow: hidden;
  padding: 8px 80px;
}

/* Slots */
.lc-slot { overflow: hidden; position: relative; min-height: 260px; }
.lc-slot-prev, .lc-slot-next {
  opacity: 0.4;
  filter: blur(1px);
  pointer-events: none;
}
/* Clip side slots to show only ~half the card */
.lc-slot-prev { clip-path: inset(0 0 0 50%); }   /* show right half only */
.lc-slot-next { clip-path: inset(0 50% 0 0); }   /* show left half only */
.lc-slot-prev .lc-body,
.lc-slot-next .lc-body { display: none; }
.lc-slot-prev .lc-img,
.lc-slot-next .lc-img  { height: 260px; border-radius: 16px; overflow: hidden; }
.lc-slot-active { opacity: 1; filter: none; }

/* Card */
.lc-card {
  background: #fff; border-radius: 16px; overflow: hidden;
  border: 1.5px solid #e8edf5;
  box-shadow: 0 2px 12px rgba(0,0,0,0.07);
  display: flex; flex-direction: column;
  transition: box-shadow 0.3s, border-color 0.3s;
}
.lc-slot-active .lc-card {
  border-color: #c7d9ff;
  box-shadow: 0 12px 40px rgba(13,110,253,0.16);
}

/* Image */
.lc-img { height: 240px; overflow: hidden; flex-shrink: 0; }
.lc-img img {
  width: 100%; height: 100%; object-fit: cover; display: block;
  transition: transform 0.5s ease;
}
.lc-slot-active .lc-card:hover .lc-img img { transform: scale(1.04); }

/* Body */
.lc-body { padding: 18px 20px 22px; flex: 1; }
.lc-tags { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 8px; }
.lc-tag { font-size: 0.7rem; font-weight: 600; padding: 2px 10px; border-radius: 20px; }
.lc-tag-blue   { background:#dbeafe; color:#1d4ed8; }
.lc-tag-green  { background:#dcfce7; color:#15803d; }
.lc-tag-red    { background:#fee2e2; color:#b91c1c; }
.lc-tag-yellow { background:#fef9c3; color:#a16207; }
.lc-tag-teal   { background:#ccfbf1; color:#0f766e; }
.lc-title { font-size: 1.05rem; font-weight: 700; color: #1a1a2e; margin: 0 0 8px; }
.lc-desc  { color: #6c757d; font-size: 0.84rem; line-height: 1.65; margin: 0; }

/* Arrow buttons — on top of side cards */
.lc-btn {
  position: absolute;
  top: 50%; transform: translateY(-50%);
  z-index: 10;
  width: 44px; height: 44px; border-radius: 50%;
  border: none; background: #fff;
  box-shadow: 0 4px 16px rgba(0,0,0,0.15);
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; color: #333;
  transition: background 0.2s, color 0.2s, transform 0.2s;
}
.lc-btn:hover { background: #0d6efd; color: #fff; transform: translateY(-50%) scale(1.08); }
.lc-btn:disabled { opacity: 0.25; pointer-events: none; }
.lc-btn svg { width: 20px; height: 20px; }
/* Position arrows centered over the side card peek area */
.lc-btn-prev { left: calc(80px + (100% - 160px) / 6 - 22px); }
.lc-btn-next { right: calc(80px + (100% - 160px) / 6 - 22px); }

/* Dots */
.lc-dots { display: flex; justify-content: center; gap: 7px; margin-top: 20px; }
.lc-dot {
  width: 7px; height: 7px; border-radius: 50%;
  border: none; background: #c5cfe8; cursor: pointer; padding: 0;
  transition: background 0.25s, width 0.25s, border-radius 0.25s;
}
.lc-dot.active { background: #0d6efd; width: 22px; border-radius: 4px; }

/* Slide animation */
@keyframes lcFromRight { from { opacity:0; transform:translateX(40px); } to { opacity:1; transform:none; } }
@keyframes lcFromLeft  { from { opacity:0; transform:translateX(-40px); } to { opacity:1; transform:none; } }
.lc-viewport.dir-next .lc-card { animation: lcFromRight 0.38s cubic-bezier(.25,.8,.25,1) both; }
.lc-viewport.dir-prev .lc-card { animation: lcFromLeft  0.38s cubic-bezier(.25,.8,.25,1) both; }

/* Responsive */
@media (max-width: 768px) {
  .lc-viewport { padding: 8px 48px; grid-template-columns: 1fr 3fr 1fr; gap: 12px; }
  .lc-img, .lc-slot-prev .lc-img, .lc-slot-next .lc-img { height: 180px; }
  .lc-btn-prev { left: 4px; }
  .lc-btn-next { right: 4px; }
}
</style>

<script>
(function () {
  const raw    = document.getElementById('layananData').dataset.cards;
  const cards  = JSON.parse(raw);
  const TOTAL  = cards.length;
  const AUTO   = 4000;
  let cur      = 0;
  let timer    = null;

  const slotPrev   = document.getElementById('lcSlotPrev');
  const slotActive = document.getElementById('lcSlotActive');
  const slotNext   = document.getElementById('lcSlotNext');
  const btnPrev    = document.getElementById('lcPrev');
  const btnNext    = document.getElementById('lcNext');
  const dots       = Array.from(document.querySelectorAll('#lcDots .lc-dot'));

  /* Build tag color map */
  const tagColorMap = { blue:'blue', green:'green', red:'red', yellow:'yellow', teal:'teal' };

  /* Render a card into a slot element */
  function renderCard(slot, idx) {
    const d = cards[((idx % TOTAL) + TOTAL) % TOTAL];
    const tagsHtml = d.tags.map(t =>
      `<span class="lc-tag lc-tag-${t.c}">${t.label}</span>`
    ).join('');

    slot.innerHTML = `
      <div class="lc-card">
        <div class="lc-img">
          <img src="/images/layanan/${d.img}" alt="${d.judul}" loading="lazy">
        </div>
        <div class="lc-body">
          <div class="lc-tags">${tagsHtml}</div>
          <h3 class="lc-title">${d.judul}</h3>
          <p class="lc-desc">${d.desc}</p>
        </div>
      </div>`;
  }

  /* Render all 3 slots based on current index */
  function render() {
    renderCard(slotPrev,   cur - 1);
    renderCard(slotActive, cur);
    renderCard(slotNext,   cur + 1);

    /* Dots */
    dots.forEach((d, i) => d.classList.toggle('active', i === cur));
  }

  const viewport = document.querySelector('.lc-viewport');
  let animating  = false;

  function goTo(idx) {
    if (animating) return;
    animating = true;

    const next = ((idx % TOTAL) + TOTAL) % TOTAL;
    const dir  = idx >= cur ? 'dir-next' : 'dir-prev';   /* direction feel */

    /* Remove old direction class, set new content, add direction class */
    viewport.classList.remove('dir-next', 'dir-prev');
    cur = next;
    render();
    /* Force reflow so animation re-triggers */
    viewport.offsetWidth;
    viewport.classList.add(dir);

    /* Unlock after animation */
    setTimeout(() => { animating = false; }, 400);
  }

  /* Auto-play */
  function startAuto() {
    clearInterval(timer);
    timer = setInterval(() => goTo(cur + 1), AUTO);
  }
  function stopAuto() { clearInterval(timer); }

  btnPrev.addEventListener('click', () => { goTo(cur - 1); startAuto(); });
  btnNext.addEventListener('click', () => { goTo(cur + 1); startAuto(); });
  dots.forEach((d, i) => d.addEventListener('click', () => { goTo(i); startAuto(); }));

  /* Pause on hover */
  viewport.addEventListener('mouseenter', stopAuto);
  viewport.addEventListener('mouseleave', startAuto);

  /* Touch swipe */
  let tx = 0;
  slotActive.addEventListener('touchstart', e => { tx = e.touches[0].clientX; }, { passive: true });
  slotActive.addEventListener('touchend',   e => {
    const dx = e.changedTouches[0].clientX - tx;
    if (Math.abs(dx) > 40) { goTo(cur + (dx < 0 ? 1 : -1)); startAuto(); }
  });

  /* Init */
  render();
  startAuto();

  /* Position arrows centered over side slots after layout */
  function positionArrows() {
    const slotPrevEl = document.getElementById('lcSlotPrev');
    const slotNextEl = document.getElementById('lcSlotNext');
    const outerEl    = document.querySelector('.lc-outer');
    const outerRect  = outerEl.getBoundingClientRect();
    const prevRect   = slotPrevEl.getBoundingClientRect();
    const nextRect   = slotNextEl.getBoundingClientRect();

    btnPrev.style.left  = (prevRect.left  - outerRect.left + prevRect.width  / 2 - 22) + 'px';
    btnNext.style.right = (outerRect.right - nextRect.right + nextRect.width / 2 - 22) + 'px';
    btnNext.style.left  = '';
  }

  requestAnimationFrame(positionArrows);
  window.addEventListener('resize', positionArrows);
})();
</script>

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