<?php
include 'admin_header.php';

$success = '';
$error = '';

// TAMBAH MERCH
if (isset($_POST['tambah_merch'])) {
    $nama_produk   = mysqli_real_escape_string($koneksi, trim($_POST['nama_produk']));
    $deskripsi     = mysqli_real_escape_string($koneksi, trim($_POST['deskripsi']));
    $harga         = (float)$_POST['harga'];
    $stok          = (int)$_POST['stok'];
    $gambar_produk = '';

    // Cek upload file lokal
    if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['file_upload']['tmp_name'];
        $file_name = $_FILES['file_upload']['name'];
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'jfif'];
        if (in_array($file_ext, $allowed_ext)) {
            $nama_baru = 'merch_' . time() . '_' . rand(100, 999) . '.' . $file_ext;
            $tujuan = 'Images/' . $nama_baru;
            if (move_uploaded_file($file_tmp, $tujuan)) {
                $gambar_produk = $tujuan;
            } else {
                $error = "Gagal mengunggah gambar produk.";
            }
        } else {
            $error = "Format file tidak didukung! Gunakan gambar .jpg, .png, atau .webp.";
        }
    } elseif (!empty($_POST['gambar_produk'])) {
        $gambar_produk = mysqli_real_escape_string($koneksi, trim($_POST['gambar_produk']));
    } else {
        $gambar_produk = 'Images/baju.jpeg';
    }

    if (empty($error)) {
        $query = "INSERT INTO produk_merch (nama_produk, deskripsi, harga, stok, gambar_produk) 
                  VALUES ('$nama_produk', '$deskripsi', '$harga', '$stok', '$gambar_produk')";
        if (mysqli_query($koneksi, $query)) {
            $success = "Produk merch berhasil ditambahkan!";
        } else {
            $error = "Gagal menambah produk: " . mysqli_error($koneksi);
        }
    }
}

// EDIT MERCH
if (isset($_POST['edit_merch'])) {
    $id_produk     = (int)$_POST['id_produk'];
    $nama_produk   = mysqli_real_escape_string($koneksi, trim($_POST['nama_produk']));
    $deskripsi     = mysqli_real_escape_string($koneksi, trim($_POST['deskripsi']));
    $harga         = (float)$_POST['harga'];
    $stok          = (int)$_POST['stok'];
    $gambar_produk = mysqli_real_escape_string($koneksi, trim($_POST['gambar_produk']));

    // Cek jika ada upload file baru saat edit
    if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['file_upload']['tmp_name'];
        $file_name = $_FILES['file_upload']['name'];
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'jfif'];

        if (in_array($file_ext, $allowed_ext)) {
            $nama_baru = 'merch_' . time() . '_' . rand(100, 999) . '.' . $file_ext;
            $tujuan = 'Images/' . $nama_baru;
            if (move_uploaded_file($file_tmp, $tujuan)) {
                $gambar_produk = $tujuan;
            }
        }
    }

    $query = "UPDATE produk_merch SET nama_produk='$nama_produk', deskripsi='$deskripsi', 
              harga='$harga', stok='$stok', gambar_produk='$gambar_produk' WHERE id_produk=$id_produk";
    if (mysqli_query($koneksi, $query)) {
        $success = "Produk merch berhasil diperbarui!";
    } else {
        $error = "Gagal memperbarui produk: " . mysqli_error($koneksi);
    }
}

// HAPUS MERCH
if (isset($_GET['hapus'])) {
    $id_produk = (int)$_GET['hapus'];
    if (mysqli_query($koneksi, "DELETE FROM produk_merch WHERE id_produk=$id_produk")) {
        $success = "Produk merch berhasil dihapus!";
    } else {
        $error = "Gagal menghapus produk: " . mysqli_error($koneksi);
    }
}

$merch_edit = null;
if (isset($_GET['edit'])) {
    $id_edit = (int)$_GET['edit'];
    $res_edit = mysqli_query($koneksi, "SELECT * FROM produk_merch WHERE id_produk=$id_edit");
    if ($res_edit && mysqli_num_rows($res_edit) === 1) {
        $merch_edit = mysqli_fetch_assoc($res_edit);
    }
}

$query_merch = mysqli_query($koneksi, "SELECT * FROM produk_merch ORDER BY id_produk ASC");
?>

<main class="app-main p-4">
  <div class="app-content-header mb-4">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h3 class="mb-0"><i class="fa-solid fa-shirt me-2 text-warning"></i>Kelola Merchandise Band</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="admin.php">Admin</a></li>
            <li class="breadcrumb-item active">Merchandise</li>
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
        <div class="col-lg-5 mb-4">
          <div class="card card-outline card-warning">
            <div class="card-header">
              <h3 class="card-title"><?= $merch_edit ? '<i class="fa-solid fa-pen-to-square me-1"></i> Edit Merch' : '<i class="fa-solid fa-plus me-1"></i> Tambah Merch Baru' ?></h3>
            </div>
            <form method="POST" action="admin_merch.php" enctype="multipart/form-data">
              <div class="card-body">
                <?php if($merch_edit): ?>
                  <input type="hidden" name="id_produk" value="<?= $merch_edit['id_produk']; ?>">
                <?php endif; ?>

                <div class="mb-3">
                  <label class="form-label font-weight-bold">Nama Produk</label>
                  <input type="text" name="nama_produk" value="<?= htmlspecialchars($merch_edit['nama_produk'] ?? ''); ?>" required placeholder="Contoh: Kaos Official SLR" class="form-control">
                </div>

                <div class="mb-3">
                  <label class="form-label font-weight-bold">Deskripsi</label>
                  <textarea name="deskripsi" rows="2" class="form-control" placeholder="Bahan cotton combed 30s sablon plastisol..."><?= htmlspecialchars($merch_edit['deskripsi'] ?? ''); ?></textarea>
                </div>

                <div class="row">
                  <div class="col-6 mb-3">
                    <label class="form-label font-weight-bold">Harga (Rp)</label>
                    <input type="number" name="harga" value="<?= $merch_edit['harga'] ?? '100000'; ?>" required class="form-control">
                  </div>
                  <div class="col-6 mb-3">
                    <label class="form-label font-weight-bold">Stok</label>
                    <input type="number" name="stok" value="<?= $merch_edit['stok'] ?? '50'; ?>" required class="form-control">
                  </div>
                </div>

                <!-- PILIHAN UPLOAD GAMBAR DARI LOKAL -->
                <div class="mb-3 p-3 rounded" style="background:#191b22; border:1px solid #2e323f;">
                  <label class="form-label text-warning font-weight-bold mb-1">
                    <i class="fa-solid fa-upload me-1"></i> Upload Gambar dari Komputer (Lokal)
                  </label>
                  <input type="file" name="file_upload" class="form-control mb-1" accept="image/*">
                  <small class="text-body-secondary d-block">Pilih foto produk (.jpg, .png, .webp) dari laptop/PC Anda.</small>
                </div>

                <!-- PILIHAN DARI URL WEB -->
                <div class="mb-3 p-3 rounded" style="background:#191b22; border:1px solid #2e323f;">
                  <label class="form-label text-warning font-weight-bold mb-1">
                    <i class="fa-solid fa-link me-1"></i> Atau Masukkan URL / Path Gambar
                  </label>
                  <input type="text" name="gambar_produk" value="<?= htmlspecialchars($merch_edit['gambar_produk'] ?? ''); ?>" placeholder="Images/baju.jpeg atau https://..." class="form-control mb-1">
                  <small class="text-body-secondary d-block">Bisa gunakan link gambar langsung dari internet atau path lokal.</small>
                </div>
              </div>
              <div class="card-footer d-flex gap-2">
                <?php if($merch_edit): ?>
                  <button type="submit" name="edit_merch" class="btn btn-warning text-dark font-weight-bold flex-fill">Simpan Perubahan</button>
                  <a href="admin_merch.php" class="btn btn-secondary">Batal</a>
                <?php else: ?>
                  <button type="submit" name="tambah_merch" class="btn btn-warning text-dark font-weight-bold w-100">Tambah Merch</button>
                <?php endif; ?>
              </div>
            </form>
          </div>
        </div>

        <!-- TABEL MERCH -->
        <div class="col-lg-7">
          <div class="card card-outline card-warning">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h3 class="card-title"><i class="fa-solid fa-boxes-stacked me-2"></i> Daftar Produk Merchandise</h3>
              <span class="badge text-bg-warning ms-auto">Total: <?= mysqli_num_rows($query_merch); ?> Produk</span>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">
                  <thead>
                    <tr>
                      <th style="width: 70px;">Foto</th>
                      <th>Nama & Deskripsi</th>
                      <th>Harga</th>
                      <th>Stok</th>
                      <th class="text-center" style="width: 90px;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (mysqli_num_rows($query_merch) > 0): ?>
                      <?php while ($row = mysqli_fetch_assoc($query_merch)): ?>
                        <tr>
                          <td>
                            <img src="<?= htmlspecialchars($row['gambar_produk']); ?>" 
                                 class="rounded object-fit-cover" style="width:60px; height:60px;" 
                                 alt="Merch" onerror="this.src='https://via.placeholder.com/60?text=No+Img';">
                          </td>
                          <td>
                            <strong class="text-warning"><?= htmlspecialchars($row['nama_produk']); ?></strong><br>
                            <small class="text-body-secondary"><?= htmlspecialchars($row['deskripsi']); ?></small>
                          </td>
                          <td class="font-monospace fw-bold text-nowrap">Rp<?= number_format($row['harga'], 0, ',', '.'); ?></td>
                          <td>
                            <span class="badge <?= $row['stok'] > 10 ? 'text-bg-success' : 'text-bg-danger'; ?>">
                              <?= $row['stok']; ?> Pcs
                            </span>
                          </td>
                          <td class="text-center">
                            <div class="btn-group btn-group-sm">
                              <a href="admin_merch.php?edit=<?= $row['id_produk']; ?>" class="btn btn-warning" title="Edit"><i class="fa-solid fa-pen"></i></a>
                              <a href="admin_merch.php?hapus=<?= $row['id_produk']; ?>" onclick="return confirm('Hapus produk ini?');" class="btn btn-danger" title="Hapus"><i class="fa-solid fa-trash"></i></a>
                            </div>
                          </td>
                        </tr>
                      <?php endwhile; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="5" class="text-center text-body-secondary py-4">Belum ada produk merchandise.</td>
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
