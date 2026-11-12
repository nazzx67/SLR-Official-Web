<?php
session_start();
include 'koneksi.php';

// Proteksi Login Fans
if (!isset($_SESSION['id_pengguna'])) {
    echo "<script>
            alert('Silakan login terlebih dahulu untuk membeli merchandise!');
            window.location.href = 'login.php';
          </script>";
    exit;
}

$id_user   = $_SESSION['id_pengguna'];
$id_produk = mysqli_real_escape_string($koneksi, $_GET['id'] ?? '');

$query_merch = mysqli_query($koneksi, "SELECT * FROM produk_merch WHERE id_produk = '$id_produk'");
$merch = mysqli_fetch_assoc($query_merch);

if (!$merch) {
    header("Location: index.php");
    exit;
}

$user_info = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM pengguna WHERE id_pengguna = '$id_user'"));
$max_beli_merch = min(5, (int)$merch['stok']);

$msg = '';
$success = false;

// PROSES TRANSAKSI
if (isset($_POST['proses_beli_merch'])) {
    $nama_penerima     = mysqli_real_escape_string($koneksi, trim($_POST['nama_penerima']));
    $nomor_telepon     = mysqli_real_escape_string($koneksi, trim($_POST['nomor_telepon']));
    $alamat_pengiriman = mysqli_real_escape_string($koneksi, trim($_POST['alamat_pengiriman']));
    $metode_pembayaran = mysqli_real_escape_string($koneksi, $_POST['metode_pembayaran']);
    $ukuran            = isset($_POST['ukuran']) ? mysqli_real_escape_string($koneksi, trim($_POST['ukuran'])) : NULL;
    $catatan           = isset($_POST['catatan']) ? mysqli_real_escape_string($koneksi, trim($_POST['catatan'])) : '';
    $jumlah            = (int)$_POST['jumlah'];
    $stok              = (int)$merch['stok'];

    if ($jumlah < 1) {
        $msg = "Jumlah produk minimal 1 Pcs!";
    } elseif ($jumlah > 5) {
        $msg = "Setiap akun hanya diperbolehkan membeli maksimal 5 Pcs produk per transaksi!";
    } elseif ($jumlah > $stok) {
        $msg = "Maaf, stok produk yang tersisa hanya " . $stok . " Pcs!";
    } elseif (empty($metode_pembayaran)) {
        $msg = "Pilih metode pembayaran terlebih dahulu!";
    } else {
        $harga_satuan = $merch['harga'];
        $subtotal     = $jumlah * $harga_satuan;
        $biaya_admin  = 5000.00;
        $ongkos_kirim = 15000.00;
        $total_bayar  = $subtotal + $biaya_admin + $ongkos_kirim;
        $kode_pesanan = "SLR-MERCH-" . rand(10000, 99999);

        // 1. Simpan ke tabel induk pesanan_merch
        $query_insert = "INSERT INTO pesanan_merch (id_pengguna, kode_pesanan, nama_penerima, nomor_telepon, alamat_pengiriman, biaya_admin, ongkos_kirim, total_bayar, metode_pembayaran, status_pesanan, waktu_transaksi) 
                         VALUES ('$id_user', '$kode_pesanan', '$nama_penerima', '$nomor_telepon', '$alamat_pengiriman', '$biaya_admin', '$ongkos_kirim', '$total_bayar', '$metode_pembayaran', 'diproses', NOW())";
        
        if (mysqli_query($koneksi, $query_insert)) {
            $id_pesanan_baru = mysqli_insert_id($koneksi);

            // 2. Simpan ke tabel detail_pesanan_merch
            $query_detail = "INSERT INTO detail_pesanan_merch (id_pesanan, id_produk, ukuran, catatan, jumlah, harga_satuan, subtotal)
                             VALUES ('$id_pesanan_baru', '$id_produk', " . ($ukuran ? "'$ukuran'" : "NULL") . ", '$catatan', '$jumlah', '$harga_satuan', '$subtotal')";
            mysqli_query($koneksi, $query_detail);

            // 3. Kurangi stok produk
            mysqli_query($koneksi, "UPDATE produk_merch SET stok = stok - $jumlah WHERE id_produk = '$id_produk'");
            $success = true;
        } else {
            $msg = "Gagal memproses pesanan: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Beli Merch - <?= htmlspecialchars($merch['nama_produk']); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-black text-white flex items-center justify-center min-h-screen p-4 py-10">

  <div class="w-full max-w-lg bg-[#111] p-8 border border-yellow-400 rounded-2xl relative shadow-2xl">
    
    <!-- TOMBOL CLOSE (X) -->
    <a href="index.php#store" class="absolute top-4 right-4 text-gray-400 hover:text-yellow-400 text-3xl font-bold leading-none transition" title="Batal">&times;</a>

    <h2 class="text-3xl font-black text-yellow-400 text-center mb-6 uppercase tracking-wider">CHECKOUT MERCHANDISE</h2>

    <?php if ($success): ?>
      <div class="bg-green-500/20 border border-green-500 text-green-400 p-6 rounded-xl text-center">
        <div class="text-4xl mb-2">📦</div>
        <h3 class="text-xl font-bold mb-2">Pesanan Berhasil Diproses!</h3>
        <p class="text-sm text-gray-300 mb-1">Kode Pesanan: <strong class="text-yellow-400 font-mono"><?= $kode_pesanan; ?></strong></p>
        <p class="text-sm text-gray-300 mb-4">Pembayaran Via: <strong class="uppercase text-yellow-400"><?= htmlspecialchars($_POST['metode_pembayaran']); ?></strong></p>
        <p class="text-xs text-gray-400 mb-6">Barang akan dikirim ke alamat tujuan setelah konfirmasi.</p>
        <a href="index.php" class="inline-block bg-yellow-400 text-black font-extrabold px-6 py-3 rounded-lg hover:bg-yellow-300 transition">
          KEMBALI KE BERANDA
        </a>
      </div>
    <?php else: ?>

      <?php if ($msg): ?>
        <div class="bg-red-500/20 border border-red-500 text-red-400 p-3 rounded mb-4 text-center text-sm font-bold"><?= $msg; ?></div>
      <?php endif; ?>

      <!-- RINGKASAN PRODUK -->
      <div class="flex gap-4 bg-black p-4 rounded-xl border border-gray-800 mb-6 items-center">
        <img src="<?= htmlspecialchars($merch['gambar_produk']); ?>" class="w-20 h-20 object-cover rounded-lg border border-gray-800" alt="Merch">
        <div class="space-y-1">
          <h3 class="text-lg font-black text-yellow-400"><?= htmlspecialchars($merch['nama_produk']); ?></h3>
          <p class="text-xs text-gray-400 line-clamp-1"><?= htmlspecialchars($merch['deskripsi']); ?></p>
          <p class="text-sm text-white font-mono font-bold">Harga: <span class="text-yellow-400">Rp<?= number_format($merch['harga'], 0, ',', '.'); ?></span></p>
          <p class="text-xs text-emerald-400">Sisa Stok: <?= $merch['stok']; ?> Pcs | <span class="text-yellow-400">Max Beli: 5 Pcs</span></p>
        </div>
      </div>

      <!-- FORM CHECKOUT -->
      <form method="POST" class="space-y-4">
        <div>
          <label class="block mb-1 text-xs font-bold uppercase text-gray-400">Nama Penerima</label>
          <input type="text" name="nama_penerima" value="<?= htmlspecialchars($user_info['Nama'] ?? ''); ?>" required class="w-full bg-black border border-yellow-400 p-3 text-white rounded-lg focus:outline-none">
        </div>

        <div>
          <label class="block mb-1 text-xs font-bold uppercase text-gray-400">Nomor Telepon / WA</label>
          <input type="tel" name="nomor_telepon" value="<?= htmlspecialchars($user_info['Nomor_telepon'] ?? ''); ?>" required class="w-full bg-black border border-yellow-400 p-3 text-white rounded-lg focus:outline-none">
        </div>

        <div>
          <label class="block mb-1 text-xs font-bold uppercase text-gray-400">Alamat Lengkap Pengiriman</label>
          <textarea name="alamat_pengiriman" rows="2" required class="w-full bg-black border border-yellow-400 p-3 text-white rounded-lg focus:outline-none"><?= htmlspecialchars($user_info['alamat'] ?? ''); ?></textarea>
        </div>

        <div>
          <label class="block mb-1 text-xs font-bold uppercase text-gray-400">Jumlah Beli (Maksimal <?= $max_beli_merch; ?> Pcs)</label>
          <input type="number" id="jumlah" name="jumlah" value="1" min="1" max="<?= $max_beli_merch; ?>" required onchange="hitungTotalMerch()" onkeyup="hitungTotalMerch()" class="w-full bg-black border border-yellow-400 p-3 text-white rounded-lg focus:outline-none">
        </div>

        <div>
          <label class="block mb-1 text-xs font-bold uppercase text-gray-400">Pilihan Ukuran (Khusus Kaos / Apparel)</label>
          <select name="ukuran" class="w-full bg-black border border-yellow-400 p-3 text-white rounded-lg focus:outline-none">
            <option value="">- Tidak Perlu Ukuran -</option>
            <option value="S">S</option>
            <option value="M">M</option>
            <option value="L">L</option>
            <option value="XL">XL</option>
            <option value="XXL">XXL</option>
          </select>
        </div>

        <div>
          <label class="block mb-1 text-xs font-bold uppercase text-gray-400">Catatan Pembeli (Opsional)</label>
          <input type="text" name="catatan" placeholder="Contoh: Packing bubble wrap tebal ya min" class="w-full bg-black border border-yellow-400 p-3 text-white rounded-lg focus:outline-none">
        </div>

        <!-- INPUT METODE PEMBAYARAN -->
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
          <span id="display_total_merch" class="text-2xl font-black text-yellow-400 font-mono">Rp<?= number_format($merch['harga'], 0, ',', '.'); ?></span>
        </div>

        <button type="submit" name="proses_beli_merch" class="w-full bg-yellow-400 text-black py-4 font-black rounded-lg hover:bg-yellow-300 transition tracking-wider uppercase text-lg shadow-lg">
          PROSES PESANAN MERCH
        </button>
      </form>

      <script>
        function hitungTotalMerch() {
          const harga = <?= $merch['harga']; ?>;
          let qty = parseInt(document.getElementById('jumlah').value) || 1;
          const max = <?= $max_beli_merch; ?>;

          if (qty > max) {
            qty = max;
            document.getElementById('jumlah').value = max;
          }

          const total = harga * qty;
          document.getElementById('display_total_merch').innerText = 'Rp' + total.toLocaleString('id-ID');
        }
      </script>

    <?php endif; ?>

  </div>

</body>
</html>