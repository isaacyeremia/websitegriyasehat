@extends('layouts.app')

@section('title','Tentang Griya Sehat')

@section('content')

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- HERO -->
<section class="hero-outer" style="padding:56px 0 48px;">
  <div class="hero-inner text-center">
    <h1 class="fw-bold text-white">Tentang Griya Sehat</h1>
    <p class="text-white-75">Klinik Kesehatan Akupunktur & Pengobatan Tradisional</p>
  </div>
</section>

{{-- ============================================================ --}}
{{-- LAYANAN KAMI - Carousel: 3 visible, center = active         --}}
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

    <div id="layananData" style="display:none" data-cards='@json($layanan)'></div>

    <div class="lc-outer">
      <button class="lc-btn lc-btn-prev" id="lcPrev" aria-label="Previous">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
      </button>

      <div class="lc-viewport" id="lcViewport">
        <div class="lc-slot lc-slot-prev"   id="lcSlotPrev"></div>
        <div class="lc-slot lc-slot-active" id="lcSlotActive"></div>
        <div class="lc-slot lc-slot-next"   id="lcSlotNext"></div>
      </div>

      <button class="lc-btn lc-btn-next" id="lcNext" aria-label="Next">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
      </button>
    </div>

    <div class="lc-dots" id="lcDots">
      @foreach($layanan as $i => $l)
        <button class="lc-dot {{ $i===0?'active':'' }}" data-i="{{ $i }}" aria-label="Layanan {{ $i+1 }}"></button>
      @endforeach
    </div>
  </div>
</section>

{{-- TENAGA MEDIS --}}
<section class="section tenaga-section">
  <div class="container">
    <h2 class="text-center fw-bold mb-1">Tenaga Medis</h2>
    <p class="text-center text-muted mb-5">Jadwal &amp; Harga Praktisi</p>

    <div class="row g-4">
      @php
        $terapis = \App\Models\Doctor::where('is_active', true)
                                      ->where('show_in_about', true)
                                      ->orderBy('urutan', 'asc')
                                      ->orderBy('name', 'asc')
                                      ->get();
      @endphp

      @forelse($terapis as $t)
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="doctor-card h-100 p-4 text-center">
          <img src="{{ $t->image
                ? '/images/tenaga-medis/'.$t->image
                : 'https://ui-avatars.com/api/?name='.urlencode($t->name).'&size=200&background=6c757d&color=fff&rounded=true' }}"
             class="doctor-avatar rounded-circle mx-auto mb-3"
             onerror="this.src='{{ asset('images/default-doctor.jpg') }}'">
          <h6 class="fw-bold mb-1">{{ $t->name }}</h6>
          @if($t->specialization)
            <p class="text-primary small mb-2">{{ $t->specialization }}</p>
          @endif
          <p class="text-muted small mb-3">
            <i class="bi bi-clock me-1"></i>{{ $t->schedule }}
          </p>

          @if($t->daftar_harga && count($t->daftar_harga) > 0)
            <div class="border-top pt-3">
              <p class="small fw-semibold text-start mb-2">
                <i class="bi bi-tag me-1"></i>Daftar Harga:
              </p>
              <ul class="small text-start mb-0 ps-3">
                @foreach($t->daftar_harga as $h)
                  <li class="mb-1">{{ $h }}</li>
                @endforeach
              </ul>
            </div>
          @else
            <div class="border-top pt-3">
              <p class="text-muted small mb-0">
                <i class="bi bi-telephone me-1"></i><em>Hubungi klinik untuk info harga</em>
              </p>
            </div>
          @endif
        </div>
      </div>
      @empty
        <div class="col-12">
          <div class="alert alert-info text-center">
            <i class="bi bi-info-circle me-1"></i>Data tenaga medis belum tersedia.
          </div>
        </div>
      @endforelse
    </div>
  </div>
</section>

<!-- MAP -->
<section class="section map-section">
  <div class="container text-center">
    <h4 class="fw-bold mb-1">Lokasi Kami</h4>
    <p class="text-muted mb-4">Temukan kami di sini</p>
    <div class="map-wrapper">
      <iframe class="map-embed"
        src="https://www.google.com/maps?q=Griya+Sehat+UKDC&output=embed"
        loading="lazy" allowfullscreen></iframe>
    </div>
  </div>
</section>

<style>
/* =============================================
   LAYANAN KAMI CAROUSEL
   ============================================= */
.layanan-section {
  padding: 64px 0 52px;
  background: #fff;
}

.lc-outer {
  position: relative;
  width: 100%;
}

/* 3-column grid: side-peek | active | side-peek */
.lc-viewport {
  display: grid;
  grid-template-columns: 200px 1fr 200px;
  gap: 20px;
  overflow: hidden;
  padding: 8px 0;
}

.lc-slot {
  overflow: hidden;
  position: relative;
  min-height: 300px;
}

/* Side slots: blurred, clipped to half, no text body */
.lc-slot-prev,
.lc-slot-next {
  opacity: 0.38;
  filter: blur(1.5px);
  pointer-events: none;
}
.lc-slot-prev { clip-path: inset(0 0 0 45%); }
.lc-slot-next { clip-path: inset(0 45% 0 0); }
.lc-slot-prev .lc-body,
.lc-slot-next .lc-body { display: none; }
.lc-slot-prev .lc-img,
.lc-slot-next .lc-img  { height: 300px; border-radius: 16px; overflow: hidden; }

.lc-slot-active { opacity: 1; filter: none; }

/* Card */
.lc-card {
  background: #fff;
  border-radius: 16px;
  overflow: hidden;
  border: 1.5px solid #e8edf5;
  box-shadow: 0 2px 12px rgba(0,0,0,0.07);
  display: flex;
  flex-direction: column;
  transition: box-shadow 0.3s, border-color 0.3s;
}
.lc-slot-active .lc-card {
  border-color: #c7d9ff;
  box-shadow: 0 12px 40px rgba(13,110,253,0.18);
}

/* Image */
.lc-img { height: 260px; overflow: hidden; flex-shrink: 0; }
.lc-img img {
  width: 100%; height: 100%; object-fit: cover; display: block;
  transition: transform 0.5s ease;
}
.lc-slot-active .lc-card:hover .lc-img img { transform: scale(1.04); }

/* Body */
.lc-body { padding: 20px 22px 24px; flex: 1; }
.lc-tags { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 10px; }
.lc-tag {
  font-size: 0.68rem; font-weight: 700;
  padding: 3px 11px; border-radius: 20px;
  letter-spacing: 0.02em;
}
.lc-tag-blue   { background:#dbeafe; color:#1d4ed8; }
.lc-tag-green  { background:#dcfce7; color:#15803d; }
.lc-tag-red    { background:#fee2e2; color:#b91c1c; }
.lc-tag-yellow { background:#fef9c3; color:#a16207; }
.lc-tag-teal   { background:#ccfbf1; color:#0f766e; }
.lc-title { font-size: 1.1rem; font-weight: 700; color: #1a1a2e; margin: 0 0 8px; }
.lc-desc  { color: #6c757d; font-size: 0.85rem; line-height: 1.7; margin: 0; }

/* Arrow buttons */
.lc-btn {
  position: absolute;
  top: 50%; transform: translateY(-50%);
  z-index: 10;
  width: 46px; height: 46px; border-radius: 50%;
  border: none; background: #fff;
  box-shadow: 0 4px 18px rgba(0,0,0,0.15);
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; color: #333;
  transition: background 0.2s, color 0.2s, transform 0.2s, box-shadow 0.2s;
}
.lc-btn:hover {
  background: #0d6efd; color: #fff;
  transform: translateY(-50%) scale(1.08);
  box-shadow: 0 6px 20px rgba(13,110,253,0.35);
}
.lc-btn:disabled { opacity: 0.2; pointer-events: none; }
.lc-btn svg { width: 20px; height: 20px; }
.lc-btn-prev { left: 80px; }
.lc-btn-next { right: 80px; }

/* Dots */
.lc-dots { display: flex; justify-content: center; gap: 8px; margin-top: 24px; }
.lc-dot {
  width: 8px; height: 8px; border-radius: 50%;
  border: none; background: #c5cfe8; cursor: pointer; padding: 0;
  transition: background 0.25s, width 0.25s, border-radius 0.25s;
}
.lc-dot.active { background: #0d6efd; width: 24px; border-radius: 4px; }

/* Slide animations */
@keyframes lcFromRight { from { opacity:0; transform:translateX(40px); } to { opacity:1; transform:none; } }
@keyframes lcFromLeft  { from { opacity:0; transform:translateX(-40px); } to { opacity:1; transform:none; } }
.lc-viewport.dir-next .lc-slot-active .lc-card { animation: lcFromRight 0.38s cubic-bezier(.25,.8,.25,1) both; }
.lc-viewport.dir-prev .lc-slot-active .lc-card { animation: lcFromLeft  0.38s cubic-bezier(.25,.8,.25,1) both; }

/* =============================================
   TENAGA MEDIS
   ============================================= */
.tenaga-section { padding: 64px 0; background: #f8f9fc; }

.doctor-card {
  background: #fff;
  border-radius: 16px;
  border: 1.5px solid #e8edf5;
  box-shadow: 0 2px 12px rgba(0,0,0,0.05);
  transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
}
.doctor-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 32px rgba(0,0,0,0.12);
  border-color: #0d6efd;
}
.doctor-avatar {
  width: 110px; height: 110px; object-fit: cover;
  border: 3px solid #e8edf5;
}

/* =============================================
   MAP
   ============================================= */
.map-section { padding: 64px 0; background: #fff; }
.map-wrapper {
  width: 100%;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 24px rgba(0,0,0,0.10);
}
.map-embed {
  width: 100%; height: 400px;
  border: none; display: block;
}

/* =============================================
   RESPONSIVE — TABLET (768px)
   ============================================= */
@media (max-width: 991px) {
  .lc-viewport {
    grid-template-columns: 120px 1fr 120px;
    gap: 14px;
  }
  .lc-btn-prev { left: 40px; }
  .lc-btn-next { right: 40px; }
  .lc-img,
  .lc-slot-prev .lc-img,
  .lc-slot-next .lc-img { height: 210px; }
}

/* =============================================
   RESPONSIVE — MOBILE (≤ 600px)
   ============================================= */
@media (max-width: 600px) {
  /* Hide side slots entirely on mobile; show only active card */
  .lc-slot-prev,
  .lc-slot-next { display: none; }
  .lc-viewport {
    grid-template-columns: 1fr;
    padding: 8px 16px;
    gap: 0;
    overflow: visible;
  }
  .lc-img { height: 210px; }
  .lc-slot-active { min-height: unset; }
  .lc-btn-prev { left: 0; }
  .lc-btn-next { right: 0; }
  .lc-btn { width: 38px; height: 38px; }
  .lc-btn svg { width: 17px; height: 17px; }

  .layanan-section  { padding: 48px 0 40px; }
  .tenaga-section   { padding: 48px 0; }
  .map-section      { padding: 48px 0; }
  .map-embed        { height: 280px; }

  /* Stack doctor cards full-width on small phones */
  .doctor-card { border-radius: 14px; }
}
</style>

<script>
(function () {
  const raw   = document.getElementById('layananData').dataset.cards;
  const cards = JSON.parse(raw);
  const TOTAL = cards.length;
  const AUTO  = 4000;

  let cur      = 0;
  let timer    = null;
  let animating = false;

  const viewport  = document.getElementById('lcViewport');
  const slotPrev  = document.getElementById('lcSlotPrev');
  const slotActive= document.getElementById('lcSlotActive');
  const slotNext  = document.getElementById('lcSlotNext');
  const btnPrev   = document.getElementById('lcPrev');
  const btnNext   = document.getElementById('lcNext');
  const dots      = [...document.querySelectorAll('.lc-dot')];

  function buildCard(d) {
    const tagsHtml = d.tags.map(t =>
      `<span class="lc-tag lc-tag-${t.c}">${t.label}</span>`
    ).join('');
    return `
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

  function renderCard(slot, idx) {
    slot.innerHTML = buildCard(cards[((idx % TOTAL) + TOTAL) % TOTAL]);
  }

  function render() {
    renderCard(slotPrev,   cur - 1);
    renderCard(slotActive, cur);
    renderCard(slotNext,   cur + 1);
    dots.forEach((d, i) => d.classList.toggle('active', i === cur));
  }

  function goTo(idx) {
    if (animating) return;
    animating = true;
    const next = ((idx % TOTAL) + TOTAL) % TOTAL;
    const dir  = idx >= cur ? 'dir-next' : 'dir-prev';
    viewport.classList.remove('dir-next', 'dir-prev');
    cur = next;
    render();
    viewport.offsetWidth; // force reflow
    viewport.classList.add(dir);
    setTimeout(() => { animating = false; }, 420);
  }

  function startAuto() {
    clearInterval(timer);
    timer = setInterval(() => goTo(cur + 1), AUTO);
  }
  function stopAuto() { clearInterval(timer); }

  btnPrev.addEventListener('click', () => { goTo(cur - 1); startAuto(); });
  btnNext.addEventListener('click', () => { goTo(cur + 1); startAuto(); });
  dots.forEach((d, i) => d.addEventListener('click', () => { goTo(i); startAuto(); }));

  viewport.addEventListener('mouseenter', stopAuto);
  viewport.addEventListener('mouseleave', startAuto);

  /* Touch swipe */
  let tx = 0;
  slotActive.addEventListener('touchstart', e => { tx = e.touches[0].clientX; }, { passive: true });
  slotActive.addEventListener('touchend',   e => {
    const dx = e.changedTouches[0].clientX - tx;
    if (Math.abs(dx) > 40) { goTo(cur + (dx < 0 ? 1 : -1)); startAuto(); }
  });

  /* Dynamic arrow positioning */
  function positionArrows() {
    const isMobile = window.innerWidth <= 600;
    if (isMobile) return; // CSS handles mobile positioning
    const prevRect  = slotPrev.getBoundingClientRect();
    const nextRect  = slotNext.getBoundingClientRect();
    const outerRect = document.querySelector('.lc-outer').getBoundingClientRect();
    btnPrev.style.left  = (prevRect.left  - outerRect.left  + prevRect.width  / 2 - 23) + 'px';
    btnNext.style.right = (outerRect.right - nextRect.right + nextRect.width / 2 - 23) + 'px';
    btnNext.style.left  = '';
  }

  render();
  startAuto();
  requestAnimationFrame(positionArrows);
  window.addEventListener('resize', positionArrows);
})();
</script>

@endsection