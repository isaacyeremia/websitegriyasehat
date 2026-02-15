<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Griya Sehat')</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="/logo.png">
  <link rel="shortcut icon" type="image/png" href="/logo.png">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <!-- Custom CSS -->
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">

  <style>
    /* ===== NAVBAR FIXED TOP ===== */
    .navbar {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        width: 100% !important;
        z-index: 9999 !important;
        background-color: white !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
        padding: 0.75rem 0 !important;
    }
    
    /* Main content margin */
    main {
        margin-top: 70px !important;
    }
    
    /* Brand Logo */
    .brand-logo {
        text-decoration: none;
        color: #333;
        font-weight: 600;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .brand-logo:hover {
        color: #8B4513;
    }

    /* Custom Burger Icon */
    .navbar-toggler {
        border: 1px solid rgba(0,0,0,.1);
        padding: 0.5rem;
        background-color: transparent;
        cursor: pointer;
    }

    .navbar-toggler:focus {
        box-shadow: none;
        outline: none;
    }

    /* Burger Lines */
    .navbar-toggler-icon {
        display: block;
        width: 25px;
        height: 2px;
        background-color: #333;
        position: relative;
        transition: all 0.3s;
    }

    .navbar-toggler-icon::before,
    .navbar-toggler-icon::after {
        content: '';
        display: block;
        width: 25px;
        height: 2px;
        background-color: #333;
        position: absolute;
        left: 0;
        transition: all 0.3s;
    }

    .navbar-toggler-icon::before {
        top: -8px;
    }

    .navbar-toggler-icon::after {
        top: 8px;
    }

    /* Desktop View */
    @media (min-width: 992px) {
        .navbar-toggler {
            display: none !important;
        }

        .navbar-collapse {
            display: flex !important;
            flex-basis: auto;
        }

        .navbar-nav-center {
            display: flex;
            gap: 2rem;
            align-items: center;
            margin: 0 auto;
        }

        .navbar-nav-center a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
        }

        .navbar-nav-center a:hover {
            color: #8B4513;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .navbar-actions .user-info {
            margin-right: 0.5rem;
        }
    }

    /* Mobile View */
    @media (max-width: 991px) {
        .navbar-collapse {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 1rem;
            margin-top: 0.5rem;
        }

        .navbar-collapse.show {
            display: block !important;
        }

        .navbar-collapse:not(.show) {
            display: none !important;
        }

        .navbar-nav-center {
            display: flex;
            flex-direction: column;
            gap: 0;
            width: 100%;
            margin-bottom: 1rem;
        }

        .navbar-nav-center a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            padding: 0.75rem 0.5rem;
            border-bottom: 1px solid #eee;
            display: block;
            width: 100%;
        }

        .navbar-nav-center a:last-child {
            border-bottom: none;
        }

        .navbar-nav-center a:hover {
            background-color: #f8f9fa;
            color: #8B4513;
        }

        .navbar-actions {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            width: 100%;
        }

        .navbar-actions .user-info {
            text-align: center;
            margin-bottom: 0.5rem;
            display: block;
            width: 100%;
        }

        .navbar-actions .btn,
        .navbar-actions form,
        .navbar-actions form button {
            width: 100%;
        }
    }

    /* Card, Modal, etc */
    .card,
    .card-auth {
        position: relative !important;
        z-index: 1 !important;
    }
    
    .center-screen {
        position: relative !important;
        z-index: 1 !important;
        margin-top: 100px !important;
    }

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
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      {{-- Logo/Brand --}}
      <a class="brand-logo" href="{{ url('/') }}">
        <img src="{{ asset('logo.png') }}" alt="logo" style="height:40px;">
        <span class="d-none d-sm-inline">Griya Sehat UKDC</span>
      </a>

      {{-- Burger Button (Only on mobile and not on login/register) --}}
      @if (!request()->is('login') && !request()->is('register'))
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      @endif

      {{-- Collapsible Menu --}}
      <div class="collapse navbar-collapse" id="navbarMenu">
        {{-- Center Menu (Beranda, Profile, Katalog) --}}
        @if (!request()->is('login') && !request()->is('register'))
        <div class="navbar-nav-center">
          <a href="{{ url('/') }}">Beranda</a>
          <a href="{{ route('profile') }}">Profile</a>
          <a href="{{ route('apotek.index') }}">Katalog</a>
          
          {{-- Admin Dashboard Link --}}
          @auth
            @if(Auth::user()->isAdmin())
              <a href="{{ route('admin.dashboard') }}" class="text-danger fw-bold">
                <i class="bi bi-speedometer2"></i> Dashboard Admin
              </a>
            @endif
          @endauth
        </div>
        @endif

        {{-- Right Side Actions (Login/Logout) --}}
        <div class="navbar-actions ms-lg-auto">
          @auth
            <span class="user-info text-muted">
              Halo, <strong>{{ Auth::user()->name }}</strong>
              @if(Auth::user()->isAdmin())
                <span class="badge bg-danger ms-1">Admin</span>
              @endif
            </span>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button class="btn btn-outline-brown">Logout</button>
            </form>
          @else
            <a href="{{ route('login') }}" class="btn btn-outline-brown">Login</a>
            <a href="{{ route('register') }}" class="btn btn-brown">Daftar</a>
          @endauth
        </div>
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

  <!-- Bootstrap JS Bundle (includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  {{-- Custom Scripts --}}
  @stack('scripts')

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Force navbar position
      const navbar = document.querySelector('.navbar');
      if (navbar) {
        navbar.style.position = 'fixed';
        navbar.style.top = '0';
        navbar.style.zIndex = '9999';
      }

      // Auto-close menu on link click (mobile)
      const navLinks = document.querySelectorAll('.navbar-nav-center a');
      const navbarCollapse = document.getElementById('navbarMenu');
      
      if (navbarCollapse && navLinks.length > 0) {
        navLinks.forEach(link => {
          link.addEventListener('click', () => {
            if (window.innerWidth < 992) {
              const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
              if (bsCollapse) {
                bsCollapse.hide();
              }
            }
          });
        });
      }
    });
  </script>

</body>
</html>