<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php';

// Proteksi Akun Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - SLR Band</title>
  
  <!-- Font & Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- AdminLTE 4 CSS (Bootstrap 5 Based) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta2/dist/css/adminlte.min.css">

  <style>
    /* Dark Theme Override untuk Admin SLR Band */
    body, .app-wrapper, .app-main, .app-footer {
      background-color: #0b0c10 !important;
      color: #ffffff !important;
    }
    .app-header {
      background-color: #111217 !important;
      border-bottom: 1px solid #22252e !important;
    }
    .app-sidebar {
      background-color: #111217 !important;
      border-right: 1px solid #22252e !important;
    }
    .card {
      background-color: #111217 !important;
      border: 1px solid #22252e !important;
      color: #ffffff !important;
    }
    .table {
      --bs-table-bg: #111217 !important;
      --bs-table-color: #ffffff !important;
      --bs-table-hover-bg: #1a1c24 !important;
      border-color: #22252e !important;
    }
    .breadcrumb-item.active {
      color: #facc15 !important;
    }
    .nav-link.active {
      background-color: #facc15 !important;
      color: #000000 !important;
      font-weight: bold;
    }
    /* Perbaikan Tampilan Ikon Card Small-Box */
    .small-box {
      position: relative !important;
      overflow: hidden !important;
    }
    .small-box .icon {
      position: absolute !important;
      top: 10px !important;
      right: 15px !important;
      font-size: 3.8rem !important;
      color: rgba(255, 255, 255, 0.25) !important;
      transition: transform 0.3s ease, color 0.3s ease !important;
      z-index: 0 !important;
    }
    .small-box:hover .icon {
      transform: scale(1.15) !important;
      color: rgba(255, 255, 255, 0.4) !important;
    }
    .small-box .inner {
      position: relative !important;
      z-index: 1 !important;
    }
    
  </style>

  
</head>
<body class="layout-fixed sidebar-expand-lg app-loaded bg-dark text-white">
  <div class="app-wrapper">

    <!-- NAVBAR ATAS -->
    <nav class="app-header navbar navbar-expand navbar-dark">
      <div class="container-fluid">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link text-light" data-lte-toggle="sidebar" href="#" role="button"><i class="fa-solid fa-bars"></i></a>
          </li>
          <li class="nav-item d-none d-md-block">
            <a href="index.php" target="_blank" class="nav-link text-warning font-weight-bold">
              <i class="fa-solid fa-globe me-1"></i> Lihat Web Utama
            </a>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item me-3 d-flex align-items-center">
            <span class="navbar-text text-light">Halo, <strong class="text-warning"><?= htmlspecialchars($_SESSION['nama_pengguna']); ?></strong></span>
          </li>
          <li class="nav-item">
            <a href="logout.php" class="btn btn-outline-danger btn-sm font-weight-bold"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</a>
          </li>
        </ul>
      </div>
    </nav>

    <!-- SIDEBAR NAVIGASI -->
    <aside class="app-sidebar shadow" data-bs-theme="dark">
      <div class="sidebar-brand">
        <a href="admin.php" class="brand-link">
          <span class="brand-text text-warning font-weight-bold">SLR BAND ADMIN</span>
        </a>
      </div>
      <div class="sidebar-wrapper">
        <nav class="mt-2">
          <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
            <li class="nav-item">
              <a href="admin.php" class="nav-link <?= ($current_page == 'admin.php') ? 'active' : '' ?>">
                <i class="nav-icon fa-solid fa-gauge"></i>
                <p>Dashboard</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="admin_lagu.php" class="nav-link <?= ($current_page == 'admin_lagu.php') ? 'active' : '' ?>">
                <i class="nav-icon fa-solid fa-music"></i>
                <p>Kelola Lagu</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="admin_tur.php" class="nav-link <?= ($current_page == 'admin_tur.php') ? 'active' : '' ?>">
                <i class="nav-icon fa-solid fa-guitar"></i>
                <p>Jadwal Tur</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="admin_merch.php" class="nav-link <?= ($current_page == 'admin_merch.php') ? 'active' : '' ?>">
                <i class="nav-icon fa-solid fa-box"></i>
                <p>Produk Merch</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="admin_galeri.php" class="nav-link <?= ($current_page == 'admin_galeri.php') ? 'active' : '' ?>">
                <i class="nav-icon fa-solid fa-image"></i>
                <p>Galeri Media</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="admin_pesanan.php" class="nav-link <?= ($current_page == 'admin_pesanan.php') ? 'active' : '' ?>">
                <i class="nav-icon fa-solid fa-ticket"></i>
                <p>Data Pesanan</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="admin_kontak.php" class="nav-link <?= ($current_page == 'admin_kontak.php') ? 'active' : '' ?>">
                <i class="nav-icon fa-solid fa-envelope"></i>
                <p>Pesan Masuk</p>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </aside>