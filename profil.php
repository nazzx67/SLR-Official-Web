<?php
include 'header.php';

if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];
$success = '';
$error = '';

// Proses Simpan Profil
if (isset($_POST['save_profile'])) {
    $nama          = mysqli_real_escape_string($koneksi, trim($_POST['Nama']));
    $nomor_telepon = mysqli_real_escape_string($koneksi, trim($_POST['Nomor_telepon']));
    $alamat        = mysqli_real_escape_string($koneksi, trim($_POST['alamat']));

    $foto_update_sql = "";

    // Upload Foto Profil
    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['foto_profil']['tmp_name'];
        $file_name = $_FILES['foto_profil']['name'];
        $file_size = $_FILES['foto_profil']['size'];
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'jfif'];
        if (!in_array($file_ext, $allowed)) {
            $error = "Format foto tidak didukung! Gunakan format JPG, PNG, atau WEBP.";
        } elseif ($file_size > 3 * 1024 * 1024) {
            $error = "Ukuran foto terlalu besar! Maksimal 3MB.";
        } else {
            $target_dir = 'Images/profile/';
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $nama_foto = 'avatar_' . $id_pengguna . '_' . time() . '.' . $file_ext;
            $target_file = $target_dir . $nama_foto;

            if (move_uploaded_file($file_tmp, $target_file)) {
                $foto_update_sql = ", foto_profil='$target_file'";
            } else {
                $error = "Gagal mengunggah foto profil.";
            }
        }
    }

    if (empty($error)) {
        $update = mysqli_query($koneksi, "UPDATE pengguna SET Nama='$nama', Nomor_telepon='$nomor_telepon', alamat='$alamat' $foto_update_sql WHERE id_pengguna='$id_pengguna'");
        if ($update) {
            $_SESSION['nama_pengguna'] = $nama;
            $success = "Data profil dan foto berhasil diperbarui!";
        } else {
            $error = "Gagal memperbarui profil: " . mysqli_error($koneksi);
        }
    }
}

// Ambil Data Profil Pengguna
$query_user = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE id_pengguna='$id_pengguna'");
$user = mysqli_fetch_assoc($query_user);

// Ambil Riwayat Tiket
$query_tiket = mysqli_query($koneksi, "SELECT t.*, j.nama_acara, j.kota, j.tanggal_acara 
                                       FROM pesanan_tiket t 
                                       JOIN jadwal_tur j ON t.id_acara = j.id_acara 
                                       WHERE t.id_pengguna='$id_pengguna' 
                                       ORDER BY t.waktu_transaksi DESC");

// Ambil Riwayat Merch
$query_merch = mysqli_query($koneksi, "SELECT * FROM pesanan_merch WHERE id_pengguna='$id_pengguna' ORDER BY waktu_transaksi DESC");
?>

<main class="pt-28 pb-20 px-6 max-w-7xl mx-auto">
  
  <!-- JUDUL HALAMAN -->
  <div class="border-b border-yellow-400/30 pb-4 mb-8">
    <h1 class="text-4xl font-black text-yellow-400 tracking-wider">PROFIL FANS SLR</h1>
    <p class="text-gray-400 text-sm mt-1">Kelola informasi diri Anda dan lihat riwayat transaksi konser & merch.</p>
  </div>

  <?php if($success): ?>
    <div class="bg-green-500/20 border border-green-500 text-green-400 p-4 rounded mb-6 font-bold flex items-center justify-between">
      <span>✅ <?= $success ?></span>
    </div>
  <?php endif; ?>

  <?php if($error): ?>
    <div class="bg-red-500/20 border border-red-500 text-red-400 p-4 rounded mb-6 font-bold flex items-center justify-between">
      <span>❌ <?= $error ?></span>
    </div>
  <?php endif; ?>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- KOLOM KIRI: CARD IDENTITAS FANS -->
    <div class="space-y-6">
      
      <!-- FANS BADGE CARD -->
      <div class="bg-[#111] border-2 border-yellow-400 p-6 rounded-xl text-center relative overflow-hidden">
        <div class="absolute top-0 right-0 bg-yellow-400 text-black text-[10px] font-black px-3 py-1 uppercase tracking-widest">
          Official Army
        </div>
        
        <?php
        $avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($user['Nama']) . "&background=facc15&color=000&size=128";
        if (!empty($user['foto_profil']) && $user['foto_profil'] !== 'default.jpg' && file_exists($user['foto_profil'])) {
            $avatar_url = htmlspecialchars($user['foto_profil']);
        }
        ?>
        <img src="<?= $avatar_url; ?>" 
             alt="Avatar" 
             class="w-28 h-28 rounded-full mx-auto border-4 border-yellow-400 mb-4 shadow-lg object-cover">
        
        <h2 class="text-2xl font-black text-white uppercase"><?= htmlspecialchars($user['Nama']); ?></h2>
        <p class="text-yellow-400 text-sm font-semibold mb-4"><?= htmlspecialchars($user['email']); ?></p>
        
        <div class="bg-black/60 p-3 rounded border border-gray-800 text-xs text-left space-y-1 text-gray-300">
          <p><span class="text-gray-500">ID Fans:</span> <span class="font-mono text-yellow-400">SLR-MEMBER-<?= sprintf('%04d', $user['id_pengguna']); ?></span></p>
          <p><span class="text-gray-500">Bergabung:</span> <?= date('d M Y', strtotime($user['dibuat_pada'])); ?></p>
        </div>
      </div>

      <!-- PETUNJUK CHECKOUT -->
      <div class="bg-yellow-400/10 border border-yellow-400/30 p-5 rounded-xl text-xs text-yellow-300 space-y-2">
        <p class="font-bold text-yellow-400 text-sm">💡 Info Otomatisasi Checkout:</p>
        <p>Data Nama, No HP, dan Alamat di samping akan otomatis terisi saat Anda membeli tiket tur atau merchandise band.</p>
      </div>

    </div>

    <!-- KOLOM KANAN: FORM EDIT & RIWAYAT -->
    <div class="lg:col-span-2 space-y-8">
      
      <!-- FORM EDIT INFORMASI DIRI -->
      <div class="bg-[#111] border border-gray-800 p-6 md:p-8 rounded-xl">
        <h3 class="text-xl font-black text-yellow-400 mb-6 flex items-center gap-2 border-b border-gray-800 pb-3">
          📝 EDIT INFORMASI DIRI & FOTO PROFIL
        </h3>

        <form method="POST" enctype="multipart/form-data" class="space-y-5">
          <div class="grid md:grid-cols-2 gap-5">
            <div>
              <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Nama Lengkap</label>
              <input type="text" name="Nama" value="<?= htmlspecialchars($user['Nama']); ?>" required 
                     class="w-full bg-black border border-gray-700 p-3 text-white rounded focus:border-yellow-400 focus:outline-none">
            </div>

            <div>
              <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Nomor Telepon / WhatsApp</label>
              <input type="tel" name="Nomor_telepon" value="<?= htmlspecialchars($user['Nomor_telepon']); ?>" required 
                     class="w-full bg-black border border-gray-700 p-3 text-white rounded focus:border-yellow-400 focus:outline-none">
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Alamat Pengiriman Utama</label>
            <textarea name="alamat" rows="3" required 
                      class="w-full bg-black border border-gray-700 p-3 text-white rounded focus:border-yellow-400 focus:outline-none"><?= htmlspecialchars($user['alamat']); ?></textarea>
          </div>

          <!-- INPUT UPLOAD FOTO PROFIL -->
          <div class="p-4 rounded-lg bg-black border border-gray-800">
            <label class="block text-xs font-bold text-yellow-400 uppercase mb-2">
              📸 Unggah / Ganti Foto Profil
            </label>
            <input type="file" name="foto_profil" accept="image/*" 
                   class="w-full bg-[#111] border border-gray-700 p-2 text-white text-sm rounded focus:border-yellow-400 focus:outline-none cursor-pointer">
            <span class="text-xs text-gray-500 mt-2 block">
              Pilih foto dari komputer/HP (format JPG, PNG, atau WEBP, maks 3MB). Biarkan kosong jika tidak ingin mengubah foto.
            </span>
          </div>

          <button type="submit" name="save_profile" 
                  class="bg-yellow-400 text-black px-8 py-3 font-extrabold hover:bg-yellow-300 transition rounded">
            SIMPAN PERUBAHAN
          </button>
        </form>
      </div>

      <!-- RIWAYAT PEMBELIAN TIKET -->
      <div class="bg-[#111] border border-gray-800 p-6 rounded-xl">
        <h3 class="text-xl font-black text-yellow-400 mb-4 flex items-center gap-2">
          🎫 RIWAYAT TIKET KONSER
        </h3>
        
        <?php if (mysqli_num_rows($query_tiket) > 0): ?>
          <div class="space-y-3">
            <?php while($tiket = mysqli_fetch_assoc($query_tiket)): ?>
              <div class="bg-black p-4 rounded border border-gray-800 flex justify-between items-center flex-wrap gap-3">
                <div>
                  <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-mono font-bold bg-yellow-400 text-black px-2 py-0.5 rounded"><?= htmlspecialchars($tiket['kode_tiket'] ?? 'SLR-TKT-'.$tiket['id_pesanan']); ?></span>
                    <h4 class="font-bold text-yellow-400 text-base"><?= htmlspecialchars($tiket['nama_acara']); ?> (<?= htmlspecialchars($tiket['kota']); ?>)</h4>
                  </div>
                  <p class="text-xs text-gray-400">📅 <?= date('d M Y, H:i', strtotime($tiket['tanggal_acara'])); ?> | <?= $tiket['jumlah_tiket']; ?> Lembar Tiket</p>
                </div>
                <div class="text-right">
                  <span class="text-sm font-extrabold text-white">Rp<?= number_format($tiket['total_bayar'], 0, ',', '.'); ?></span>
                  <?php
                  $st_tiket = strtolower($tiket['status_pembayaran'] ?? 'lunas');
                  if ($st_tiket === 'batal') {
                      $badge_color = 'bg-red-500/20 text-red-400 border-red-500/40';
                  } elseif ($st_tiket === 'menunggu') {
                      $badge_color = 'bg-yellow-500/20 text-yellow-400 border-yellow-500/40';
                  } else {
                      $badge_color = 'bg-green-500/20 text-green-400 border-green-500/40';
                  }
                  ?>
                  <span class="block text-[10px] <?= $badge_color; ?> border px-2.5 py-0.5 rounded font-black mt-1 uppercase tracking-wider">
                    <?= htmlspecialchars($tiket['status_pembayaran'] ?? 'LUNAS'); ?>
                  </span>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        <?php else: ?>
          <p class="text-sm text-gray-500 italic">Belum ada riwayat pemesanan tiket konser.</p>
        <?php endif; ?>
      </div>

      <!-- RIWAYAT PEMBELIAN MERCHANDISE -->
      <div class="bg-[#111] border border-gray-800 p-6 rounded-xl">
        <h3 class="text-xl font-black text-yellow-400 mb-4 flex items-center gap-2">
          📦 RIWAYAT PESANAN MERCHANDISE
        </h3>
        
        <?php if (mysqli_num_rows($query_merch) > 0): ?>
          <div class="space-y-3">
            <?php while($m = mysqli_fetch_assoc($query_merch)): 
              $m_id = $m['id_pesanan'];
              $m_items = mysqli_query($koneksi, "SELECT d.*, p.nama_produk FROM detail_pesanan_merch d JOIN produk_merch p ON d.id_produk=p.id_produk WHERE d.id_pesanan=$m_id");
            ?>
              <div class="bg-black p-4 rounded border border-gray-800 flex justify-between items-center flex-wrap gap-3">
                <div>
                  <span class="text-xs font-mono font-bold bg-yellow-400 text-black px-2 py-0.5 rounded"><?= htmlspecialchars($m['kode_pesanan']); ?></span>
                  <div class="mt-2 text-sm text-gray-200">
                    <?php if (mysqli_num_rows($m_items) > 0): ?>
                      <?php while($item = mysqli_fetch_assoc($m_items)): ?>
                        <span><?= htmlspecialchars($item['nama_produk']); ?> (<?= $item['jumlah']; ?>x<?= $item['ukuran'] ? ', Size: '.$item['ukuran'] : ''; ?>)</span><br>
                      <?php endwhile; ?>
                    <?php else: ?>
                      <span>Paket Merchandise</span>
                    <?php endif; ?>
                  </div>
                  <?php if (!empty($m['nomor_resi'])): ?>
                    <p class="text-xs text-yellow-400 font-mono mt-1">🚚 No. Resi: <?= htmlspecialchars($m['nomor_resi']); ?></p>
                  <?php endif; ?>
                  <p class="text-[11px] text-gray-500 mt-1">📅 <?= date('d M Y, H:i', strtotime($m['waktu_transaksi'])); ?></p>
                </div>
                <div class="text-right">
                  <span class="text-sm font-extrabold text-white">Rp<?= number_format($m['total_bayar'], 0, ',', '.'); ?></span>
                  <?php
                  $st_merch = strtolower($m['status_pesanan'] ?? 'diproses');
                  if ($st_merch === 'dibatalkan' || $st_merch === 'batal') {
                      $badge_merch_color = 'bg-red-500/20 text-red-400 border-red-500/40';
                  } elseif ($st_merch === 'selesai') {
                      $badge_merch_color = 'bg-green-500/20 text-green-400 border-green-500/40';
                  } elseif ($st_merch === 'dikirim') {
                      $badge_merch_color = 'bg-indigo-500/20 text-indigo-400 border-indigo-500/40';
                  } elseif ($st_merch === 'diproses') {
                      $badge_merch_color = 'bg-blue-500/20 text-blue-400 border-blue-500/40';
                  } else {
                      $badge_merch_color = 'bg-yellow-500/20 text-yellow-400 border-yellow-500/40';
                  }
                  ?>
                  <span class="block text-[10px] <?= $badge_merch_color; ?> border px-2.5 py-0.5 rounded font-black mt-1 uppercase tracking-wider">
                    <?= htmlspecialchars($m['status_pesanan'] ?? 'diproses'); ?>
                  </span>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        <?php else: ?>
          <p class="text-sm text-gray-500 italic">Belum ada riwayat pemesanan merchandise.</p>
        <?php endif; ?>
      </div>

    </div>

  </div>
</main>

<?php include 'footer.php'; ?>