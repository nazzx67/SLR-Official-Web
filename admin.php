<?php
include 'admin_header.php';

// Hitung Statistik Data
$total_pengguna = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pengguna"))['total'];
$total_lagu     = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM lagu"))['total'];
$total_tur      = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM jadwal_tur"))['total'];
$total_pesan    = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pesan_kontak"))['total'];

// Ambil 5 Pesanan Tiket Terbaru
$query_tiket_recent = mysqli_query($koneksi, "SELECT t.*, p.Nama, j.nama_acara 
                                               FROM pesanan_tiket t 
                                               JOIN pengguna p ON t.id_pengguna = p.id_pengguna 
                                               JOIN jadwal_tur j ON t.id_acara = j.id_acara 
                                               ORDER BY t.waktu_transaksi DESC LIMIT 5");
?>

<main class="app-main p-4">
  <div class="app-content-header mb-4">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h3 class="mb-0">Dashboard Overview</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="admin.php">Admin</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">
      
      <!-- SMALL BOX STATISTIK ADMINLTE 4 -->
      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="small-box text-bg-primary">
            <div class="inner">
              <h3><?= $total_pengguna; ?></h3>
              <p>Fans Terdaftar</p>
            </div>
            <div class="icon"><i class="fa-solid fa-users"></i></div>
            <a href="admin_pesanan.php" class="small-box-footer link-light link-underline-opacity-0">Detail <i class="fa-solid fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box text-bg-success">
            <div class="inner">
              <h3><?= $total_lagu; ?></h3>
              <p>Katalog Lagu</p>
            </div>
            <div class="icon"><i class="fa-solid fa-music"></i></div>
            <a href="admin_lagu.php" class="small-box-footer link-light link-underline-opacity-0">Kelola <i class="fa-solid fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box text-bg-warning">
            <div class="inner">
              <h3><?= $total_tur; ?></h3>
              <p>Jadwal Konser</p>
            </div>
            <div class="icon"><i class="fa-solid fa-guitar"></i></div>
            <a href="admin_tur.php" class="small-box-footer link-dark link-underline-opacity-0">Kelola <i class="fa-solid fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box text-bg-danger">
            <div class="inner">
              <h3><?= $total_pesan; ?></h3>
              <p>Pesan Masuk</p>
            </div>
            <div class="icon"><i class="fa-solid fa-envelope"></i></div>
            <a href="admin_kontak.php" class="small-box-footer link-light link-underline-opacity-0">Baca <i class="fa-solid fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>

      <!-- TABEL TRANSAKSI TERBARU -->
      <div class="row mt-4">
        <div class="col-12">
          <div class="card card-outline card-warning">
            <div class="card-header">
              <h3 class="card-title"><i class="fa-solid fa-ticket me-2"></i> Transaksi Tiket Terbaru</h3>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                  <thead>
                    <tr>
                      <th>Pembeli</th>
                      <th>Acara Konser</th>
                      <th>Jumlah</th>
                      <th>Total Bayar</th>
                      <th>Waktu</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (mysqli_num_rows($query_tiket_recent) > 0): ?>
                      <?php while ($row = mysqli_fetch_assoc($query_tiket_recent)): ?>
                        <tr>
                          <td><strong><?= htmlspecialchars($row['Nama']); ?></strong></td>
                          <td><span class="badge text-bg-warning"><?= htmlspecialchars($row['nama_acara']); ?></span></td>
                          <td><?= $row['jumlah_tiket']; ?> Lembar</td>
                          <td class="font-monospace">Rp<?= number_format($row['total_bayar'], 0, ',', '.'); ?></td>
                          <td><small class="text-body-secondary"><?= date('d M Y, H:i', strtotime($row['waktu_transaksi'])); ?></small></td>
                        </tr>
                      <?php endwhile; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="5" class="text-center text-body-secondary py-3">Belum ada transaksi tiket masuk.</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</main>

<?php include 'admin_footer.php'; ?>