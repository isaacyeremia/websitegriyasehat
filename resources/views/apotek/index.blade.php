@extends('layouts.app')

@section('title','Apotek Online')

@section('content')
<div class="section">
  <div class="container">

    <h2 class="text-center fw-bold mb-2">Katalog Apotek</h2>
    <p class="text-center text-muted mb-4">
      Beli obat dan produk kesehatan dengan mudah dan aman
    </p>

    {{-- SEARCH --}}
    <div class="mb-4">
      <input
        type="text"
        id="searchInput"
        class="form-control"
        placeholder="Cari obat atau produk kesehatan..."
      >
    </div>

    {{-- FILTER KATEGORI --}}
    <div class="mb-4 d-flex gap-2 flex-wrap">
      <button class="btn btn-brown btn-sm filter-btn" data-filter="all">Semua Produk</button>
      <button class="btn btn-outline-brown btn-sm filter-btn" data-filter="minyak">Minyak Gosok</button>
      <button class="btn btn-outline-brown btn-sm filter-btn" data-filter="herbal">Obat Herbal</button>
      <button class="btn btn-outline-brown btn-sm filter-btn" data-filter="suplemen">Suplemen</button>
      <button class="btn btn-outline-brown btn-sm filter-btn" data-filter="sirup">Sirup</button>
    </div>

    {{-- PRODUK --}}
    <div class="row g-4" id="productGrid">
      @foreach($products as $product)
      <div
        class="col-md-4 col-lg-3 product-item"
        data-name="{{ strtolower($product['name']) }}"
        data-category="{{ $product['category'] }}"
      >
        <div class="product-card h-100">
          <img
            src="{{ asset($product['image']) }}"
            class="product-img"
            alt="{{ $product['name'] }}"
          >

          <h6 class="mt-3 fw-semibold">{{ $product['name'] }}</h6>

          <p class="text-primary fw-bold">
            Rp{{ number_format($product['price'],0,',','.') }}
          </p>

          <a
            href="{{ $product['link'] }}"
            target="_blank"
            class="btn btn-brown w-100"
          >
            Beli di Tokopedia
          </a>
        </div>
      </div>
      @endforeach
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

  const searchInput = document.getElementById('searchInput');
  const productItems = document.querySelectorAll('.product-item');
  const filterButtons = document.querySelectorAll('.filter-btn');

  let activeCategory = 'all';

  // SEARCH
  searchInput.addEventListener('keyup', function () {
    filterProducts(this.value.toLowerCase(), activeCategory);
  });

  // FILTER BUTTON
  filterButtons.forEach(btn => {
    btn.addEventListener('click', function () {

      filterButtons.forEach(b => {
        b.classList.remove('btn-brown');
        b.classList.add('btn-outline-brown');
      });

      this.classList.remove('btn-outline-brown');
      this.classList.add('btn-brown');

      activeCategory = this.dataset.filter;
      filterProducts(searchInput.value.toLowerCase(), activeCategory);
    });
  });

  function filterProducts(keyword, category) {
    productItems.forEach(item => {
      const name = item.dataset.name;
      const itemCategory = item.dataset.category;

      const matchName = name.includes(keyword);
      const matchCategory = category === 'all' || itemCategory === category;

      item.style.display = (matchName && matchCategory) ? '' : 'none';
    });
  }

});
</script>
@endpush
