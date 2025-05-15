@include('header')
<main class="max-w-7xl mx-auto px-4 py-0 flex-grow" style="
                padding-right: 0px;
                padding-left: 0px;
                ">
    <section class="bg-white pt-4 pb-8 antialiased dark:bg-gray-900 md:pt-6 md:pb-12"
    <!-- FULL-WIDTH BACKGROUND -->
    <section class="w-full relative bg-cover bg-center bg-no-repeat pt-4 pb-0 md:pt-6 md:pb-12 rounded-2xl overflow-hidden"
    style="background-image: url('{{ asset('landing/background.jpg') }}');">

    <div class="mx-auto grid max-w-screen-l px-8 pb-8 md:grid-cols-12 md:px-16 md:pb-0 lg:gap-12 xl:gap-0">
            <div class="content-center justify-self-start md:col-span-7 md:text-start">
                <p class="mb-4 max-w-2xl font-bold text-white dark:text-white md:text-lg lg:text-xl sm:mt-8 md:mt-0" style="transform: translateY(0);">
                    Hasil yang Luar Biasa Bermula dari Awal yang Baik
                </p>
                <h1 class="mb-2 text-4xl text-white font-extrabold leading-none tracking-tight dark:text-white md:max-w-2xl md:text-5xl xl:text-6xl" 
                    style="transform: translateY(0);">
                    Dan awal yang baik adalah tanam <br> Dami Mas!
                </h1>

                <a href="#pesan"
                class="inline-flex items-center justify-center py-4 mt-5 px-8 text-white font-semibold text-xl rounded-lg transition duration-300 transform hover:scale-105 hover:shadow-xl"
                style="background-color: rgb(237, 28, 36);">
                    Pesan Sekarang
                </a>
          </div>
          <div class="hidden md:col-span-5 md:mt-0 md:flex">
            <img class="dark:hidden" src={{ asset('landing/hero.png') }} alt="shopping illustration" />
            <img class="hidden dark:block" src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/girl-shopping-list-dark.svg" alt="shopping illustration" />
          </div>
        </div>
      </section>

      <div class="flex flex-wrap justify-center gap-10 mt-10">
        <!-- Card 1: Benih Unggul -->
        <div class="group relative w-64 h-64 bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-xl cursor-pointer">
            <div class="absolute inset-0 flex items-center justify-center p-4 transition-opacity duration-300 group-hover:opacity-0">
                <p class="text-center font-semibold text-2xl text-gray-800 dark:text-white">Benih Unggul</p>
            </div>
            <div class="absolute inset-0 flex items-center justify-center p-4 opacity-0 group-hover:opacity-100 hover:shadow-[0_8px_20px_rgba(237,28,36,0.3)] hover:shadow-xl transition-opacity duration-300 bg-white/90 dark:bg-gray-800/90">
                <p class="text-center text-lg text-gray-700 dark:text-gray-300">Kecambah sawit DxP Dami Mas, berkualitas tinggi & bersertifikat.</p>
            </div>
        </div>
    
        <!-- Card 2: Harga Terjangkau -->
        <div class="group relative w-64 h-64 bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-xl cursor-pointer">
            <div class="absolute inset-0 flex items-center justify-center p-4 transition-opacity duration-300 group-hover:opacity-0">
                <p class="text-center font-semibold text-2xl text-gray-800 dark:text-white">Harga Terjangkau</p>
            </div>
            <div class="absolute inset-0 flex items-center justify-center p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-white/90 dark:bg-gray-800/90">
                <p class="text-center text-lg text-gray-700 dark:text-gray-300">Mulai dari Rp9.500/butir. Investasi hemat untuk hasil maksimal.</p>
            </div>
        </div>
    
        <!-- Card 3: Pengiriman Aman -->
        <div class="group relative w-64 h-64 bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-xl cursor-pointer">
            <div class="absolute inset-0 flex items-center justify-center p-4 transition-opacity duration-300 group-hover:opacity-0">
                <p class="text-center font-semibold text-2xl text-gray-800 dark:text-white">Pengiriman Aman</p>
            </div>
            <div class="absolute inset-0 flex items-center justify-center p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-white/90 dark:bg-gray-800/90">
                <p class="text-center text-lg text-gray-700 dark:text-gray-300">Packing khusus & kerjasama dengan PT POS ke seluruh Indonesia.</p>
            </div>
        </div>
    
        <!-- Card 4: Legal & Resmi -->
        <div class="group relative w-64 h-64 bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-xl cursor-pointer">
            <div class="absolute inset-0 flex items-center justify-center p-4 transition-opacity duration-300 group-hover:opacity-0">
                <p class="text-center font-semibold text-2xl text-gray-800 dark:text-white">Legal & Resmi</p>
            </div>
            <div class="absolute inset-0 flex items-center justify-center p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-white/90 dark:bg-gray-800/90">
                <p class="text-center text-lg text-gray-700 dark:text-gray-300">Dari Dami Mas resmi, pembayaran via PT Sawit Unggul Sakti.</p>
            </div>
        </div>
    
    
      
    <h1 class="mb-4 text-center mt-10 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white"> Kawan Dami Mas Kami</h1>
    

    <div class="w-full bg-white py-4 marquee-container mt-10">
      
        <div class="marquee-track">
          <!-- Group of brand names, duplicated for looping -->
          <div class="flex gap-12 px-4">
            <div class="bg-gray-100 rounded-xl shadow px-6 py-3 text-gray-800 font-medium whitespace-nowrap">
                PT Agro Sejahtera
              </div>
              <div class="bg-gray-100 rounded-xl shadow px-6 py-3 text-gray-800 font-medium whitespace-nowrap">
                CV Tani Jaya
              </div>
              <div class="bg-gray-100 rounded-xl shadow px-6 py-3 text-gray-800 font-medium whitespace-nowrap">
                UD Sawit Makmur
              </div>
              <div class="bg-gray-100 rounded-xl shadow px-6 py-3 text-gray-800 font-medium whitespace-nowrap">
                KSU Karya Bersama
              </div>
              <div class="bg-gray-100 rounded-xl shadow px-6 py-3 text-gray-800 font-medium whitespace-nowrap">
                PT Nusantara Palm
              </div>
              <div class="bg-gray-100 rounded-xl shadow px-6 py-3 text-gray-800 font-medium whitespace-nowrap">
                CV Bumi Hijau
              </div>
          </div>
          <div class="flex gap-12 px-4">
            <!-- Duplicate the same content for seamless looping -->
            <div class="bg-gray-100 rounded-xl shadow px-6 py-3 text-gray-800 font-medium whitespace-nowrap">
                PT Agro Sejahtera
              </div>
              <div class="bg-gray-100 rounded-xl shadow px-6 py-3 text-gray-800 font-medium whitespace-nowrap">
                CV Tani Jaya
              </div>
              <div class="bg-gray-100 rounded-xl shadow px-6 py-3 text-gray-800 font-medium whitespace-nowrap">
                UD Sawit Makmur
              </div>
              <div class="bg-gray-100 rounded-xl shadow px-6 py-3 text-gray-800 font-medium whitespace-nowrap">
                KSU Karya Bersama
              </div>
              <div class="bg-gray-100 rounded-xl shadow px-6 py-3 text-gray-800 font-medium whitespace-nowrap">
                PT Nusantara Palm
              </div>
              <div class="bg-gray-100 rounded-xl shadow px-6 py-3 text-gray-800 font-medium whitespace-nowrap">
                CV Bumi Hijau
              </div>
          </div>
        </div>
      </div>

</main>
@include('footer')


