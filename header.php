<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php';

// Ambil Foto Profil Pengguna untuk Header
$header_user_avatar = '';
if (isset($_SESSION['id_pengguna'])) {
    $h_uid = (int)$_SESSION['id_pengguna'];
    $h_query = mysqli_query($koneksi, "SELECT foto_profil, Nama FROM pengguna WHERE id_pengguna = $h_uid");
    if ($h_query && $h_user = mysqli_fetch_assoc($h_query)) {
        if (!empty($h_user['foto_profil']) && $h_user['foto_profil'] !== 'default.jpg' && file_exists($h_user['foto_profil'])) {
            $header_user_avatar = $h_user['foto_profil'];
        } else {
            $header_user_avatar = "https://ui-avatars.com/api/?name=" . urlencode($h_user['Nama']) . "&background=facc15&color=000&size=64";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SLR Website</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="style.css">
</head>
<body class="font-sans text-white bg-black">

  <!-- HEADER -->
  <header class="fixed top-0 left-0 w-full bg-black border-b border-yellow-400 z-50">
    <div class="w-full flex justify-between items-center px-6 md:px-12 py-4">
        
        <!-- LOGO BAND -->
        <a href="index.php" class="text-3xl font-black text-yellow-400 tracking-widest hover:opacity-80 transition">
            SLR
        </a>

        <!-- MENU DESKTOP -->
        <nav class="hidden lg:flex gap-8 text-sm font-bold uppercase items-center">
            <a href="index.php#home" class="hover:text-yellow-400 transition">Home</a>
            <a href="index.php#music" class="hover:text-yellow-400 transition">Music</a>
            <a href="index.php#gallery" class="hover:text-yellow-400 transition">Galery</a>
            <a href="index.php#tour" class="hover:text-yellow-400 transition">Tour</a>
            <a href="index.php#store" class="hover:text-yellow-400 transition">Store</a>
            <a href="index.php#about" class="hover:text-yellow-400 transition">About</a>
        </nav>

        <!-- AREA KANAN: LOGIN / AKUN -->
        <div class="hidden md:flex items-center gap-4">
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin.php" class="bg-red-600 text-white px-4 py-2 font-extrabold text-xs tracking-wider rounded hover:bg-red-500 transition">
                  ADMIN PANEL
                </a>
                <a href="logout.php" class="border border-yellow-400 text-yellow-400 px-4 py-2 text-xs font-bold hover:bg-yellow-400 hover:text-black transition">
                  LOGOUT
                </a>

            <?php elseif (isset($_SESSION['id_pengguna'])): ?>
                <a href="profil.php" class="flex items-center gap-2.5 text-yellow-400 border border-yellow-400 px-3.5 py-1.5 text-xs font-bold rounded-full hover:bg-yellow-400 hover:text-black transition group">
                  <?php if (!empty($header_user_avatar)): ?>
                    <img src="<?= htmlspecialchars($header_user_avatar); ?>" alt="Avatar" class="w-6 h-6 rounded-full object-cover border border-yellow-400">
                  <?php else: ?>
                    <span>👤</span>
                  <?php endif; ?>
                  <span class="truncate max-w-[140px]"><?= htmlspecialchars($_SESSION['nama_pengguna'] ?? 'Profil'); ?></span>
                </a>
                <a href="logout.php" class="bg-red-600/20 border border-red-500 text-red-400 px-3 py-1.5 text-xs font-bold rounded-full hover:bg-red-600 hover:text-white transition">
                  LOGOUT
                </a>

            <?php else: ?>
                <a href="login.php" class="bg-yellow-400 text-black px-6 py-2.5 font-extrabold text-xs uppercase tracking-wider hover:bg-yellow-300 transition">
                  LOGIN
                </a>
            <?php endif; ?>
        </div>

        <!-- TOMBOL HAMBURGER MOBILE -->
        <button id="menuButton" class="lg:hidden text-yellow-400 text-3xl focus:outline-none" aria-label="Buka menu">
          ☰
        </button>
    </div>

    <!-- MOBILE NAVIGATION MENU -->
    <div id="mobileMenu" class="hidden lg:hidden bg-black border-t border-yellow-400 px-6 py-4">
        <nav class="flex flex-col gap-4 text-sm font-bold uppercase">
            <a href="index.php#home" class="hover:text-yellow-400">Home</a>
            <a href="index.php#music" class="hover:text-yellow-400">Music</a>
            <a href="index.php#gallery" class="hover:text-yellow-400">Galery</a>
            <a href="index.php#tour" class="hover:text-yellow-400">Tour</a>
            <a href="index.php#store" class="hover:text-yellow-400">Store</a>
            <a href="index.php#about" class="hover:text-yellow-400">About</a>
            <hr class="border-gray-800 my-2">
            
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin.php" class="text-red-500 font-extrabold">ADMIN PANEL</a>
                <a href="logout.php" class="text-gray-400">LOGOUT</a>
            <?php elseif (isset($_SESSION['id_pengguna'])): ?>
                <a href="profil.php" class="flex items-center gap-3 text-yellow-400 font-extrabold">
                  <?php if (!empty($header_user_avatar)): ?>
                    <img src="<?= htmlspecialchars($header_user_avatar); ?>" alt="Avatar" class="w-7 h-7 rounded-full object-cover border border-yellow-400">
                  <?php endif; ?>
                  <span>PROFIL SAYA (<?= htmlspecialchars($_SESSION['nama_pengguna']); ?>)</span>
                </a>
                <a href="logout.php" class="text-red-400">LOGOUT</a>
            <?php else: ?>
                <a href="login.php" class="bg-yellow-400 text-black text-center py-2.5 font-bold">LOGIN</a>
            <?php endif; ?>
        </nav>
    </div>
  </header>