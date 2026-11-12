<?php
include 'admin_header.php';

$success = '';
$error = '';

// TAMBAH LAGU
if (isset($_POST['tambah_lagu'])) {
    $judul_lagu  = mysqli_real_escape_string($koneksi, trim($_POST['judul_lagu']));
    $url_youtube = mysqli_real_escape_string($koneksi, trim($_POST['url_youtube']));

    if (!empty($judul_lagu)) {
        $query = "INSERT INTO lagu (judul_lagu, url_youtube) VALUES ('$judul_lagu', '$url_youtube')";
        if (mysqli_query($koneksi, $query)) {
            $success = "Lagu berhasil ditambahkan!";
        } else {
            $error = "Gagal menambah lagu: " . mysqli_error($koneksi);
        }
    } else {
        $error = "Judul lagu tidak boleh kosong!";
    }
}

// EDIT LAGU
if (isset($_POST['edit_lagu'])) {
    $id_lagu     = mysqli_real_escape_string($koneksi, $_POST['id_lagu']);
    $judul_lagu  = mysqli_real_escape_string($koneksi, trim($_POST['judul_lagu']));
    $url_youtube = mysqli_real_escape_string($koneksi, trim($_POST['url_youtube']));

    if (!empty($judul_lagu)) {
        $query = "UPDATE lagu SET judul_lagu='$judul_lagu', url_youtube='$url_youtube' WHERE id_lagu='$id_lagu'";
        if (mysqli_query($koneksi, $query)) {
            $success = "Data lagu berhasil diperbarui!";
        } else {
            $error = "Gagal memperbarui lagu: " . mysqli_error($koneksi);
        }
    } else {
        $error = "Judul lagu tidak boleh kosong!";
    }
}

// HAPUS LAGU
if (isset($_GET['hapus'])) {
    $id_lagu = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    if (mysqli_query($koneksi, "DELETE FROM lagu WHERE id_lagu='$id_lagu'")) {
        $success = "Lagu berhasil dihapus!";
    } else {
        $error = "Gagal menghapus lagu: " . mysqli_error($koneksi);
    }
}

$lagu_edit = null;
if (isset($_GET['edit'])) {
    $id_edit = mysqli_real_escape_string($koneksi, $_GET['edit']);
    $res_edit = mysqli_query($koneksi, "SELECT * FROM lagu WHERE id_lagu='$id_edit'");
    if (mysqli_num_rows($res_edit) === 1) {
        $lagu_edit = mysqli_fetch_assoc($res_edit);
    }
}

$query_lagu = mysqli_query($koneksi, "SELECT * FROM lagu ORDER BY id_lagu ASC");
?>

<main class="app-main p-4">
  <div class="app-content-header mb-4">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h3 class="mb-0"><i class="fa-solid fa-music me-2 text-warning"></i>Kelola Katalog Lagu</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="admin.php">Admin</a></li>
            <li class="breadcrumb-item active">Kelola Lagu</li>
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
              <h3 class="card-title"><?= $lagu_edit ? '<i class="fa-solid fa-pen-to-square me-1"></i> Edit Lagu' : '<i class="fa-solid fa-plus me-1"></i> Tambah Lagu Baru' ?></h3>
            </div>
            <form method="POST" action="admin_lagu.php">
              <div class="card-body">
                <?php if($lagu_edit): ?>
                  <input type="hidden" name="id_lagu" value="<?= $lagu_edit['id_lagu']; ?>">
                <?php endif; ?>

                <div class="mb-3">
                  <label class="form-label font-weight-bold">Judul Lagu</label>
                  <input type="text" name="judul_lagu" value="<?= htmlspecialchars($lagu_edit['judul_lagu'] ?? ''); ?>" required placeholder="Contoh: Pulpen Ilang" class="form-control">
                </div>

                <div class="mb-3">
                  <label class="form-label font-weight-bold">URL Video YouTube</label>
                  <input type="url" name="url_youtube" value="<?= htmlspecialchars($lagu_edit['url_youtube'] ?? ''); ?>" placeholder="https://youtu.be/..." class="form-control">
                </div>
              </div>
              <div class="card-footer d-flex gap-2">
                <?php if($lagu_edit): ?>
                  <button type="submit" name="edit_lagu" class="btn btn-warning text-dark font-weight-bold flex-fill">Simpan</button>
                  <a href="admin_lagu.php" class="btn btn-secondary">Batal</a>
                <?php else: ?>
                  <button type="submit" name="tambah_lagu" class="btn btn-warning text-dark font-weight-bold w-100">Tambah Lagu</button>
                <?php endif; ?>
              </div>
            </form>
          </div>
        </div>

        <!-- TABEL -->
        <div class="col-lg-8">
          <div class="card card-outline card-warning">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h3 class="card-title"><i class="fa-solid fa-list me-2"></i> Daftar Lagu Band</h3>
              <span class="badge text-bg-warning ms-auto">Total: <?= mysqli_num_rows($query_lagu); ?> Lagu</span>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                  <thead>
                    <tr>
                      <th style="width: 60px;" class="text-center">ID</th>
                      <th>Judul Lagu</th>
                      <th>YouTube</th>
                      <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (mysqli_num_rows($query_lagu) > 0): ?>
                      <?php while ($row = mysqli_fetch_assoc($query_lagu)): ?>
                        <tr>
                          <td class="text-center font-monospace"><?= $row['id_lagu']; ?></td>
                          <td class="fw-bold text-warning"><?= htmlspecialchars($row['judul_lagu']); ?></td>
                          <td>
                            <?php if($row['url_youtube']): ?>
                              <a href="<?= htmlspecialchars($row['url_youtube']); ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                <i class="fa-brands fa-youtube me-1"></i> Tonton
                              </a>
                            <?php else: ?>
                              <span class="text-body-secondary fst-italic small">Tidak ada link</span>
                            <?php endif; ?>
                          </td>
                          <td class="text-center">
                            <a href="admin_lagu.php?edit=<?= $row['id_lagu']; ?>" class="btn btn-sm btn-warning me-1" title="Edit"><i class="fa-solid fa-pen"></i></a>
                            <a href="admin_lagu.php?hapus=<?= $row['id_lagu']; ?>" onclick="return confirm('Yakin hapus lagu ini?');" class="btn btn-sm btn-danger" title="Hapus"><i class="fa-solid fa-trash"></i></a>
                          </td>
                        </tr>
                      <?php endwhile; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="4" class="text-center text-body-secondary py-3">Belum ada lagu tersimpan.</td>
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