<?php
session_start();
include 'koneksi.php';

// Proteksi Login Fans
if (!isset($_SESSION['id_pengguna'])) {
    echo "<script>
            alert('Silakan login terlebih dahulu untuk membeli tiket!');
            window.location.href = 'login.php';
          </script>";
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];
$id_acara    = mysqli_real_escape_string($koneksi, $_GET['id'] ?? '');

$query_tur = mysqli_query($koneksi, "SELECT * FROM jadwal_tur WHERE id_acara = '$id_acara'");
$tur = mysqli_fetch_assoc($query_tur);

if (!$tur) {
    header("Location: index.php");
    exit;
}

// Cek riwayat pembelian akun ini
$q_cek = mysqli_query($koneksi, "SELECT SUM(jumlah_tiket) AS total_dibeli FROM pesanan_tiket WHERE id_pengguna = '$id_pengguna' AND id_acara = '$id_acara'");
$d_cek = mysqli_fetch_assoc($q_cek);
$sudah_dibeli = (int)($d_cek['total_dibeli'] ?? 0);

$sisa_jatah_akun = 5 - $sudah_dibeli;
if ($sisa_jatah_akun < 0) $sisa_jatah_akun = 0;

$max_bisa_dibeli = min($sisa_jatah_akun, (int)$tur['sisa_kuota']);

$msg = '';
$success = false;

// PROSES TRANSAKSI
if (isset($_POST['proses_beli_tiket'])) {
    $jumlah_tiket      = (int)$_POST['jumlah_tiket'];
    $metode_pembayaran = mysqli_real_escape_string($koneksi, $_POST['metode_pembayaran']);
    $sisa_kuota        = (int)$tur['sisa_kuota'];
    $biaya_admin       = 0.00; // Dapat disesuaikan nominal biaya admin jika ada

    if ($jumlah_tiket < 1) {
        $msg = "Jumlah tiket minimal 1 lembar!";
    } elseif ($sudah_dibeli >= 5) {
        $msg = "Anda sudah mencapai batas maksimal pembelian (5 tiket) untuk acara ini!";
    } elseif ($jumlah_tiket > $sisa_jatah_akun) {
        $msg = "Batas sisa pembelian tiket akun Anda adalah " . $sisa_jatah_akun . " lembar!";
    } elseif ($jumlah_tiket > $sisa_kuota) {
        $msg = "Maaf, sisa tiket venue yang tersedia hanya " . $sisa_kuota . " lembar!";
    } elseif (empty($metode_pembayaran)) {
        $msg = "Pilih metode pembayaran terlebih dahulu!";
    } else {
        $total_bayar = ($jumlah_tiket * $tur['harga_tiket']) + $biaya_admin;
        $kode_tiket  = "SLR-TKT-" . rand(10000, 99999);

        // Simpan ke database sesuai kolom pada tabel pesanan_tiket
        $query_insert = "INSERT INTO pesanan_tiket (kode_tiket, id_pengguna, id_acara, jumlah_tiket, biaya_admin, total_bayar, metode_pembayaran, status_pembayaran, waktu_transaksi) 
                         VALUES ('$kode_tiket', '$id_pengguna', '$id_acara', '$jumlah_tiket', '$biaya_admin', '$total_bayar', '$metode_pembayaran', 'lunas', NOW())";
        
        if (mysqli_query($koneksi, $query_insert)) {
            mysqli_query($koneksi, "UPDATE jadwal_tur SET sisa_kuota = sisa_kuota - $jumlah_tiket WHERE id_acara = '$id_acara'");
            $success = true;
        } else {
            $msg = "Gagal memproses transaksi: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Beli Tiket - <?= htmlspecialchars($tur['nama_acara']); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-black text-white flex items-center justify-center min-h-screen p-4 py-8">

  <div class="w-full max-w-lg bg-[#111] p-8 border border-yellow-400 rounded-2xl relative shadow-2xl">
    
    <!-- TOMBOL CLOSE (X) -->
    <a href="index.php#tour" class="absolute top-4 right-4 text-gray-400 hover:text-yellow-400 text-3xl font-bold leading-none transition" title="Batal">&times;</a>

    <h2 class="text-3xl font-black text-yellow-400 text-center mb-6 uppercase tracking-wider">PEMBELIAN TIKET</h2>

    <?php if ($success): ?>
      <div class="bg-green-500/20 border border-green-500 text-green-400 p-6 rounded-xl text-center">
        <div class="text-4xl mb-2">🎉</div>
        <h3 class="text-xl font-bold mb-2">Pembayaran Berhasil!</h3>
        <p class="text-sm text-gray-300 mb-1">Kode Tiket: <strong class="text-yellow-400 font-mono text-base"><?= $kode_tiket; ?></strong></p>
        <p class="text-sm text-gray-300 mb-2">Metode Bayar: <strong class="uppercase text-yellow-400"><?= htmlspecialchars($_POST['metode_pembayaran']); ?></strong></p>
        <p class="text-xs text-gray-400 mb-6">Tunjukkan kode tiket ini saat penukaran gelang di venue konser!</p>
        <a href="index.php" class="inline-block bg-yellow-400 text-black font-extrabold px-6 py-3 rounded-lg hover:bg-yellow-300 transition">
          KEMBALI KE BERANDA
        </a>
      </div>
    <?php else: ?>

      <?php if ($msg): ?>
        <div class="bg-red-500/20 border border-red-500 text-red-400 p-3 rounded mb-4 text-center text-sm font-bold"><?= $msg; ?></div>
      <?php endif; ?>

      <!-- RINGKASAN KONSER -->
      <div class="bg-black p-4 rounded-xl border border-gray-800 mb-6 space-y-2">
        <h3 class="text-xl font-black text-yellow-400"><?= htmlspecialchars($tur['nama_acara']); ?></h3>
        <p class="text-sm text-gray-300">📍 Kota: <strong class="text-white"><?= htmlspecialchars($tur['kota']); ?></strong></p>
        <p class="text-sm text-gray-300">🏟️ Venue: <strong class="text-white"><?= htmlspecialchars($tur['lokasi_venue']); ?></strong></p>
        <p class="text-sm text-gray-300">📅 Tanggal: <strong class="text-white"><?= date('d F Y, H:i', strtotime($tur['tanggal_acara'])); ?></strong> WIB</p>
        <p class="text-sm text-gray-300">💰 Harga: <strong class="text-yellow-400 font-mono">Rp<?= number_format($tur['harga_tiket'], 0, ',', '.'); ?></strong> / tiket</p>
        
        <div class="pt-2 border-t border-gray-800 flex justify-between text-xs font-bold">
          <span class="text-emerald-400">🔥 Sisa Tiket Venue: <?= $tur['sisa_kuota']; ?> Slot</span>
          <span class="text-yellow-400">⚠️ Jatah Akun Anda: <?= $sisa_jatah_akun; ?> / 5 Tiket</span>
        </div>
      </div>

      <?php if ($sisa_jatah_akun <= 0): ?>
        <div class="bg-yellow-400/20 border border-yellow-400 text-yellow-400 p-4 rounded-xl text-center font-bold text-sm">
          ⛔ Anda telah mencapai batas maksimal 5 tiket per akun untuk konser ini.
        </div>
      <?php else: ?>
        <!-- FORM PEMBELIAN -->
        <form method="POST" class="space-y-4">
          <div>
            <label class="block mb-2 text-xs font-bold uppercase text-gray-400">
              Jumlah Tiket (Maksimal <?= $max_bisa_dibeli; ?> lembar)
            </label>
            <input type="number" id="jumlah_tiket" name="jumlah_tiket" value="1" min="1" max="<?= $max_bisa_dibeli; ?>" required onchange="hitungTotal()" onkeyup="hitungTotal()" class="w-full bg-black border border-yellow-400 p-3 text-white rounded-lg focus:outline-none focus:border-yellow-300">
          </div>

          <!-- INPUT METODE PEMBAYARAN DENGAN LOGO E-WALLET -->
<!-- INPUT METODE PEMBAYARAN WITH ACTIVE STYLE FIX -->
<div>
  <label class="block mb-2 text-xs font-bold uppercase text-gray-400">Pilih Metode Pembayaran</label>
  
  <style>
    /* CSS Efek Klik Radio Pembayaran */
    .payment-option input[type="radio"]:checked + .payment-box {
      border-color: #facc15 !important;
      background-color: rgba(250, 204, 21, 0.15) !important;
      box-shadow: 0 0 15px rgba(250, 204, 21, 0.3) !important;
    }
    .payment-option input[type="radio"]:checked + .payment-box .check-icon {
      opacity: 1 !important;
    }
  </style>

  <div class="grid grid-cols-3 gap-3">
    
    <!-- QRIS -->
    <label class="payment-option cursor-pointer">
      <input type="radio" name="metode_pembayaran" value="qris" required class="hidden">
      <div class="payment-box bg-black border border-gray-800 p-3 rounded-xl flex flex-col items-center justify-center gap-1.5 transition duration-200 relative">
        <span class="check-icon absolute top-1.5 right-2 text-xs font-black text-yellow-400 opacity-0 transition">✓</span>
        <div class="h-7 flex items-center justify-center">
          <span class="font-black text-red-500 text-lg tracking-tighter bg-white px-2 py-0.5 rounded border border-red-500">QRIS</span>
        </div>
        <span class="text-[10px] font-bold text-gray-400"></span>
      </div>
    </label>

    <!-- DANA -->
    <label class="payment-option cursor-pointer">
      <input type="radio" name="metode_pembayaran" value="dana" class="hidden">
      <div class="payment-box bg-black border border-gray-800 p-3 rounded-xl flex flex-col items-center justify-center gap-1.5 transition duration-200 relative">
        <span class="check-icon absolute top-1.5 right-2 text-xs font-black text-yellow-400 opacity-0 transition">✓</span>
        <div class="h-7 flex items-center justify-center">
          <span class="font-black text-blue-400 text-lg tracking-wider">DANA</span>
        </div>
        <span class="text-[10px] font-bold text-gray-400"></span>
      </div>
    </label>

    <!-- OVO -->
    <label class="payment-option cursor-pointer">
      <input type="radio" name="metode_pembayaran" value="ovo" class="hidden">
      <div class="payment-box bg-black border border-gray-800 p-3 rounded-xl flex flex-col items-center justify-center gap-1.5 transition duration-200 relative">
        <span class="check-icon absolute top-1.5 right-2 text-xs font-black text-yellow-400 opacity-0 transition">✓</span>
        <div class="h-7 flex items-center justify-center">
          <span class="font-black text-purple-400 text-lg tracking-widest">OVO</span>
        </div>
        <span class="text-[10px] font-bold text-gray-400"></span>
      </div>
    </label>

  </div>
</div>

          <div class="flex justify-between items-center bg-gray-900/60 p-4 rounded-lg border border-gray-800">
            <span class="text-sm font-bold text-gray-400 uppercase">Total Bayar:</span>
            <span id="display_total" class="text-2xl font-black text-yellow-400 font-mono">Rp<?= number_format($tur['harga_tiket'], 0, ',', '.'); ?></span>
          </div>

          <button type="submit" name="proses_beli_tiket" class="w-full bg-yellow-400 text-black py-4 font-black rounded-lg hover:bg-yellow-300 transition tracking-wider uppercase text-lg shadow-lg">
            KONFIRMASI PEMBAYARAN
          </button>
        </form>

        <script>
          function hitungTotal() {
            const harga = <?= $tur['harga_tiket']; ?>;
            let qty = parseInt(document.getElementById('jumlah_tiket').value) || 1;
            const max = <?= $max_bisa_dibeli; ?>;

            if (qty > max) {
              qty = max;
              document.getElementById('jumlah_tiket').value = max;
            }

            const total = harga * qty;
            document.getElementById('display_total').innerText = 'Rp' + total.toLocaleString('id-ID');
          }
        </script>
      <?php endif; ?>

    <?php endif; ?>

  </div>

</body>
</html>