<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Griya Sehat')</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <!-- Custom CSS -->
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">

  <!-- ✅ FIX NAVBAR POSITIONING - ULTIMATE FIX -->
  <style>
    /* FORCE NAVBAR TO TOP - PRIORITAS TERTINGGI */
    nav.navbar,
    .navbar {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        width: 100% !important;
        z-index: 99999 !important;
        background-color: white !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
        margin: 0 !important;
    }
    
    /* Beri margin untuk main agar tidak tertutup navbar */
    main {
        margin-top: 80px !important;
        position: relative !important;
        z-index: 1 !important;
    }
    
    /* Card dan form tidak boleh menutupi navbar */
    .card,
    .card-auth {
        position: relative !important;
        z-index: 1 !important;
    }
    
    /* Container */
    .container,
    .container-fluid {
        position: relative !important;
        z-index: 1 !important;
    }
    
    /* Center screen untuk halaman login/register */
    .center-screen {
        position: relative !important;
        z-index: 1 !important;
        margin-top: 100px !important;
    }
    
    /* Body */
    body {
        position: relative !important;
        padding-top: 0 !important;
        margin-top: 0 !important;
    }
    
    /* Dropdown navbar jika ada */
    .navbar .dropdown-menu {
        z-index: 100000 !important;
    }
    
    /* Modal backdrop dan dialog */
    .modal-backdrop {
        z-index: 1040 !important;
    }
    
    .modal-dialog {
        z-index: 1050 !important;
    }
  </style>
</head>

<body>

  {{-- NAVBAR --}}
  <nav class="navbar">
    <div class="container">
      <a class="brand-logo d-flex align-items-center gap-2" href="{{ url('/') }}">
        <img src="{{ asset('logo.png') }}" alt="logo" style="height:44px;">
        <span>Griya Sehat UKDC</span>
      </a>

      {{-- MENU TENGAH --}}
      @if (!request()->is('login') && !request()->is('register'))
      <div class="nav-menu">
        <a href="{{ url('/') }}">Beranda</a>
        <a href="{{ route('profile') }}">Profile</a>
        <a href="{{ route('apotek.index') }}">Katalog</a>
        
        {{-- Link Dashboard Admin (hanya muncul jika user adalah admin) --}}
        @auth
          @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="text-danger fw-bold">
              <i class="bi bi-speedometer2"></i> Dashboard Admin
            </a>
          @endif
        @endauth
      </div>
      @endif

      {{-- ACTION --}}
      <div class="nav-actions">
        @auth
          <span class="me-3 text-muted">
            Halo, <strong>{{ Auth::user()->name }}</strong>
            @if(Auth::user()->isAdmin())
              <span class="badge bg-danger ms-1">Admin</span>
            @endif
          </span>
          <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button class="btn btn-outline-brown">Logout</button>
          </form>
        @else
          <a href="{{ route('login') }}" class="btn btn-outline-brown">Login</a>
          <a href="{{ route('register') }}" class="btn btn-brown">Daftar</a>
        @endauth
      </div>
    </div>
  </nav>

  {{-- CONTENT --}}
  <main>
    @yield('content')
  </main>

  {{-- FOOTER --}}
  @if (request()->is('login') || request()->is('register'))
    @include('components.footer-auth')
  @else
    @include('components.footer-full')
  @endif

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  {{-- Script dari halaman --}}
  @stack('scripts')

  {{-- JavaScript Backup - Force Navbar Position --}}
  <script>
    // Force navbar ke atas dengan JavaScript (backup jika CSS gagal)
    document.addEventListener('DOMContentLoaded', function() {
      const navbar = document.querySelector('nav.navbar');
      if (navbar) {
        navbar.style.position = 'fixed';
        navbar.style.top = '0';
        navbar.style.left = '0';
        navbar.style.right = '0';
        navbar.style.width = '100%';
        navbar.style.zIndex = '99999';
        navbar.style.backgroundColor = 'white';
        navbar.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';
      }
      
      const main = document.querySelector('main');
      if (main) {
        main.style.marginTop = '80px';
        main.style.position = 'relative';
        main.style.zIndex = '1';
      }
    });
  </script>

</body>
</html>