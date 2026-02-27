@extends('layouts.app')

@section('title', 'Apotek Online')

@section('content')
<div class="section">
    <div class="container">

        {{-- Page Header --}}
        <h2 class="text-center fw-bold mb-2">Katalog Apotek</h2>
        <p class="text-center text-muted mb-4">
            Beli obat dan produk kesehatan dengan mudah dan aman
        </p>

        {{-- Search Input --}}
        <div class="mb-4">
            <input
                type="text"
                id="searchInput"
                class="form-control"
                placeholder="🔍 Cari obat atau produk kesehatan..."
            >
        </div>

        {{-- Category Filter --}}
        <div class="mb-4 d-flex gap-2 flex-wrap">
            <button class="btn btn-brown btn-sm filter-btn active-filter" data-filter="all">Semua Produk</button>
            <button class="btn btn-outline-brown btn-sm filter-btn" data-filter="minyak">Minyak Gosok</button>
            <button class="btn btn-outline-brown btn-sm filter-btn" data-filter="herbal">Obat Herbal</button>
            <button class="btn btn-outline-brown btn-sm filter-btn" data-filter="suplemen">Suplemen</button>
            <button class="btn btn-outline-brown btn-sm filter-btn" data-filter="sirup">Sirup</button>
            <button class="btn btn-outline-brown btn-sm filter-btn" data-filter="newest">Produk Terbaru</button>
        </div>

        {{-- No Result Message --}}
        <div id="noResult" class="text-center text-muted py-5 d-none">
            <p class="fs-5">😕 Produk tidak ditemukan.</p>
        </div>

        {{-- Products Grid --}}
        <div class="row g-4" id="productGrid">
            @forelse($products as $product)
                <div class="col-md-4 col-lg-3 product-item"
                     data-name="{{ strtolower($product['name']) }}"
                     data-category="{{ $product['category'] }}"
                     data-new="{{ $product['is_new'] ? '1' : '0' }}">
                    <div class="product-card h-100 d-flex flex-column">

                        {{-- Product Image --}}
                        <img
                            src="{{ asset($product['image']) }}"
                            class="product-img"
                            alt="{{ $product['name'] }}"
                            loading="lazy"
                            onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'300\' height=\'300\'%3E%3Crect width=\'300\' height=\'300\' fill=\'%23f0f0f0\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' font-size=\'14\' text-anchor=\'middle\' dominant-baseline=\'middle\' fill=\'%23999\'%3ENo Image%3C/text%3E%3C/svg%3E'"
                        >

                        <div class="product-body d-flex flex-column flex-grow-1">
                            {{-- Product Name --}}
                            <h6 class="mt-3 fw-semibold product-name">{{ $product['name'] }}</h6>

                            {{-- Description --}}
                            @if(!empty($product['description']))
                                <p class="text-muted small product-desc">{{ Str::limit($product['description'], 80) }}</p>
                            @endif

                            {{-- Price --}}
                            <p class="text-primary fw-bold mt-auto mb-2">
                                Rp {{ number_format($product['price'], 0, ',', '.') }}
                            </p>

                            {{-- Buy Button --}}
                            @if(!empty($product['link']))
                                <a href="{{ $product['link'] }}" target="_blank" rel="noopener noreferrer" class="btn btn-brown w-100">
                                    Beli di Tokopedia
                                </a>
                            @else
                                <button class="btn btn-secondary w-100" disabled>Tidak Tersedia</button>
                            @endif
                        </div>

                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">
                    <p class="fs-5">Belum ada produk tersedia.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
.product-card {
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
    background: white;
    position: relative;
    overflow: hidden;
}
.product-card:hover {
    box-shadow: 0 6px 16px rgba(0,0,0,0.12);
    transform: translateY(-5px);
}
.product-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
    background-color: #f0f0f0;
}
.product-name {
    font-size: 0.95rem;
    line-height: 1.4;
    min-height: 2.8rem;
}
.product-desc {
    font-size: 0.8rem;
    line-height: 1.4;
}
.btn-brown {
    background-color: #8B4513;
    color: white;
    border: 1px solid #8B4513;
    transition: background-color 0.2s;
}
.btn-brown:hover, .btn-brown:focus {
    background-color: #6B3410;
    color: white;
}
.btn-outline-brown {
    background-color: transparent;
    color: #8B4513;
    border: 1px solid #8B4513;
    transition: all 0.2s;
}
.btn-outline-brown:hover, .btn-outline-brown:focus {
    background-color: #8B4513;
    color: white;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput  = document.getElementById('searchInput');
    const productItems = document.querySelectorAll('.product-item');
    const filterBtns   = document.querySelectorAll('.filter-btn');
    const noResult     = document.getElementById('noResult');
    let activeCategory = 'all';

    searchInput.addEventListener('keyup', function () {
        filterProducts(this.value.toLowerCase().trim(), activeCategory);
    });

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            filterBtns.forEach(b => {
                b.classList.remove('btn-brown');
                b.classList.add('btn-outline-brown');
            });
            this.classList.remove('btn-outline-brown');
            this.classList.add('btn-brown');
            activeCategory = this.dataset.filter;
            filterProducts(searchInput.value.toLowerCase().trim(), activeCategory);
        });
    });

    function filterProducts(keyword, category) {
        let visibleCount = 0;

        productItems.forEach(item => {
            const matchName     = item.dataset.name.includes(keyword);
            const matchCategory = category === 'all'
                || (category === 'newest' && item.dataset.new === '1')
                || (category !== 'newest' && item.dataset.category === category);

            const visible = matchName && matchCategory;
            item.style.display = visible ? '' : 'none';
            if (visible) visibleCount++;
        });

        noResult.classList.toggle('d-none', visibleCount > 0);
    }
});
</script>
@endpush