<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Benih Dami Mas')</title>
  <link rel="icon" type="image/png" href="{{ asset('logo/thumbnail.png') }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
      .marquee-container {
      overflow: hidden;
      position: relative;
    }

    .marquee-track {
      display: flex;
      width: fit-content;
      animation: scroll-left 20s linear infinite;
    }

    @keyframes scroll-left {
      0% {
        transform: translateX(0);
      }
      100% {
        transform: translateX(-50%);
      }
    }
</style>

</head>
<body class="bg-white text-gray-800 flex flex-col min-h-screen">

  <!-- ✅ Flowbite Navbar -->
  <nav class="bg-white border-gray-200 sticky top-0 z-50 shadow">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
      <a href="/" class="flex items-center space-x-2">
        <img src="{{ asset('logo/No Tagline.png') }}" alt="Logo" class="h-8 w-auto">
         </a>
  
      <!-- Hamburger button -->
      <button data-collapse-toggle="navbar-default" type="button"
              class="inline-flex items-center p-2 w-10 h-10 justify-center text-gray-500 rounded-lg md:hidden hover:bg-gray-100"
              aria-controls="navbar-default" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd"
                d="M3 5h14a1 1 0 010 2H3a1 1 0 110-2zm0 6h14a1 1 0 010 2H3a1 1 0 110-2zm0 6h14a1 1 0 010 2H3a1 1 0 110-2z"
                clip-rule="evenodd"></path>
        </svg>
      </button>
  
      <!-- Collapsible menu -->
      <div class="hidden w-full md:block md:w-auto" id="navbar-default">
        <ul class="font-medium flex flex-col md:flex-row md:space-x-11 mt-4 md:mt-0">
          <li><a href="#tentang" class="block py-2 px-3 hover:text-red-600">Tentang</a></li>
          <li><a href="#produk" class="block py-2 px-3 hover:text-red-600">Produk</a></li>
          <li><a href="#blog" class="block py-2 px-3 hover:text-red-600">Blog</a></li>
          <li><a href="#galeri" class="block py-2 px-3 hover:text-red-600">Galeri</a></li>
          <li><a href="#kontak" class="block py-2 px-3 hover:text-red-600">Kontak</a></li>
          {{-- <li>

         
          </li> --}}
        </ul>
      </div>
    </div>
  </nav>
  
