<?php
include 'admin_header.php';

$success = '';
$error = '';

if (isset($_GET['hapus'])) {
    $id_pesan = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    if (mysqli_query($koneksi, "DELETE FROM pesan_kontak WHERE id_pesan='$id_pesan'")) {
        $success = "Pesan berhasil dihapus!";
    } else {
        $error = "Gagal menghapus pesan: " . mysqli_error($koneksi);
    }
}

$query_pesan = mysqli_query($koneksi, "SELECT * FROM pesan_kontak ORDER BY waktu_diterima DESC");
?>

<main class="app-main p-4">
  <div class="app-content-header mb-4">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h3 class="mb-0"><i class="fa-solid fa-envelope me-2 text-warning"></i>Pesan Masuk dari Fans</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="admin.php">Admin</a></li>
            <li class="breadcrumb-item active">Pesan Masuk</li>
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

      <div class="card card-outline card-warning">
        <div class="card-header">
          <h3 class="card-title"><i class="fa-solid fa-inbox me-2"></i> Kotak Masuk Formulir Kontak</h3>
        </div>
        <div class="card-body">
          <?php if (mysqli_num_rows($query_pesan) > 0): ?>
            <div class="row g-3">
              <?php while ($row = mysqli_fetch_assoc($query_pesan)): ?>
                <div class="col-12">
                  <div class="p-3 border border-secondary border-opacity-25 rounded bg-body-secondary">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <div>
                        <strong class="text-warning font-size-5"><?= htmlspecialchars($row['nama_pengirim']); ?></strong>
                        <small class="text-body-secondary ms-2">&lt;<?= htmlspecialchars($row['email_pengirim']); ?>&gt;</small>
                      </div>
                      <div class="d-flex align-items-center gap-2">
                        <small class="text-body-secondary font-monospace"><?= date('d M Y, H:i', strtotime($row['waktu_diterima'])); ?></small>
                        <a href="admin_kontak.php?hapus=<?= $row['id_pesan']; ?>" onclick="return confirm('Hapus pesan ini?');" class="btn btn-sm btn-outline-danger ms-2" title="Hapus Pesan">
                          <i class="fa-solid fa-trash"></i>
                        </a>
                      </div>
                    </div>
                    <p class="mb-0 text-light bg-dark p-3 rounded border border-secondary border-opacity-25">
                      <?= nl2br(htmlspecialchars($row['isi_pesan'])); ?>
                    </p>
                  </div>
                </div>
              <?php endwhile; ?>
            </div>
          <?php else: ?>
            <p class="text-body-secondary italic text-center py-4 mb-0">Belum ada pesan masuk dari pengunjung web.</p>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </div>
</main>

<?php include 'admin_footer.php'; ?>