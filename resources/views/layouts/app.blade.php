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
</head>

<body>

  {{-- NAVBAR --}}
  <nav class="navbar">
    <div class="container">
      <a class="brand-logo d-flex align-items-center gap-2" href="{{ url('/home') }}">
        <img src="{{ asset('logo.png') }}" alt="logo" style="height:44px;">
        <span>Griya Sehat UKDC</span>
      </a>

      {{-- MENU TENGAH --}}
      @if (!request()->is('login') && !request()->is('register'))
      <div class="nav-menu">
        <a href="{{ url('/home') }}">Beranda</a>
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

</body>
</html>