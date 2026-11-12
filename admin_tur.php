<?php
include 'admin_header.php';

$success = '';
$error = '';

if (isset($_POST['tambah_tur'])) {
    $nama_acara    = mysqli_real_escape_string($koneksi, trim($_POST['nama_acara']));
    $kota          = mysqli_real_escape_string($koneksi, trim($_POST['kota']));
    $lokasi_venue  = mysqli_real_escape_string($koneksi, trim($_POST['lokasi_venue']));
    $tanggal_acara = mysqli_real_escape_string($koneksi, $_POST['tanggal_acara']);
    $harga_tiket   = mysqli_real_escape_string($koneksi, $_POST['harga_tiket']);
    $sisa_kuota    = mysqli_real_escape_string($koneksi, $_POST['sisa_kuota']);

    $query = "INSERT INTO jadwal_tur (nama_acara, kota, lokasi_venue, tanggal_acara, harga_tiket, sisa_kuota) 
              VALUES ('$nama_acara', '$kota', '$lokasi_venue', '$tanggal_acara', '$harga_tiket', '$sisa_kuota')";
    if (mysqli_query($koneksi, $query)) {
        $success = "Jadwal tur berhasil ditambahkan!";
    } else {
        $error = "Gagal menambah tur: " . mysqli_error($koneksi);
    }
}

if (isset($_POST['edit_tur'])) {
    $id_acara      = mysqli_real_escape_string($koneksi, $_POST['id_acara']);
    $nama_acara    = mysqli_real_escape_string($koneksi, trim($_POST['nama_acara']));
    $kota          = mysqli_real_escape_string($koneksi, trim($_POST['kota']));
    $lokasi_venue  = mysqli_real_escape_string($koneksi, trim($_POST['lokasi_venue']));
    $tanggal_acara = mysqli_real_escape_string($koneksi, $_POST['tanggal_acara']);
    $harga_tiket   = mysqli_real_escape_string($koneksi, $_POST['harga_tiket']);
    $sisa_kuota    = mysqli_real_escape_string($koneksi, $_POST['sisa_kuota']);

    $query = "UPDATE jadwal_tur SET nama_acara='$nama_acara', kota='$kota', lokasi_venue='$lokasi_venue', 
              tanggal_acara='$tanggal_acara', harga_tiket='$harga_tiket', sisa_kuota='$sisa_kuota' 
              WHERE id_acara='$id_acara'";
    if (mysqli_query($koneksi, $query)) {
        $success = "Data tur berhasil diperbarui!";
    } else {
        $error = "Gagal memperbarui tur: " . mysqli_error($koneksi);
    }
}

if (isset($_GET['hapus'])) {
    $id_acara = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    if (mysqli_query($koneksi, "DELETE FROM jadwal_tur WHERE id_acara='$id_acara'")) {
        $success = "Jadwal tur berhasil dihapus!";
    } else {
        $error = "Gagal menghapus tur: " . mysqli_error($koneksi);
    }
}

$tur_edit = null;
if (isset($_GET['edit'])) {
    $id_edit = mysqli_real_escape_string($koneksi, $_GET['edit']);
    $res_edit = mysqli_query($koneksi, "SELECT * FROM jadwal_tur WHERE id_acara='$id_edit'");
    if (mysqli_num_rows($res_edit) === 1) {
        $tur_edit = mysqli_fetch_assoc($res_edit);
    }
}

$query_tur = mysqli_query($koneksi, "SELECT * FROM jadwal_tur ORDER BY tanggal_acara ASC");
?>

<main class="app-main p-4">
  <div class="app-content-header mb-4">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h3 class="mb-0"><i class="fa-solid fa-guitar me-2 text-warning"></i>Kelola Jadwal Tur Konser</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="admin.php">Admin</a></li>
            <li class="breadcrumb-item active">Jadwal Tur</li>
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

      <div class="row">
        <!-- FORM INPUT/EDIT -->
        <div class="col-lg-4 mb-4">
          <div class="card card-outline card-warning">
            <div class="card-header">
              <h3 class="card-title"><?= $tur_edit ? '<i class="fa-solid fa-pen-to-square me-1"></i> Edit Tur' : '<i class="fa-solid fa-plus me-1"></i> Tambah Tur Konser' ?></h3>
            </div>
            <form method="POST" action="admin_tur.php">
              <div class="card-body">
                <?php if($tur_edit): ?>
                  <input type="hidden" name="id_acara" value="<?= $tur_edit['id_acara']; ?>">
                <?php endif; ?>

                <div class="mb-3">
                  <label class="form-label font-weight-bold">Nama Acara</label>
                  <input type="text" name="nama_acara" value="<?= htmlspecialchars($tur_edit['nama_acara'] ?? ''); ?>" required placeholder="Misal: SLR Tour Jakarta" class="form-control">
                </div>

                <div class="row">
                  <div class="col-6 mb-3">
                    <label class="form-label font-weight-bold">Kota</label>
                    <input type="text" name="kota" value="<?= htmlspecialchars($tur_edit['kota'] ?? ''); ?>" required placeholder="Jakarta" class="form-control">
                  </div>
                  <div class="col-6 mb-3">
                    <label class="form-label font-weight-bold">Venue</label>
                    <input type="text" name="lokasi_venue" value="<?= htmlspecialchars($tur_edit['lokasi_venue'] ?? ''); ?>" required placeholder="Punk Arena" class="form-control">
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label font-weight-bold">Tanggal & Waktu</label>
                  <input type="datetime-local" name="tanggal_acara" value="<?= isset($tur_edit) ? date('Y-m-d\TH:i', strtotime($tur_edit['tanggal_acara'])) : ''; ?>" required class="form-control">
                </div>

                <div class="row">
                  <div class="col-6 mb-3">
                    <label class="form-label font-weight-bold">Harga Tiket (Rp)</label>
                    <input type="number" name="harga_tiket" value="<?= $tur_edit['harga_tiket'] ?? '100000'; ?>" required class="form-control">
                  </div>
                  <div class="col-6 mb-3">
                    <label class="form-label font-weight-bold">Sisa Kuota</label>
                    <input type="number" name="sisa_kuota" value="<?= $tur_edit['sisa_kuota'] ?? '50'; ?>" required class="form-control">
                  </div>
                </div>
              </div>
              <div class="card-footer d-flex gap-2">
                <?php if($tur_edit): ?>
                  <button type="submit" name="edit_tur" class="btn btn-warning text-dark font-weight-bold flex-fill">Simpan</button>
                  <a href="admin_tur.php" class="btn btn-secondary">Batal</a>
                <?php else: ?>
                  <button type="submit" name="tambah_tur" class="btn btn-warning text-dark font-weight-bold w-100">Tambah Tur</button>
                <?php endif; ?>
              </div>
            </form>
          </div>
        </div>

        <!-- TABEL -->
        <div class="col-lg-8">
          <div class="card card-outline card-warning">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h3 class="card-title"><i class="fa-solid fa-calendar-days me-2"></i> Daftar Jadwal Konser</h3>
              <span class="badge text-bg-warning ms-auto">Total: <?= mysqli_num_rows($query_tur); ?> Konser</span>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                  <thead>
                    <tr>
                      <th>Acara & Kota</th>
                      <th>Tanggal & Venue</th>
                      <th>Harga</th>
                      <th>Kuota</th>
                      <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (mysqli_num_rows($query_tur) > 0): ?>
                      <?php while ($row = mysqli_fetch_assoc($query_tur)): ?>
                        <tr>
                          <td>
                            <strong class="text-warning"><?= htmlspecialchars($row['nama_acara']); ?></strong><br>
                            <small class="text-body-secondary"><i class="fa-solid fa-location-dot me-1"></i><?= htmlspecialchars($row['kota']); ?></small>
                          </td>
                          <td>
                            <small class="fw-bold"><?= date('d M Y, H:i', strtotime($row['tanggal_acara'])); ?></small><br>
                            <small class="text-body-secondary"><i class="fa-solid fa-building me-1"></i><?= htmlspecialchars($row['lokasi_venue']); ?></small>
                          </td>
                          <td class="font-monospace fw-bold">Rp<?= number_format($row['harga_tiket'], 0, ',', '.'); ?></td>
                          <td><span class="badge text-bg-warning"><?= $row['sisa_kuota']; ?> Slot</span></td>
                          <td class="text-center">
                            <a href="admin_tur.php?edit=<?= $row['id_acara']; ?>" class="btn btn-sm btn-warning me-1" title="Edit"><i class="fa-solid fa-pen"></i></a>
                            <a href="admin_tur.php?hapus=<?= $row['id_acara']; ?>" onclick="return confirm('Hapus konser ini?');" class="btn btn-sm btn-danger" title="Hapus"><i class="fa-solid fa-trash"></i></a>
                          </td>
                        </tr>
                      <?php endwhile; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="5" class="text-center text-body-secondary py-3">Belum ada konser terjadwal.</td>
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