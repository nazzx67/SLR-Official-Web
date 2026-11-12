<?php
include 'admin_header.php';

$success = '';
$error = '';

// AKSI 1: HAPUS TIKET
if (isset($_GET['hapus_tiket'])) {
    $id = (int)$_GET['hapus_tiket'];
    if (mysqli_query($koneksi, "DELETE FROM pesanan_tiket WHERE id_pesanan = $id")) {
        $success = "Data pesanan tiket berhasil dihapus!";
    } else {
        $error = "Gagal menghapus tiket: " . mysqli_error($koneksi);
    }
}

// AKSI 2: UPDATE STATUS TIKET
if (isset($_POST['update_status_tiket'])) {
    $id = (int)$_POST['id_pesanan'];
    $status = mysqli_real_escape_string($koneksi, $_POST['status_pembayaran']);
    if (mysqli_query($koneksi, "UPDATE pesanan_tiket SET status_pembayaran='$status' WHERE id_pesanan=$id")) {
        $success = "Status pembayaran tiket berhasil diperbarui!";
    } else {
        $error = "Gagal memperbarui status: " . mysqli_error($koneksi);
    }
}

// AKSI 3: HAPUS PESANAN MERCH
if (isset($_GET['hapus_merch'])) {
    $id = (int)$_GET['hapus_merch'];
    if (mysqli_query($koneksi, "DELETE FROM pesanan_merch WHERE id_pesanan = $id")) {
        $success = "Data pesanan merchandise berhasil dihapus!";
    } else {
        $error = "Gagal menghapus pesanan merch: " . mysqli_error($koneksi);
    }
}

// AKSI 4: UPDATE STATUS & RESI MERCH
if (isset($_POST['update_status_merch'])) {
    $id = (int)$_POST['id_pesanan'];
    $status = mysqli_real_escape_string($koneksi, $_POST['status_pesanan']);
    $resi = mysqli_real_escape_string($koneksi, trim($_POST['nomor_resi']));
    if (mysqli_query($koneksi, "UPDATE pesanan_merch SET status_pesanan='$status', nomor_resi='$resi' WHERE id_pesanan=$id")) {
        $success = "Status pesanan merch dan nomor resi berhasil diperbarui!";
    } else {
        $error = "Gagal memperbarui pesanan merch: " . mysqli_error($koneksi);
    }
}

// QUERY DATA TIKET
$query_tiket = mysqli_query($koneksi, "SELECT t.*, p.Nama, p.email, j.nama_acara, j.kota 
                                       FROM pesanan_tiket t 
                                       JOIN pengguna p ON t.id_pengguna = p.id_pengguna 
                                       JOIN jadwal_tur j ON t.id_acara = j.id_acara 
                                       ORDER BY t.waktu_transaksi DESC");

// QUERY DATA MERCH
$query_merch = mysqli_query($koneksi, "SELECT m.*, p.Nama as nama_pemesan, p.email 
                                       FROM pesanan_merch m 
                                       LEFT JOIN pengguna p ON m.id_pengguna = p.id_pengguna 
                                       ORDER BY m.waktu_transaksi DESC");
?>

<main class="app-main p-4">
  <div class="app-content-header mb-4">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h3 class="mb-0"><i class="fa-solid fa-ticket me-2 text-warning"></i>Data Pesanan Tiket & Merch</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="admin.php">Admin</a></li>
            <li class="breadcrumb-item active">Data Pesanan</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">

      <?php if($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="fa-solid fa-circle-check me-2"></i><?= $success ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <?php if($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="fa-solid fa-circle-xmark me-2"></i><?= $error ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <!-- TABEL PESANAN TIKET -->
      <div class="card card-outline card-warning mb-4">
        <div class="card-header">
          <h3 class="card-title"><i class="fa-solid fa-ticket me-2"></i> Transaksi Pembelian Tiket Konser</h3>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 align-middle">
              <thead>
                <tr>
                  <th>Kode Tiket</th>
                  <th>Nama Pembeli</th>
                  <th>Konser Acara</th>
                  <th>Jumlah</th>
                  <th>Total Bayar</th>
                  <th>Status</th>
                  <th>Waktu</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if (mysqli_num_rows($query_tiket) > 0): ?>
                  <?php while ($row = mysqli_fetch_assoc($query_tiket)): ?>
                    <tr>
                      <td><span class="font-monospace badge text-bg-warning"><?= htmlspecialchars($row['kode_tiket'] ?? 'SLR-TKT-'.$row['id_pesanan']); ?></span></td>
                      <td>
                        <strong><?= htmlspecialchars($row['Nama']); ?></strong><br>
                        <small class="text-body-secondary"><?= htmlspecialchars($row['email']); ?></small>
                      </td>
                      <td><span class="badge text-bg-dark"><?= htmlspecialchars($row['nama_acara']); ?> (<?= htmlspecialchars($row['kota']); ?>)</span></td>
                      <td><?= $row['jumlah_tiket']; ?> Lembar</td>
                      <td class="font-monospace fw-bold">Rp<?= number_format($row['total_bayar'], 0, ',', '.'); ?></td>
                      <td>
                        <?php
                        $stk = strtolower($row['status_pembayaran'] ?? 'lunas');
                        $badge_class = ($stk === 'batal') ? 'text-bg-danger' : (($stk === 'menunggu') ? 'text-bg-warning' : 'text-bg-success');
                        ?>
                        <span class="badge <?= $badge_class; ?> mb-1 text-uppercase"><?= htmlspecialchars($row['status_pembayaran'] ?? 'lunas'); ?></span>
                        <form method="POST" class="d-flex gap-1 align-items-center">
                          <input type="hidden" name="id_pesanan" value="<?= $row['id_pesanan']; ?>">
                          <select name="status_pembayaran" class="form-select form-select-sm" style="width:115px;" onchange="this.form.submit()">
                            <option value="menunggu" <?= ($row['status_pembayaran'] ?? '') === 'menunggu' ? 'selected' : ''; ?>>Menunggu</option>
                            <option value="lunas" <?= ($row['status_pembayaran'] ?? 'lunas') === 'lunas' ? 'selected' : ''; ?>>Lunas</option>
                            <option value="batal" <?= ($row['status_pembayaran'] ?? '') === 'batal' ? 'selected' : ''; ?>>Batal</option>
                          </select>
                          <input type="hidden" name="update_status_tiket" value="1">
                        </form>
                      </td>
                      <td><small class="text-body-secondary"><?= date('d M Y, H:i', strtotime($row['waktu_transaksi'])); ?></small></td>
                      <td>
                        <a href="admin_pesanan.php?hapus_tiket=<?= $row['id_pesanan']; ?>" 
                           onclick="return confirm('Yakin ingin menghapus data tiket ini?');" 
                           class="btn btn-sm btn-danger" title="Hapus Tiket">
                          <i class="fa-solid fa-trash"></i>
                        </a>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="8" class="text-center text-body-secondary py-3">Belum ada pesanan tiket.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- TABEL PESANAN MERCH -->
      <div class="card card-outline card-warning">
        <div class="card-header">
          <h3 class="card-title"><i class="fa-solid fa-box me-2"></i> Transaksi Pemesanan Merchandise & Detail Produk</h3>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 align-middle">
              <thead>
                <tr>
                  <th>Kode & Waktu</th>
                  <th>Penerima & Alamat</th>
                  <th>Rincian Produk</th>
                  <th>Total Bayar</th>
                  <th>Status & Resi</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if (mysqli_num_rows($query_merch) > 0): ?>
                  <?php while ($row = mysqli_fetch_assoc($query_merch)): 
                    $id_pes = $row['id_pesanan'];
                    // Ambil detail produk
                    $q_det = mysqli_query($koneksi, "SELECT d.*, p.nama_produk, p.gambar_produk 
                                                      FROM detail_pesanan_merch d 
                                                      JOIN produk_merch p ON d.id_produk = p.id_produk 
                                                      WHERE d.id_pesanan = $id_pes");
                  ?>
                    <tr>
                      <td>
                        <span class="font-monospace badge text-bg-warning"><?= htmlspecialchars($row['kode_pesanan']); ?></span><br>
                        <small class="text-body-secondary"><?= date('d M Y, H:i', strtotime($row['waktu_transaksi'])); ?></small>
                      </td>
                      <td>
                        <strong><?= htmlspecialchars($row['nama_penerima']); ?></strong><br>
                        <small><i class="fa-solid fa-phone me-1"></i><?= htmlspecialchars($row['nomor_telepon']); ?></small><br>
                        <small class="text-body-secondary"><?= htmlspecialchars($row['alamat_pengiriman']); ?></small>
                      </td>
                      <td>
                        <?php if (mysqli_num_rows($q_det) > 0): ?>
                          <?php while ($det = mysqli_fetch_assoc($q_det)): ?>
                            <div class="d-flex align-items-center gap-2 mb-1">
                              <span class="badge text-bg-dark"><?= htmlspecialchars($det['nama_produk']); ?></span>
                              <?php if(!empty($det['ukuran'])): ?>
                                <span class="badge text-bg-secondary">Size: <?= htmlspecialchars($det['ukuran']); ?></span>
                              <?php endif; ?>
                              <span class="text-warning font-monospace"><?= $det['jumlah']; ?>x</span>
                            </div>
                            <?php if(!empty($det['catatan'])): ?>
                              <small class="text-body-secondary d-block fst-italic">Ket: <?= htmlspecialchars($det['catatan']); ?></small>
                            <?php endif; ?>
                          <?php endwhile; ?>
                        <?php else: ?>
                          <small class="text-body-secondary fst-italic">Item produk standar</small>
                        <?php endif; ?>
                      </td>
                      <td class="font-monospace fw-bold text-nowrap">
                        Rp<?= number_format($row['total_bayar'], 0, ',', '.'); ?><br>
                        <small class="text-body-secondary uppercase badge text-bg-light"><?= htmlspecialchars($row['metode_pembayaran'] ?? 'QRIS'); ?></small>
                      </td>
                      <td>
                        <?php
                        $stm = strtolower($row['status_pesanan'] ?? 'diproses');
                        $badge_m_class = match($stm) {
                            'selesai' => 'text-bg-success',
                            'dibatalkan', 'batal' => 'text-bg-danger',
                            'menunggu' => 'text-bg-warning',
                            'dikirim' => 'text-bg-info',
                            default => 'text-bg-primary'
                        };
                        ?>
                        <span class="badge <?= $badge_m_class; ?> mb-1 text-uppercase"><?= htmlspecialchars($row['status_pesanan'] ?? 'diproses'); ?></span>
                        <form method="POST" class="d-flex flex-column gap-1">
                          <input type="hidden" name="id_pesanan" value="<?= $row['id_pesanan']; ?>">
                          <select name="status_pesanan" class="form-select form-select-sm" style="min-width:130px;">
                            <option value="menunggu" <?= ($row['status_pesanan'] ?? '') === 'menunggu' ? 'selected' : ''; ?>>Menunggu</option>
                            <option value="diproses" <?= ($row['status_pesanan'] ?? 'diproses') === 'diproses' ? 'selected' : ''; ?>>Diproses</option>
                            <option value="dikirim" <?= ($row['status_pesanan'] ?? '') === 'dikirim' ? 'selected' : ''; ?>>Dikirim</option>
                            <option value="selesai" <?= ($row['status_pesanan'] ?? '') === 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                            <option value="dibatalkan" <?= ($row['status_pesanan'] ?? '') === 'dibatalkan' ? 'selected' : ''; ?>>Dibatalkan</option>
                          </select>
                          <input type="text" name="nomor_resi" value="<?= htmlspecialchars($row['nomor_resi'] ?? ''); ?>" placeholder="No. Resi Pengiriman" class="form-control form-control-sm">
                          <button type="submit" name="update_status_merch" class="btn btn-sm btn-outline-warning">Simpan Status</button>
                        </form>
                      </td>
                      <td>
                        <a href="admin_pesanan.php?hapus_merch=<?= $row['id_pesanan']; ?>" 
                           onclick="return confirm('Yakin ingin menghapus data pesanan merchandise ini?');" 
                           class="btn btn-sm btn-danger" title="Hapus Pesanan">
                          <i class="fa-solid fa-trash"></i>
                        </a>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="6" class="text-center text-body-secondary py-3">Belum ada pesanan merchandise.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</main>

<?php include 'admin_footer.php'; ?>
