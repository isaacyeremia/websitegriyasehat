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
        z-index: 1030 !important;
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

    /* =====================================================
       FOOTER - paksa z-index rendah agar tidak tembus modal
       ===================================================== */
    footer,
    .footer,
    .footer-top,
    .footer-bottom,
    .footer-cols,
    .footer-col {
        position: relative !important;
        z-index: 0 !important;
    }

    /* ========================================================================
       MODAL & BACKDROP - FIX FOR HIGH DPI/SCALING LAPTOPS
    ======================================================================== */

    /* Hide backdrop completely - use modal background instead */
    .modal-backdrop {
        display: none !important;
    }

    /* Modal dengan background sendiri */
    .modal {
        z-index: 1055 !important;
        background-color: rgba(0, 0, 0, 0) !important;
    }

    .modal.show {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        background-color: rgba(0, 0, 0, 0.65) !important;
    }

    /* Modal Dialog */
    .modal-dialog {
        position: relative !important;
        z-index: 1060 !important;
        margin: 1.75rem auto !important;
        max-width: 500px !important;
    }

    /* Modal Dialog Centered */
    .modal-dialog-centered {
        display: flex !important;
        align-items: center !important;
        min-height: calc(100% - 3.5rem) !important;
    }

    /* Modal Dialog Scrollable */
    .modal-dialog-scrollable {
        height: auto !important;
        max-height: calc(100vh - 3.5rem) !important;
    }

    .modal-dialog-scrollable .modal-content {
        max-height: 100% !important;
        overflow: hidden !important;
    }

    .modal-dialog-scrollable .modal-body {
        overflow-y: auto !important;
        max-height: calc(100vh - 250px) !important;
    }

    /* Modal Content - putih solid agar footer tidak tembus */
    .modal-content {
        background-color: #ffffff !important;
        box-shadow: 0 15px 50px rgba(0,0,0,0.5) !important;
        border: none !important;
        border-radius: 12px !important;
        position: relative !important;
        z-index: 1061 !important;
        pointer-events: auto !important;
    }

    /* Modal Header - JANGAN override background,
       biarkan class bg-primary/bg-success dll dari blade bekerja */
    .modal-header {
        border-radius: 12px 12px 0 0 !important;
        flex-shrink: 0 !important;
    }

    /* Modal Body */
    .modal-body {
        background-color: #ffffff !important;
        position: relative !important;
    }

    /* Modal Footer */
    .modal-footer {
        background-color: #ffffff !important;
        flex-shrink: 0 !important;
    }

    /* Body saat modal terbuka */
    body.modal-open {
        overflow: hidden !important;
        padding-right: 0 !important;
    }

    /* Force ALL modal elements clickable */
    .modal *,
    .modal input,
    .modal select,
    .modal textarea,
    .modal button,
    .modal .btn,
    .modal .btn-close,
    .modal .form-control,
    .modal .form-select,
    .modal label,
    .modal .alert {
        pointer-events: auto !important;
        position: relative !important;
    }

    .modal input,
    .modal select,
    .modal button,
    .modal .btn {
        cursor: pointer !important;
    }

    .modal input[type="text"],
    .modal input[type="date"],
    .modal input[type="number"],
    .modal input[type="email"],
    .modal input[type="password"],
    .modal textarea {
        cursor: text !important;
    }

    /* Responsive */
    @media (max-width: 576px) {
        .modal-dialog {
            max-width: calc(100% - 1rem) !important;
            margin: 0.5rem !important;
        }
        
        .modal-dialog-centered {
            min-height: calc(100% - 1rem) !important;
        }
        
        .modal-dialog-scrollable {
            max-height: calc(100vh - 1rem) !important;
        }
        
        .modal-dialog-scrollable .modal-body {
            max-height: calc(100vh - 180px) !important;
        }
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
          
          @auth
            @if(Auth::user()->isAdmin())
              <a href="{{ route('admin.dashboard') }}" class="text-danger fw-bold">
                <i class="bi bi-speedometer2"></i> Dashboard Admin
              </a>
            @endif

            @if(Auth::user()->isTerapis())
              <a href="{{ route('terapis.dashboard') }}" class="text-primary fw-bold">
                <i class="bi bi-clipboard-heart"></i> Dashboard Terapis
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
              @elseif(Auth::user()->isTerapis())
                <span class="badge bg-primary ms-1">Terapis</span>
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

      // Modal fix - remove backdrop and use modal background
      document.addEventListener('show.bs.modal', function(event) {
        const modal = event.target;

        // Remove any existing backdrop
        document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());

        // Set modal background
        setTimeout(() => {
          modal.style.backgroundColor = 'rgba(0, 0, 0, 0.65)';
          modal.style.display = 'flex';
          modal.style.alignItems = 'center';
          modal.style.justifyContent = 'center';

          // Force all elements clickable
          modal.querySelectorAll('*').forEach(el => {
            el.style.pointerEvents = 'auto';
          });
        }, 10);
      });

      // Clean up when modal closes
      document.addEventListener('hidden.bs.modal', function(event) {
        const modal = event.target;
        modal.style.backgroundColor = '';

        // Clean up any leftover backdrops
        document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());

        // Reset body
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
      });
    });
  </script>

</body>
</html>