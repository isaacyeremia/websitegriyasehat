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
            <button class="btn btn-brown btn-sm filter-btn" data-filter="all">
                Semua Produk
            </button>
            <button class="btn btn-outline-brown btn-sm filter-btn" data-filter="minyak">
                Minyak Gosok
            </button>
            <button class="btn btn-outline-brown btn-sm filter-btn" data-filter="herbal">
                Obat Herbal
            </button>
            <button class="btn btn-outline-brown btn-sm filter-btn" data-filter="suplemen">
                Suplemen
            </button>
            <button class="btn btn-outline-brown btn-sm filter-btn" data-filter="sirup">
                Sirup
            </button>
            <button class="btn btn-outline-brown btn-sm filter-btn" data-filter="database">
                Produk Terbaru
            </button>
        </div>

        {{-- Products Grid --}}
        <div class="row g-4" id="productGrid">
            @foreach($products as $product)
                <div class="col-md-4 col-lg-3 product-item"
                     data-name="{{ strtolower($product['name']) }}"
                     data-category="{{ $product['category'] }}">
                    <div class="product-card h-100">
                        {{-- Product Image --}}
                        @if(isset($product['category']) && $product['category'] == 'database')
                            {{-- Database product (storage path) --}}
                            <img
                                src="{{ asset('storage/' . $product['image']) }}"
                                class="product-img"
                                alt="{{ $product['name'] }}"
                                onerror="this.src='https://via.placeholder.com/300x300?text=No+Image'"
                            >
                        @else
                            {{-- Hardcoded product (public path) --}}
                            <img
                                src="{{ asset($product['image']) }}"
                                class="product-img"
                                alt="{{ $product['name'] }}"
                                onerror="this.src='https://via.placeholder.com/300x300?text=No+Image'"
                            >
                        @endif

                        {{-- Product Name --}}
                        <h6 class="mt-3 fw-semibold">{{ $product['name'] }}</h6>

                        {{-- Product Price --}}
                        <p class="text-primary fw-bold">
                            Rp {{ number_format($product['price'], 0, ',', '.') }}
                        </p>

                        {{-- Buy Button --}}
                        @if(isset($product['link']) && $product['link'])
                            <a href="{{ $product['link'] }}"
                               target="_blank"
                               class="btn btn-brown w-100">
                                Beli di Tokopedia
                            </a>
                        @else
                            <button class="btn btn-secondary w-100" disabled>
                                Tidak Tersedia
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ================================
   PRODUCT CARD STYLING
   ================================ */
.product-card {
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
    background: white;
}

.product-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-5px);
}

.product-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
}

/* ================================
   BUTTON STYLING
   ================================ */
.btn-brown {
    background-color: #8B4513;
    color: white;
    border: 1px solid #8B4513;
}

.btn-brown:hover {
    background-color: #6B3410;
    color: white;
}

.btn-outline-brown {
    background-color: transparent;
    color: #8B4513;
    border: 1px solid #8B4513;
}

.btn-outline-brown:hover {
    background-color: #8B4513;
    color: white;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const searchInput = document.getElementById('searchInput');
    const productItems = document.querySelectorAll('.product-item');
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    // Active filter state
    let activeCategory = 'all';

    // ================================
    // SEARCH FUNCTIONALITY
    // ================================
    searchInput.addEventListener('keyup', function() {
        filterProducts(this.value.toLowerCase(), activeCategory);
    });

    // ================================
    // FILTER BUTTON FUNCTIONALITY
    // ================================
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update button styles
            filterButtons.forEach(b => {
                b.classList.remove('btn-brown');
                b.classList.add('btn-outline-brown');
            });

            this.classList.remove('btn-outline-brown');
            this.classList.add('btn-brown');

            // Update active category
            activeCategory = this.dataset.filter;
            
            // Filter products
            filterProducts(searchInput.value.toLowerCase(), activeCategory);
        });
    });

    // ================================
    // FILTER PRODUCTS FUNCTION
    // ================================
    function filterProducts(keyword, category) {
        productItems.forEach(item => {
            const name = item.dataset.name;
            const itemCategory = item.dataset.category;

            const matchName = name.includes(keyword);
            const matchCategory = category === 'all' || itemCategory === category;

            // Show/hide based on filters
            item.style.display = (matchName && matchCategory) ? '' : 'none';
        });
    }
});
</script>
@endpush