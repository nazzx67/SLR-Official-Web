<?php include 'header.php'; ?>

<!-- HERO -->
<section
  id="home"
  class="hero-section flex items-center justify-center text-center px-6 bg-cover bg-center relative"
  style="background-image:url('https://hypeabis.id/assets/content/SaveClip_App_575330926_17916058527211103_1859594305724840390_n_202512241717441766571464.jpg');"
>

  <!-- overlay -->
  <div class="absolute inset-0 bg-black/70"></div>

  <!-- content -->
  <div class="relative z-10 max-w-4xl">

    <h1 class="text-4xl sm:text-5xl md:text-7xl lg:text-8xl font-extrabold leading-tight mb-6">
      PUNK ROCK <br>
      PELAJAR BEKASI
    </h1>

    <p class="text-gray-300 text-xl mb-10">
      Band Punk Rock Asal Bekasi yang Membawakan Cerita Kehidupan Pelajar dan Anak Muda
    </p>

    <!-- CTA -->
    <div class="flex flex-col md:flex-row gap-5 justify-center">
      <a href="#music" class="bg-yellow-400 text-black px-10 py-4 font-bold text-2xl hover:bg-yellow-300 transition">
        ▶️ DENGERIN
      </a>
      <a href="#tour" class="border-2 border-yellow-400 text-yellow-400 px-10 py-4 font-bold text-2xl hover:bg-yellow-400 hover:text-black transition">
        LIHAT TOUR
      </a>
    </div>

    <div class="mt-20 animate-bounce">
      <a href="#intro">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mx-auto text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
      </a>
    </div>

  </div>
</section>

<!-- INTRO -->
<section id="intro" class="px-6 md:px-10 py-20 max-w-6xl mx-auto">
  <div class="grid md:grid-cols-2 gap-12 items-center">
    <div>
      <img src="https://pophariini.com/wp-content/uploads/2025/09/Photo-1-2-e1757616734912.webp" class="rounded-lg border-4 border-yellow-400" alt="Band SLR">
    </div>
    <div>
      <h2 class="text-4xl font-bold mb-6 text-yellow-400">SIAPA SLR?</h2>
      <p class="text-lg leading-relaxed mb-4">
        Band punk pelajar dari Bekasi dengan energi liar, lirik absurd, dan panggung chaos.
      </p>
      <p class="text-lg leading-relaxed text-gray-300">
        Tiga sahabat yang memutuskan untuk mengekspresikan diri melalui musik punk rock. Kami membawa cerita kehidupan pelajar dan anak muda dengan cara yang unik dan menghibur.
      </p>
    </div>
  </div>
</section>

<!-- MUSIC -->
<section id="music" class="px-6 md:px-10 py-20 bg-[#111111]">
  <h2 class="text-4xl font-bold mb-10 text-yellow-400">LAGU KAMI</h2>
  <div class="music-tools">
    <input id="songSearch" type="search" class="music-search" placeholder="Cari lagu..." aria-label="Cari lagu">
    <p id="songResultInfo" class="music-result-info" aria-live="polite"></p>
  </div>

  <div id="song-list" class="grid gap-6 md:grid-cols-2 pr-3 custom-scrollbar">
    <?php
    $query_lagu = mysqli_query($koneksi, "SELECT * FROM lagu ORDER BY id_lagu ASC");
    if ($query_lagu && mysqli_num_rows($query_lagu) > 0):
      while ($lagu = mysqli_fetch_assoc($query_lagu)):
        $audio_src = !empty($lagu['file_audio']) ? $lagu['file_audio'] : 'lagu1.mp3';
        $durasi = !empty($lagu['durasi']) ? $lagu['durasi'] : '03:00';
    ?>
      <div class="music-card" data-video="<?= htmlspecialchars($lagu['url_youtube'] ?? ''); ?>">
        <div class="track-header">
          <div class="track-info">
            <div class="track-title"><?= htmlspecialchars($lagu['judul_lagu']); ?></div>
            <div class="track-artist">SLR Official</div>
          </div>
        </div>
        <div class="player">
          <button class="play-btn" title="Putar Lagu">▶</button>
          <div class="progress-wrap">
            <span class="time-label time-current">0:00</span>
            <div class="progress-track"><div class="progress-fill"></div></div>
            <span class="time-label time-duration"><?= htmlspecialchars($durasi); ?></span>
          </div>
        </div>
        <audio class="hidden"><source src="<?= htmlspecialchars($audio_src); ?>"></audio>
      </div>
    <?php 
      endwhile;
    else: 
    ?>
      <p class="text-gray-400 italic">Belum ada katalog lagu yang ditambahkan.</p>
    <?php endif; ?>
  </div>
</section>

<!-- GALERI -->
<section id="gallery" class="px-3 sm:px-6 md:px-10 py-20 bg-black">
  <h2 class="text-4xl font-bold text-yellow-400 text-center mb-5 uppercase tracking-wider">GALERI</h2>
  <p class="text-center text-gray-300 max-w-3xl mx-auto mb-10 text-sm md:text-base">
    Dokumentasi perjalanan, latihan, panggung, dan aktivitas SLR.
  </p>

  <div class="gallery-tabs mb-8" role="tablist" aria-label="Kategori galeri">
    <button class="gallery-tab active" type="button" role="tab" aria-selected="true" data-gallery-tab="videos">VIDEO</button>
    <button class="gallery-tab" type="button" role="tab" aria-selected="false" data-gallery-tab="photos">FOTO</button>
  </div>

  <style>
    /* CSS Slider Galeri (Tepat 3 Kartu di Desktop / PC) */
    .gallery-slider-item {
      flex: 0 0 100%; /* Mobile: 1 kartu */
    }
    @media (min-width: 640px) {
      .gallery-slider-item {
        flex: 0 0 calc((100% - 1.25rem) / 2); /* Tablet: 2 kartu */
      }
    }
    @media (min-width: 1024px) {
      .gallery-slider-item {
        flex: 0 0 calc((100% - 2.5rem) / 3); /* PC / Laptop: 3 Kartu Melebar */
      }
    }
    #gallery-video-container::-webkit-scrollbar,
    #gallery-photo-container::-webkit-scrollbar {
      display: none;
    }
  </style>

  <?php
  // Helper render video (Mendukung YouTube, Shorts, TikTok, Instagram, dan Video Lokal)
  if (!function_exists('renderGaleriVideoPlayer')) {
      function renderGaleriVideoPlayer($url, $poster = '') {
          $url = trim($url);

          // 1. YouTube Link (Biasa, youtu.be, Embed, maupun YouTube Shorts)
          if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/i', $url, $m)) {
              $yt_id = $m[1];
              return '<iframe src="https://www.youtube.com/embed/' . htmlspecialchars($yt_id) . '" class="w-full h-64 md:h-72 rounded-xl border-2 border-yellow-400 bg-black" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
          }

          // 2. TikTok Link (Mendukung tiktok.com/@user/video/ID atau link tiktok lainnya)
          if (stripos($url, 'tiktok.com') !== false) {
              if (preg_match('/(?:video|v)\/(\d+)/i', $url, $m)) {
                  $tiktok_id = $m[1];
                  return '<iframe src="https://www.tiktok.com/embed/v2/' . htmlspecialchars($tiktok_id) . '" class="w-full h-64 md:h-72 rounded-xl border-2 border-yellow-400 bg-black" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
              } else {
                  // Jika link TikTok pendek (vt.tiktok.com) yang tidak memiliki ID angka langsung
                  return '<div class="w-full h-64 md:h-72 rounded-xl border-2 border-yellow-400 bg-[#16181f] flex flex-col items-center justify-center p-6 text-center">
                            <div class="text-4xl mb-2">🎵</div>
                            <h4 class="font-bold text-yellow-400 text-sm mb-1">Video TikTok SLR</h4>
                            <p class="text-xs text-gray-400 mb-4">Putar video TikTok langsung di aplikasi atau browser Anda</p>
                            <a href="' . htmlspecialchars($url) . '" target="_blank" rel="noopener noreferrer" class="bg-yellow-400 text-black px-5 py-2.5 rounded-lg font-black text-xs uppercase tracking-wider hover:bg-yellow-300 transition">
                              ▶ Buka di TikTok
                            </a>
                          </div>';
              }
          }

          // 3. Instagram Reels / Post
          if (preg_match('/instagram\.com\/(?:reel|p)\/([a-zA-Z0-9_-]+)/i', $url, $m)) {
              $ig_id = $m[1];
              return '<iframe src="https://www.instagram.com/reel/' . htmlspecialchars($ig_id) . '/embed" class="w-full h-64 md:h-72 rounded-xl border-2 border-yellow-400 bg-black" allowfullscreen></iframe>';
          }

          // 4. File Video Lokal atau Direct Link File (.mp4, .webm, .mov)
          $poster_attr = !empty($poster) ? ' poster="' . htmlspecialchars($poster) . '"' : '';
          return '<video class="w-full h-64 md:h-72 object-cover rounded-xl border-2 border-yellow-400 bg-black" controls preload="metadata"' . $poster_attr . '>
                    <source src="' . htmlspecialchars($url) . '" type="video/mp4">
                    Browser Anda tidak mendukung tag video.
                  </video>';
      }
  }
  ?>

  <!-- TAB PANEL: VIDEO SLIDER -->
  <div id="gallery-videos" class="gallery-panel active" role="tabpanel">
    <div class="flex items-center gap-2 md:gap-4">
      
      <!-- Tombol Geser Kiri (<) -->
      <button onclick="scrollGalleryContainer('gallery-video-container', -1)" class="w-12 h-12 bg-yellow-400 text-black font-black text-2xl rounded-full flex-shrink-0 flex items-center justify-center hover:bg-yellow-300 hover:scale-110 transition shadow-2xl focus:outline-none cursor-pointer border-2 border-yellow-400" title="Geser Kiri">
        &#10094;
      </button>

      <!-- Container Slider Video (3 Kartu Melebar di Desktop) -->
      <div id="gallery-video-container" class="flex-1 flex gap-5 overflow-x-auto scroll-smooth py-3" style="scrollbar-width: none; -ms-overflow-style: none;">
        <?php
        $query_video = mysqli_query($koneksi, "SELECT * FROM galeri WHERE jenis_media='video' ORDER BY id_galeri DESC");
        if ($query_video && mysqli_num_rows($query_video) > 0):
          while ($vid = mysqli_fetch_assoc($query_video)):
        ?>
          <div class="gallery-slider-item bg-[#111] p-4 rounded-2xl border border-gray-800 shadow-2xl flex flex-col justify-between hover:border-yellow-400/50 transition">
            <div>
              <?= renderGaleriVideoPlayer($vid['url_media'], $vid['thumbnail_url']); ?>
              <div class="mt-3">
                <h3 class="text-lg md:text-xl font-black text-yellow-400"><?= htmlspecialchars($vid['judul']); ?></h3>
                <?php if(!empty($vid['deskripsi'])): ?>
                  <p class="text-xs text-gray-400 mt-1 line-clamp-2"><?= htmlspecialchars($vid['deskripsi']); ?></p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php 
          endwhile;
        else:
        ?>
          <div class="w-full text-center py-8 text-gray-500 italic">Belum ada video galeri.</div>
        <?php endif; ?>
      </div>

      <!-- Tombol Geser Kanan (>) -->
      <button onclick="scrollGalleryContainer('gallery-video-container', 1)" class="w-12 h-12 bg-yellow-400 text-black font-black text-2xl rounded-full flex-shrink-0 flex items-center justify-center hover:bg-yellow-300 hover:scale-110 transition shadow-2xl focus:outline-none cursor-pointer border-2 border-yellow-400" title="Geser Kanan">
        &#10095;
      </button>

    </div>
  </div>

  <!-- TAB PANEL: FOTO SLIDER -->
  <div id="gallery-photos" class="gallery-panel" role="tabpanel" hidden>
    <div class="flex items-center gap-2 md:gap-4">
      
      <!-- Tombol Geser Kiri (<) -->
      <button onclick="scrollGalleryContainer('gallery-photo-container', -1)" class="w-12 h-12 bg-yellow-400 text-black font-black text-2xl rounded-full flex-shrink-0 flex items-center justify-center hover:bg-yellow-300 hover:scale-110 transition shadow-2xl focus:outline-none cursor-pointer border-2 border-yellow-400" title="Geser Kiri">
        &#10094;
      </button>

      <!-- Container Slider Foto (3 Kartu Melebar di Desktop) -->
      <div id="gallery-photo-container" class="flex-1 flex gap-5 overflow-x-auto scroll-smooth py-3" style="scrollbar-width: none; -ms-overflow-style: none;">
        <?php
        $query_foto = mysqli_query($koneksi, "SELECT * FROM galeri WHERE jenis_media='foto' ORDER BY id_galeri DESC");
        if ($query_foto && mysqli_num_rows($query_foto) > 0):
          while ($foto = mysqli_fetch_assoc($query_foto)):
        ?>
          <div class="gallery-slider-item bg-[#111] p-4 rounded-2xl border border-gray-800 shadow-2xl flex flex-col justify-between hover:border-yellow-400/50 transition">
            <div>
              <div class="w-full h-64 md:h-72 overflow-hidden rounded-xl border-2 border-yellow-400 bg-black flex items-center justify-center">
                <img src="<?= htmlspecialchars($foto['url_media']); ?>" class="w-full h-full object-cover hover:scale-105 transition duration-300" alt="<?= htmlspecialchars($foto['judul']); ?>" onerror="this.src='https://via.placeholder.com/400x300?text=Foto+SLR';">
              </div>
              <div class="mt-3">
                <h3 class="text-lg md:text-xl font-black text-yellow-400"><?= htmlspecialchars($foto['judul']); ?></h3>
                <?php if(!empty($foto['deskripsi'])): ?>
                  <p class="text-xs text-gray-400 mt-1 line-clamp-2"><?= htmlspecialchars($foto['deskripsi']); ?></p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php 
          endwhile;
        else:
        ?>
          <div class="w-full text-center py-8 text-gray-500 italic">Belum ada foto galeri.</div>
        <?php endif; ?>
      </div>

      <!-- Tombol Geser Kanan (>) -->
      <button onclick="scrollGalleryContainer('gallery-photo-container', 1)" class="w-12 h-12 bg-yellow-400 text-black font-black text-2xl rounded-full flex-shrink-0 flex items-center justify-center hover:bg-yellow-300 hover:scale-110 transition shadow-2xl focus:outline-none cursor-pointer border-2 border-yellow-400" title="Geser Kanan">
        &#10095;
      </button>

    </div>
  </div>
</section>

<!-- SCRIPT PENGGESER SLIDER GALERI -->
<script>
function scrollGalleryContainer(containerId, direction) {
  const container = document.getElementById(containerId);
  if (container) {
    const card = container.querySelector('.gallery-slider-item');
    if (card) {
      const gap = 20; // 1.25rem = 20px
      const scrollAmount = (card.offsetWidth + gap) * direction;
      container.scrollBy({
        left: scrollAmount,
        behavior: 'smooth'
      });
    }
  }
}
</script>

<!-- TOUR -->
<!-- SECTION TOUR / JADWAL KONSER -->
<!-- SECTION TOUR / GIGS SLIDER -->
<!-- SECTION TOUR / GIGS SLIDER -->
<!-- SECTION TOUR / GIGS SLIDER -->
<!-- SECTION TOUR / GIGS SLIDER -->
<section id="tour" class="bg-yellow-400 py-12 px-2 sm:px-6 md:px-8">
  <div class="w-full mx-auto">
    
    <!-- HEADER GIGS -->
    <div class="flex justify-between items-center mb-6 px-4">
      <h2 class="text-4xl font-black text-black uppercase tracking-wider">TOUR</h2>
    </div>

    <!-- CSS UNTUK 4 KARTU MELEBAR UTUH DI PC -->
    <style>
      .gigs-slider-item {
        flex: 0 0 100%; /* Mobile: 1 kartu */
      }
      @media (min-width: 640px) {
        .gigs-slider-item {
          flex: 0 0 calc((100% - 1.25rem) / 2); /* Tablet: 2 kartu */
        }
      }
      @media (min-width: 1024px) {
        .gigs-slider-item {
          flex: 0 0 calc((100% - 3.75rem) / 4); /* PC/Laptop: 4 Kartu Melebar Full */
        }
      }
      #gigs-container::-webkit-scrollbar {
        display: none; /* Sembunyikan scrollbar */
      }
    </style>

    <!-- CONTAINER WIDGET DENGAN TOMBOL DI KIRI DAN KANAN SLIDER -->
    <div class="flex items-center gap-2 md:gap-4">
      
      <!-- TOMBOL KIRI (<) -->
      <button onclick="scrollGigs(-1)" class="w-12 h-12 bg-black text-yellow-400 font-black text-2xl rounded-full flex-shrink-0 flex items-center justify-center hover:bg-gray-800 hover:scale-110 transition shadow-2xl focus:outline-none cursor-pointer border-2 border-black" title="Geser Kiri">
        &#10094;
      </button>

      <!-- CONTAINER SLIDER KARTU (MELEBAR SEPENUH LAYAR) -->
      <div id="gigs-container" class="flex-1 flex gap-5 overflow-x-auto scroll-smooth py-3" style="scrollbar-width: none; -ms-overflow-style: none;">
        <?php
        $query_tur = mysqli_query($koneksi, "SELECT * FROM jadwal_tur ORDER BY tanggal_acara ASC");
        while ($tur = mysqli_fetch_assoc($query_tur)):
          $stok = $tur['sisa_kuota'];
        ?>
          <!-- KARTU KONSER (LEBIH BESAR & PROPORSIONAL) -->
          <div class="gigs-slider-item bg-black p-6 md:p-7 rounded-2xl border-2 border-black shadow-2xl flex flex-col justify-between">
            <div class="space-y-4 mb-6">
              <!-- Kota -->
              <h3 class="text-2xl md:text-3xl font-black text-yellow-400 flex items-center gap-2">
                📍 <span><?= htmlspecialchars($tur['kota']); ?></span>
              </h3>

              <!-- Tanggal -->
              <p class="text-white font-semibold text-sm md:text-base flex items-center gap-2">
                📅 <span><?= date('d F Y', strtotime($tur['tanggal_acara'])); ?></span>
              </p>

              <!-- Venue -->
              <p class="text-gray-300 text-sm md:text-base flex items-center gap-2">
                🏟️ <span><?= htmlspecialchars($tur['lokasi_venue']); ?></span>
              </p>

              <!-- Harga -->
              <p class="text-white font-extrabold text-xl flex items-center gap-2 pt-1">
                💰 <span>Rp<?= number_format($tur['harga_tiket'], 0, ',', '.'); ?></span>
              </p>

              <!-- Status Kuota -->
              <div class="pt-1">
                <?php if ($stok <= 30): ?>
                  <span class="inline-flex items-center gap-1 bg-red-600 text-white text-xs md:text-sm font-bold px-3.5 py-1.5 rounded-full">
                    🔥 Tersisa <?= $stok; ?>
                  </span>
                <?php else: ?>
                  <span class="inline-flex items-center gap-1 bg-emerald-600 text-white text-xs md:text-sm font-bold px-3.5 py-1.5 rounded-full">
                    Tersisa <?= $stok; ?>
                  </span>
                <?php endif; ?>
              </div>
            </div>

            <!-- Tombol Beli Tiket -->
            <a href="beli_tiket.php?id=<?= $tur['id_acara']; ?>" class="block w-full text-center bg-yellow-400 text-black font-black py-3.5 rounded-xl hover:bg-yellow-300 transition uppercase tracking-wider text-sm md:text-base shadow-md">
              BELI TIKET
            </a>
          </div>
        <?php endwhile; ?>
      </div>

      <!-- TOMBOL KANAN (>) -->
      <button onclick="scrollGigs(1)" class="w-12 h-12 bg-black text-yellow-400 font-black text-2xl rounded-full flex-shrink-0 flex items-center justify-center hover:bg-gray-800 hover:scale-110 transition shadow-2xl focus:outline-none cursor-pointer border-2 border-black" title="Geser Kanan">
        &#10095;
      </button>

    </div>

  </div>
</section>

<!-- SCRIPT NAVIGASI GESER -->
<script>
function scrollGigs(direction) {
  const container = document.getElementById('gigs-container');
  if (container) {
    const card = container.querySelector('.gigs-slider-item');
    const gap = 20; // Jarak antar kartu (gap-5 = 20px)
    const scrollAmount = (card.offsetWidth + gap) * direction;
    container.scrollBy({
      left: scrollAmount,
      behavior: 'smooth'
    });
  }
}
</script>

<!-- STORE -->
<!-- SECTION STORE / MERCHANDISE SLIDER -->
<section id="store" class="bg-black text-white py-12 px-2 sm:px-6 md:px-8 border-t border-yellow-400/20">
  <div class="w-full mx-auto">
    
    <!-- HEADER STORE -->
    <div class="flex justify-between items-center mb-6 px-4">
      <h2 class="text-4xl font-black text-yellow-400 uppercase tracking-wider">STORE</h2>
    </div>

    <!-- CSS PRESI 4 KARTU MERCH DI PC -->
    <style>
      .merch-slider-item {
        flex: 0 0 100%; /* Mobile: 1 kartu */
      }
      @media (min-width: 640px) {
        .merch-slider-item {
          flex: 0 0 calc((100% - 1.25rem) / 2); /* Tablet: 2 kartu */
        }
      }
      @media (min-width: 1024px) {
        .merch-slider-item {
          flex: 0 0 calc((100% - 3.75rem) / 4); /* PC/Laptop: 4 Kartu Melebar Full */
        }
      }
      #merch-container::-webkit-scrollbar {
        display: none; /* Sembunyikan scrollbar */
      }
    </style>

    <!-- CONTAINER SLIDER DENGAN TOMBOL DI KIRI DAN KANAN -->
    <div class="flex items-center gap-2 md:gap-4">
      
      <!-- TOMBOL KIRI (<) -->
      <button onclick="scrollMerch(-1)" class="w-12 h-12 bg-yellow-400 text-black font-black text-2xl rounded-full flex-shrink-0 flex items-center justify-center hover:bg-yellow-300 hover:scale-110 transition shadow-2xl focus:outline-none cursor-pointer border-2 border-yellow-400" title="Geser Kiri">
        &#10094;
      </button>

      <!-- CONTAINER SLIDER KARTU MERCHANDISE -->
      <div id="merch-container" class="flex-1 flex gap-5 overflow-x-auto scroll-smooth py-3" style="scrollbar-width: none; -ms-overflow-style: none;">
        <?php
        // Query mengambil seluruh produk dari database produk_merch
        $query_merch = mysqli_query($koneksi, "SELECT * FROM produk_merch ORDER BY id_produk DESC");
        
        if (mysqli_num_rows($query_merch) > 0):
          while ($merch = mysqli_fetch_assoc($query_merch)):
        ?>
            <!-- KARTU MERCHANDISE -->
            <div class="merch-slider-item bg-[#111] p-6 rounded-2xl border border-gray-800 shadow-2xl flex flex-col justify-between hover:border-yellow-400/50 transition">
              <div>
                <!-- GAMBAR PRODUK -->
                <div class="w-full h-52 mb-4 overflow-hidden rounded-xl bg-black flex items-center justify-center border border-gray-800">
                  <img src="<?= htmlspecialchars($merch['gambar_produk']); ?>" alt="<?= htmlspecialchars($merch['nama_produk']); ?>" class="w-full h-full object-cover hover:scale-105 transition duration-300" onerror="this.src='https://via.placeholder.com/300x300?text=No+Image';">
                </div>

                <!-- NAMA PRODUK -->
                <h3 class="text-xl md:text-2xl font-black text-yellow-400 mb-1">
                  <?= htmlspecialchars($merch['nama_produk']); ?>
                </h3>

                <!-- DESKRIPSI RINGKAS -->
                <p class="text-gray-400 text-xs md:text-sm line-clamp-2 mb-3">
                  <?= htmlspecialchars($merch['deskripsi']); ?>
                </p>
              </div>

              <div>
                <!-- HARGA & STOK -->
                <div class="flex justify-between items-center mb-4 pt-3 border-t border-gray-800">
                  <span class="text-white font-extrabold text-lg md:text-xl font-mono">
                    Rp<?= number_format($merch['harga'], 0, ',', '.'); ?>
                  </span>
                  <span class="text-xs font-bold px-2.5 py-1 rounded-full <?= $merch['stok'] > 0 ? 'bg-yellow-400/20 text-yellow-400 border border-yellow-400/30' : 'bg-red-500/20 text-red-400 border border-red-500/30' ?>">
                    <?= $merch['stok'] > 0 ? 'Stok: '.$merch['stok'] : 'Habis'; ?>
                  </span>
                </div>

                <!-- TOMBOL BELI MERCH -->
                <a href="beli_merch.php?id=<?= $merch['id_produk']; ?>" class="block w-full text-center bg-yellow-400 text-black font-black py-3 rounded-xl hover:bg-yellow-300 transition uppercase tracking-wider text-sm shadow-md">
                  BELI MERCH
                </a>
              </div>
            </div>
        <?php 
          endwhile;
        else:
        ?>
          <div class="w-full text-center py-8 text-gray-500 italic">
            Belum ada produk merchandise yang tersedia.
          </div>
        <?php endif; ?>
      </div>

      <!-- TOMBOL KANAN (>) -->
      <button onclick="scrollMerch(1)" class="w-12 h-12 bg-yellow-400 text-black font-black text-2xl rounded-full flex-shrink-0 flex items-center justify-center hover:bg-yellow-300 hover:scale-110 transition shadow-2xl focus:outline-none cursor-pointer border-2 border-yellow-400" title="Geser Kanan">
        &#10095;
      </button>

    </div>

  </div>
</section>

<!-- SCRIPT NAVIGASI GESER STORE -->
<script>
function scrollMerch(direction) {
  const container = document.getElementById('merch-container');
  if (container) {
    const card = container.querySelector('.merch-slider-item');
    if (card) {
      const gap = 20; // Jarak antar kartu (gap-5 = 20px)
      const scrollAmount = (card.offsetWidth + gap) * direction;
      container.scrollBy({
        left: scrollAmount,
        behavior: 'smooth'
      });
    }
  }
}
</script>

<!-- CONTACT -->
<?php
$contact_user_name = '';
$contact_user_email = '';
if (isset($_SESSION['id_pengguna'])) {
    $c_uid = (int)$_SESSION['id_pengguna'];
    $c_query = mysqli_query($koneksi, "SELECT Nama, email FROM pengguna WHERE id_pengguna = $c_uid");
    if ($c_query && $c_row = mysqli_fetch_assoc($c_query)) {
        $contact_user_name = $c_row['Nama'];
        $contact_user_email = $c_row['email'];
    }
}
?>
<section id="contact" class="py-24 bg-black">
  <div class="max-w-3xl mx-auto px-6">
    <h2 class="text-5xl font-bold text-yellow-400 text-center mb-4">CONTACT US</h2>
    
    <?php if (!empty($contact_user_name)): ?>
      <p class="text-center text-xs md:text-sm text-gray-400 mb-8">
        Terhubung sebagai: <span class="text-yellow-400 font-bold"><?= htmlspecialchars($contact_user_name); ?></span> (<?= htmlspecialchars($contact_user_email); ?>)
      </p>
    <?php else: ?>
      <p class="text-center text-xs md:text-sm text-gray-400 mb-8">
        Ada pertanyaan seputar konser, jadwal tur, atau merch? Hubungi kami langsung.
      </p>
    <?php endif; ?>

    <form id="contactForm" action="kirim_pesan.php" method="POST" class="space-y-6">
      <div>
        <label class="block mb-2 text-xs font-bold uppercase text-gray-400">Nama</label>
        <input type="text" name="name" 
               value="<?= htmlspecialchars($contact_user_name); ?>" 
               <?= !empty($contact_user_name) ? 'readonly' : ''; ?>
               placeholder="Nama Lengkap Anda" required spellcheck="false" autocomplete="off" 
               class="w-full bg-black border border-yellow-400 rounded-lg p-4 placeholder-gray-500 focus:outline-none focus:border-yellow-300 <?= !empty($contact_user_name) ? 'bg-[#181818] cursor-not-allowed text-gray-300' : 'text-white'; ?>" style="color:white;">
      </div>

      <div>
        <label class="block mb-2 text-xs font-bold uppercase text-gray-400">Email</label>
        <input type="email" name="email" 
               value="<?= htmlspecialchars($contact_user_email); ?>" 
               <?= !empty($contact_user_email) ? 'readonly' : ''; ?>
               placeholder="Alamat Email (contoh@gmail.com)" required spellcheck="false" autocomplete="off" 
               class="w-full bg-black border border-yellow-400 rounded-lg p-4 placeholder-gray-500 focus:outline-none focus:border-yellow-300 <?= !empty($contact_user_email) ? 'bg-[#181818] cursor-not-allowed text-gray-300' : 'text-white'; ?>" style="color:white;">
      </div>

      <div>
        <label class="block mb-2 text-xs font-bold uppercase text-gray-400">Pesan</label>
        <textarea name="message" rows="7" placeholder="Tuliskan pesan atau pertanyaan Anda di sini..." required spellcheck="false" 
                  class="w-full bg-black border border-yellow-400 rounded-lg p-4 placeholder-gray-500 resize-none focus:outline-none focus:border-yellow-300" style="color:white;"></textarea>
      </div>

      <p id="contactStatus" class="text-center font-bold hidden"></p>
      <div class="text-center">
        <button id="contactSubmitBtn" type="submit" class="bg-yellow-400 text-black px-10 py-4 rounded-lg font-bold hover:bg-yellow-300 transition">KIRIM PESAN</button>
      </div>
    </form>
  </div>
</section>

<!-- ABOUT -->
<section id="about" class="py-20 bg-[#111]">
  <div class="max-w-7xl mx-auto px-8">
    <h2 class="text-4xl text-center text-yellow-400 font-bold mb-12">ABOUT</h2>
    <div class="grid md:grid-cols-3 gap-8">
      <div class="bg-black border border-yellow-400 rounded-lg p-6 text-center">
        <img src="Images/bm.JPG" class="w-40 h-40 rounded-full object-cover mx-auto border-4 border-yellow-400">
        <h3 class="text-2xl font-bold mt-6">Yoiqbal</h3>
        <p class="text-yellow-400">Drum</p>
        <p class="mt-4 text-gray-300">Dentuman tempo cepat dan konstan yang menjadi jantung dari setiap distorsi kami</p>
      </div>
      <div class="bg-black border border-yellow-400 rounded-lg p-6 text-center">
        <img src="Images/mhs.jpg" class="w-40 h-40 rounded-full object-cover mx-auto border-4 border-yellow-400">
        <h3 class="text-2xl font-bold mt-6">Mahes</h3>
        <p class="text-yellow-400">Vocal</p>
        <p class="mt-4 text-gray-300">Suara khas punk dengan energi tinggi di atas panggung</p>
      </div>
      <div class="bg-black border border-yellow-400 rounded-lg p-6 text-center">
        <img src="Images/jll.jpg" class="w-40 h-40 rounded-full object-cover mx-auto border-4 border-yellow-400">
        <h3 class="text-2xl font-bold mt-6">Jalil</h3>
        <p class="text-yellow-400">Bass</p>
        <p class="mt-4 text-gray-300">Ritme yang mengikat semua elemen musik kami menjadi satu kesatuan yang harmonis</p>
      </div>
    </div>
  </div>
</section>

<!-- POPUP MODALS -->
<div id="videoPopup" class="popup-overlay">
  <button onclick="closeVideoPopup()" class="absolute top-5 right-8 text-white text-5xl">&times;</button>
  <video id="videoPlayer" src="" controls autoplay class="max-w-full max-h-[90vh] rounded-xl border-4 border-yellow-400"></video>
  <iframe id="youtubePlayer" src="" class="hidden youtube-frame rounded-xl border-4 border-yellow-400" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
</div>

<div id="ticketPopup" class="popup-overlay">
  <div class="popup-box">
    <h3 id="popupTitle" class="popup-title">BELI TIKET</h3>
    <div class="popup-field"><label>Nama</label><input type="text" id="ticketName" placeholder="Nama Lengkap"></div>
    <div class="popup-field"><label>Email</label><input type="email" id="ticketEmail" placeholder="email@gmail.com"></div>
    <div class="popup-field">
      <label>Jumlah Tiket (maksimal 5 per email)</label>
      <input type="number" id="ticketAmount" value="1" min="1" max="5" onchange="updateTotal()">
    </div>
    <p id="ticketLimitInfo" class="ticket-limit-info" aria-live="polite">Maksimal 5 tiket untuk setiap alamat email.</p>
    <div class="popup-info"><p>Harga Tiket</p><p id="ticketPrice">Rp0</p></div>
    <div class="popup-info"><p>Biaya Admin</p><p>Rp5.000</p></div>
    <div class="popup-total"><p>TOTAL</p><p id="totalPrice">Rp0</p></div>
    <div class="popup-actions">
      <button class="border border-black px-5 py-2 font-bold" onclick="closePopup()">BATAL</button>
      <button class="border border-black bg-black text-white px-5 py-2 font-bold" onclick="openPaymentPopup('ticket')">LANJUT BAYAR</button>
    </div>
  </div>
</div>

<div id="successMerchPopup" class="popup-overlay">
  <div class="popup-box" style="text-align:center;">
    <div style="font-size:60px;margin-bottom:15px;">📦</div>
    <h3 class="popup-title">PESANAN BERHASIL</h3>
    <p id="successMerchItem" style="font-weight:bold;font-size:20px;color:#facc15;background:#000;display:inline-block;padding:6px 16px;border-radius:999px;margin-bottom:10px;"></p>
    <p id="successMerchName" style="font-weight:bold;"></p>
    <p id="successMerchPhone" style="color:#666;margin-bottom:10px;"></p>
    <p style="margin-top:15px;font-size:14px;">Alamat Pengiriman</p>
    <p id="successMerchAddress" style="margin-bottom:20px;color:#666;"></p>
    <p style="font-size:14px;">Detail Pesanan</p>
    <p id="successMerchDetail" style="font-weight:bold;margin-bottom:20px;"></p>
    <div class="popup-total" style="text-align:left;"><p>TOTAL DIBAYAR</p><p id="successMerchTotal">Rp0</p></div>
    <p>Kode Pesanan</p>
    <h2 id="successMerchCode" style="margin:20px 0;">SLR-000001</h2>
    <p>Pesanan segera diproses.</p>
    <button class="btn btn-filled" style="margin-top:25px" onclick="closeSuccessPopup('merch')">TUTUP</button>
  </div>
</div>

<div id="paymentPopup" class="popup-overlay">
  <div class="popup-box">
    <h3 class="popup-title">PEMBAYARAN</h3>
    <div class="payment-methods">
      <button class="payment-btn">QRIS</button>
      <button class="payment-btn">DANA</button>
      <button class="payment-btn">OVO</button>
      <button class="payment-btn">GoPay</button>
    </div>
    <div class="popup-total"><p>TOTAL PEMBAYARAN</p><p id="paymentTotal">Rp0</p></div>
    <button class="btn btn-filled" style="width:100%;margin-top:20px" onclick="finishPayment()">BAYAR SEKARANG</button>
  </div>
</div>

<div id="merchPopup" class="popup-overlay">
  <div class="popup-box">
    <h3 id="merchPopupTitle" class="popup-title">BELI MERCH</h3>
    <div class="popup-field"><label>Nama</label><input type="text" id="merchName" placeholder="Nama Lengkap"></div>
    <div class="popup-field"><label>Nomor HP</label><input type="tel" id="merchPhone" placeholder="08xxxxxxxxxx"></div>
    <div class="popup-field"><label>Alamat Pengiriman</label><textarea id="merchAddress" rows="3" placeholder="Alamat lengkap"></textarea></div>
    <div class="popup-field" id="merchSizeField" style="display:none;">
      <label>Ukuran Kaos</label>
      <select id="merchSize">
        <option value="">Pilih Ukuran</option>
        <option value="S">S</option>
        <option value="M">M</option>
        <option value="L">L</option>
        <option value="XL">XL</option>
        <option value="XXL">XXL</option>
      </select>
    </div>
    <div class="popup-field"><label>Catatan</label><textarea id="merchNote" rows="3" placeholder="Catatan tambahan (opsional)"></textarea></div>
    <div class="popup-field"><label>Jumlah</label><input type="number" id="merchAmount" value="1" min="1" onchange="updateMerchTotal()"></div>
    <div class="popup-info"><p>Harga Satuan</p><p id="merchPrice">Rp0</p></div>
    <div class="popup-info"><p>Biaya Admin</p><p>Rp5.000</p></div>
    <div class="popup-info"><p>Ongkos Kirim</p><p>Rp15.000</p></div>
    <div class="popup-total"><p>TOTAL</p><p id="merchTotalPrice">Rp0</p></div>
    <div class="popup-actions">
      <button class="border border-black px-5 py-2 font-bold" onclick="closeMerchPopup()">BATAL</button>
      <button class="border border-black bg-black text-white px-5 py-2 font-bold" onclick="openPaymentPopup('merch')">LANJUT BAYAR</button>
    </div>
  </div>
</div>

<div id="successTicketPopup" class="popup-overlay">
  <div class="popup-box" style="text-align:center;">
    <div style="font-size:60px;margin-bottom:15px;">🎫</div>
    <h3 class="popup-title">PEMBELIAN BERHASIL</h3>
    <p id="successTicketName" style="font-weight:bold;"></p>
    <p id="successTicketEmail" style="margin-bottom:20px;color:#666;"></p>
    <p>Tunjukkan QR berikut saat masuk venue</p>
    <div id="ticketQR" style="display:flex;justify-content:center;margin:25px 0;"></div>
    <p>Sampai jumpa di konser! 🤘</p>
    <button class="btn btn-filled" style="margin-top:25px" onclick="closeSuccessPopup('ticket')">TUTUP</button>
  </div>
</div>

<?php include 'footer.php'; ?>