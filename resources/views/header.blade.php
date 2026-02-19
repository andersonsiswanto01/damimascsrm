<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Benih Dami Mas')</title>
  <link rel="icon" type="image/png" href="{{ asset('logo/thumbnail.png') }}">

  @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/custom.css'])
  <script src="/js/flowbite.min.js"></script>
</head>
<body class="bg-white text-gray-800 flex flex-col min-h-screen">

  <!-- ✅ Flowbite Navbar -->
  
<nav class="navbar navbar-expand-md bg-white sticky-top shadow border-bottom">
  <div class="container-xl">

    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center" href="/">
      <img src="{{ asset('logo/No Tagline.png') }}" alt="Logo" height="32">
    </a>

    <!-- Hamburger -->
    <button class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarDefault"
            aria-controls="navbarDefault"
            aria-expanded="false"
            aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu -->
    <div class="collapse navbar-collapse" id="navbarDefault">
      <ul class="navbar-nav ms-auto mb-2 mb-md-0 gap-md-4 fw-medium">

        <li class="nav-item">
          <a class="nav-link" href="about">Tentang</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#tentang">Penangkar</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#produk">Produk</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#blog">Blog</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#kontak">Kontak</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#kontak">Cek Pesanan</a>
        </li>

      </ul>
    </div>
  </div>
</nav>

  
