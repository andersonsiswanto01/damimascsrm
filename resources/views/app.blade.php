@include('header')

<main class="container-xl px-0 flex-grow-1">
  
  <section class="bg-white pt-4 pb-5 pt-md-5 pb-md-5">

    

<div id="myCarousel" class="carousel slide mb-6" data-bs-ride="carousel">
        <div class="carousel-indicators">
          <button
            type="button"
            data-bs-target="#myCarousel"
            data-bs-slide-to="0"
            class="active"
            aria-current="true"
            aria-label="Slide 1"
          ></button>
          <button
            type="button"
            data-bs-target="#myCarousel"
            data-bs-slide-to="1"
            aria-label="Slide 2"
          ></button>
          <button
            type="button"
            data-bs-target="#myCarousel"
            data-bs-slide-to="2"
            aria-label="Slide 3"
          ></button>
        </div>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <svg
              aria-hidden="true"
              class="bd-placeholder-img"
              height="100%"
              preserveAspectRatio="xMidYMid slice"
              width="100%"
              xmlns="http://www.w3.org/2000/svg"
            >
              <rect
                width="100%"
                height="100%"
                fill="var(--bs-secondary-color)"
              ></rect>
            </svg>
            <div class="container">
              <div class="carousel-caption text-start">
                <h1>Example headline.</h1>
                <p class="opacity-75">
                  Some representative placeholder content for the first slide of
                  the carousel.
                </p>
                <p>
                  <a class="btn btn-lg btn-primary" href="#">Sign up today</a>
                </p>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <svg
              aria-hidden="true"
              class="bd-placeholder-img"
              height="100%"
              preserveAspectRatio="xMidYMid slice"
              width="100%"
              xmlns="http://www.w3.org/2000/svg"
            >
              <rect
                width="100%"
                height="100%"
                fill="var(--bs-secondary-color)"
              ></rect>
            </svg>
            <div class="container">
              <div class="carousel-caption">
                <h1>Another example headline.</h1>
                <p>
                  Some representative placeholder content for the second slide
                  of the carousel.
                </p>
                <p><a class="btn btn-lg btn-primary" href="#">Learn more</a></p>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <svg
              aria-hidden="true"
              class="bd-placeholder-img"
              height="100%"
              preserveAspectRatio="xMidYMid slice"
              width="100%"
              xmlns="http://www.w3.org/2000/svg"
            >
              <rect
                width="100%"
                height="100%"
                fill="var(--bs-secondary-color)"
              ></rect>
            </svg>
            <div class="container">
              <div class="carousel-caption text-end">
                <h1>One more for good measure.</h1>
                <p>
                  Some representative placeholder content for the third slide of
                  this carousel.
                </p>
                <p>
                  <a class="btn btn-lg btn-primary" href="#">Browse gallery</a>
                </p>
              </div>
            </div>
          </div>
        </div>
        <button
          class="carousel-control-prev"
          type="button"
          data-bs-target="#myCarousel"
          data-bs-slide="prev"
        >
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button
          class="carousel-control-next"
          type="button"
          data-bs-target="#myCarousel"
          data-bs-slide="next"
        >
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>


<section class="bg-light py-5">

  <!-- Section Title -->
  <h1 class="text-center fw-extrabold display-6 display-md-5 display-lg-4 mt-4 mb-5">
    Mengapa Memilih Dami Mas?
  </h1>

  <!-- Cards -->
  <div class="container">
    <div class="row justify-content-center g-4">

      <!-- Card 1 -->
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="flip-card h-100">
          <div class="flip-card-inner">

            <div class="flip-card-front d-flex align-items-center justify-content-center text-center">
              <h5 class="fw-semibold fs-4 text-dark">Benih Unggul</h5>
            </div>

            <div class="flip-card-back d-flex align-items-center justify-content-center text-center p-3">
              <p class="mb-0">
                Persilangan Deli Dura × Pisifera Avros dengan keunggulan Quick Starter,
                panen perdana lebih cepat ±24 bulan.
              </p>
            </div>

          </div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="flip-card h-100">
          <div class="flip-card-inner">

            <div class="flip-card-front d-flex align-items-center justify-content-center text-center">
              <h5 class="fw-semibold fs-4 text-dark">Harga Terjangkau</h5>
            </div>

            <div class="flip-card-back d-flex align-items-center justify-content-center text-center p-3">
              <p class="mb-0">
                Harga kompetitif dengan kualitas benih terjamin.
              </p>
            </div>

          </div>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="flip-card h-100">
          <div class="flip-card-inner">

            <div class="flip-card-front d-flex align-items-center justify-content-center text-center">
              <h5 class="fw-semibold fs-4 text-dark">Pengiriman Aman</h5>
            </div>

            <div class="flip-card-back d-flex align-items-center justify-content-center text-center p-3">
              <p class="mb-0">
                Pengiriman aman melalui jalur resmi ke seluruh Indonesia.
              </p>
            </div>

          </div>
        </div>
      </div>

      <!-- Card 4 -->
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="flip-card h-100">
          <div class="flip-card-inner">

            <div class="flip-card-front d-flex align-items-center justify-content-center text-center">
              <h5 class="fw-semibold fs-4 text-dark">Legal & Resmi</h5>
            </div>

            <div class="flip-card-back d-flex align-items-center justify-content-center text-center p-3">
              <p class="mb-0">
                Benih bersertifikat dan sesuai regulasi pemerintah.
              </p>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>

  <section class="bg-white py-5">
  <div class="container">

    <div class="text-center mb-4">
      <h2 class="fw-bold">Kegiatan & Edukasi Lapangan</h2>
      <p class="text-muted">
        Komitmen kami dalam mendampingi pekebun secara langsung
      </p>
    </div>

    <div class="row g-4">

      <!-- Event Card -->
      <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
          <img src="/events/workshop.jpg" class="card-img-top" alt="Event">

          <div class="card-body">
            <span class="badge bg-success mb-2">Workshop</span>

            <h6 class="fw-bold">
              Pelatihan Pemilihan Benih Sawit Unggul
            </h6>

            <p class="text-muted small mb-2">
              Kampar, Riau • 12 Mei 2025
            </p>

            <p class="small">
              Edukasi langsung kepada pekebun terkait benih DxP Dami Mas.
            </p>
          </div>
        </div>
      </div>

      <!-- Event Card -->
      <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
          <img src="/events/pameran.jpg" class="card-img-top" alt="Event">

          <div class="card-body">
            <span class="badge bg-primary mb-2">Pameran</span>

            <h6 class="fw-bold">
              Pameran Perkebunan Nasional
            </h6>

            <p class="text-muted small mb-2">
              Medan • 20 Juni 2025
            </p>

            <p class="small">
              Partisipasi resmi dalam pameran industri perkebunan.
            </p>
          </div>
        </div>
      </div>

      <!-- Event Card -->
      <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
          <img src="/events/field.jpg" class="card-img-top" alt="Event">

          <div class="card-body">
            <span class="badge bg-warning mb-2 text-dark">Field Visit</span>

            <h6 class="fw-bold">
              Kunjungan Kebun Mitra
            </h6>

            <p class="text-muted small mb-2">
              Kalimantan Barat • 3 Juli 2025
            </p>

            <p class="small">
              Monitoring pertumbuhan dan pendampingan teknis.
            </p>
          </div>
        </div>
      </div>

    </div>

    <div class="text-center mt-4">
      <a href="/events" class="btn btn-outline-danger">
        Lihat Semua Kegiatan
      </a>
    </div>

  </div>
</section>

<section class="bg-light py-5">

  <!-- Section Title -->
<h1 class="text-center fw-extrabold display-6 display-md-5 display-lg-4 mt-5 mb-4">
    Artikel Terbaru dari Blog Kami
</h1>
  <div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">

      <div class="card shadow-sm border-0 h-100">
        <img
          src="{{ asset('blog/cover.jpg') }}"
          class="card-img-top"
          alt="Recent blog post">

        <div class="card-body">
          <span class="badge bg-success mb-2">Blog Terbaru</span>

          <h5 class="card-title fw-bold mt-2">
            Cara Memilih Benih Sawit Berkualitas untuk Hasil Maksimal
          </h5>

          <p class="card-text text-muted">
            Panduan singkat bagi pekebun sawit dalam memilih benih unggul
            agar panen lebih cepat dan produktif.
          </p>

          <a href="/blog/cara-memilih-benih-sawit"
             class="btn btn-outline-danger">
            Baca Selengkapnya →
          </a>
        </div>

        <div class="card-footer bg-white border-0 text-muted small">
          Diposting 3 hari yang lalu
        </div>
      </div>

    </div>
  </div>
</div>


</section>

<h1 class="text-center fw-extrabold display-6 display-md-5 display-lg-4 mt-5 mb-4">
    Dipercaya dari berbagai perusahaan sawit di Indonesia dan Dunia
</h1>

  <div class="w-100 bg-white py-4 marquee-container mt-4 overflow-hidden">
  <div class="marquee-track d-flex">
    
    <!-- Group 1 -->
    <div class="d-flex gap-4 px-4 marquee-group">
      <div class="badge-item">PT Agro Sejahtera</div>
      <div class="badge-item">CV Tani Jaya</div>
      <div class="badge-item">UD Sawit Makmur</div>
      <div class="badge-item">KSU Karya Bersama</div>
      <div class="badge-item">PT Nusantara Palm</div>
      <div class="badge-item">CV Bumi Hijau</div>
    </div>

    <!-- Group 2 (duplicate) -->
    <div class="d-flex gap-4 px-4 marquee-group">
      <div class="badge-item">PT Agro Sejahtera</div>
      <div class="badge-item">CV Tani Jaya</div>
      <div class="badge-item">UD Sawit Makmur</div>
      <div class="badge-item">KSU Karya Bersama</div>
      <div class="badge-item">PT Nusantara Palm</div>
      <div class="badge-item">CV Bumi Hijau</div>
    </div>

  </div>
</div>
</main>
@include('footer')


