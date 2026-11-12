<?php
include 'admin_header.php';

$success = '';
$error = '';

// TAMBAH MEDIA GALERI
if (isset($_POST['tambah_galeri'])) {
    $judul       = mysqli_real_escape_string($koneksi, trim($_POST['judul']));
    $jenis_media = mysqli_real_escape_string($koneksi, $_POST['jenis_media']);
    $deskripsi   = mysqli_real_escape_string($koneksi, trim($_POST['deskripsi']));
    $url_media   = '';
    $thumbnail   = '';

    // 1. PROSES UPLOAD FILE LOKAL (JIKA MEMILIH FILE)
    if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['file_upload']['tmp_name'];
        $file_name = $_FILES['file_upload']['name'];
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Tentukan folder penyimpanan berdasarkan jenis media
        $target_dir = ($jenis_media === 'video') ? 'Video/' : 'Images/';
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $nama_file_baru = 'media_' . time() . '_' . rand(100, 999) . '.' . $file_ext;
        $target_file    = $target_dir . $nama_file_baru;

        // Validasi ekstensi yang diizinkan
        $allowed_ext = ($jenis_media === 'video') 
            ? ['mp4', 'mkv', 'webm', 'mov'] 
            : ['jpg', 'jpeg', 'png', 'gif', 'webp', 'jfif'];

        if (!in_array($file_ext, $allowed_ext)) {
            $error = "Format file tidak didukung! Ekstensi yang diizinkan: " . implode(', ', $allowed_ext);
        } elseif (move_uploaded_file($file_tmp, $target_file)) {
            $url_media = $target_file;
        } else {
            $error = "Gagal mengunggah file ke server.";
        }
    } 
    // 2. ATAU GUNAKAN URL DARI INTERNET
    elseif (!empty($_POST['url_media'])) {
        $url_media = mysqli_real_escape_string($koneksi, trim($_POST['url_media']));
    } else {
        $error = "Silakan pilih file untuk diunggah ATAU masukkan URL media!";
    }

    // PROSES UPLOAD THUMBNAIL (OPSIONAL UNTUK VIDEO)
    if (isset($_FILES['thumb_upload']) && $_FILES['thumb_upload']['error'] === UPLOAD_ERR_OK) {
        $t_tmp  = $_FILES['thumb_upload']['tmp_name'];
        $t_name = $_FILES['thumb_upload']['name'];
        $t_ext  = strtolower(pathinfo($t_name, PATHINFO_EXTENSION));
        $t_file = 'Images/thumb_' . time() . '_' . rand(100, 999) . '.' . $t_ext;
        if (move_uploaded_file($t_tmp, $t_file)) {
            $thumbnail = $t_file;
        }
    } elseif (!empty($_POST['thumbnail_url'])) {
        $thumbnail = mysqli_real_escape_string($koneksi, trim($_POST['thumbnail_url']));
    }

    if (empty($error) && !empty($url_media)) {
        $query = "INSERT INTO galeri (judul, url_media, thumbnail_url, jenis_media, deskripsi) 
                  VALUES ('$judul', '$url_media', " . ($thumbnail ? "'$thumbnail'" : "NULL") . ", '$jenis_media', '$deskripsi')";
        if (mysqli_query($koneksi, $query)) {
            $success = "Media galeri berhasil ditambahkan!";
        } else {
            $error = "Gagal menambah galeri: " . mysqli_error($koneksi);
        }
    }
}

// EDIT MEDIA GALERI
if (isset($_POST['edit_galeri'])) {
    $id_galeri   = (int)$_POST['id_galeri'];
    $judul       = mysqli_real_escape_string($koneksi, trim($_POST['judul']));
    $jenis_media = mysqli_real_escape_string($koneksi, $_POST['jenis_media']);
    $deskripsi   = mysqli_real_escape_string($koneksi, trim($_POST['deskripsi']));
    $url_media   = mysqli_real_escape_string($koneksi, trim($_POST['url_media']));

    // Cek jika ada upload file baru saat edit
    if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['file_upload']['tmp_name'];
        $file_name = $_FILES['file_upload']['name'];
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $target_dir = ($jenis_media === 'video') ? 'Video/' : 'Images/';
        $nama_file_baru = 'media_' . time() . '_' . rand(100, 999) . '.' . $file_ext;
        $target_file = $target_dir . $nama_file_baru;

        if (move_uploaded_file($file_tmp, $target_file)) {
            $url_media = $target_file;
        }
    }

    $query = "UPDATE galeri SET judul='$judul', jenis_media='$jenis_media', url_media='$url_media', deskripsi='$deskripsi' 
              WHERE id_galeri=$id_galeri";
    if (mysqli_query($koneksi, $query)) {
        $success = "Data galeri berhasil diperbarui!";
    } else {
        $error = "Gagal memperbarui galeri: " . mysqli_error($koneksi);
    }
}

// HAPUS MEDIA GALERI
if (isset($_GET['hapus'])) {
    $id_galeri = (int)$_GET['hapus'];
    // Ambil path file untuk dibersihkan jika lokal
    $q_cek = mysqli_query($koneksi, "SELECT url_media FROM galeri WHERE id_galeri = $id_galeri");
    if ($d_cek = mysqli_fetch_assoc($q_cek)) {
        if (file_exists($d_cek['url_media']) && !str_starts_with($d_cek['url_media'], 'http')) {
            @unlink($d_cek['url_media']);
        }
    }

    if (mysqli_query($koneksi, "DELETE FROM galeri WHERE id_galeri = $id_galeri")) {
        $success = "Galeri berhasil dihapus!";
    } else {
        $error = "Gagal menghapus galeri: " . mysqli_error($koneksi);
    }
}

// FORM EDIT CHECK
$galeri_edit = null;
if (isset($_GET['edit'])) {
    $id_edit = (int)$_GET['edit'];
    $res_edit = mysqli_query($koneksi, "SELECT * FROM galeri WHERE id_galeri = $id_edit");
    if ($res_edit && mysqli_num_rows($res_edit) === 1) {
        $galeri_edit = mysqli_fetch_assoc($res_edit);
    }
}

$query_galeri = mysqli_query($koneksi, "SELECT * FROM galeri ORDER BY id_galeri DESC");
?>

<main class="app-main p-4">
  <div class="app-content-header mb-4">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h3 class="mb-0"><i class="fa-solid fa-image me-2 text-warning"></i>Kelola Galeri Media (Foto & Video)</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="admin.php">Admin</a></li>
            <li class="breadcrumb-item active">Galeri Media</li>
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
        <!-- FORM TAMBAH / EDIT -->
        <div class="col-lg-5 mb-4">
          <div class="card card-outline card-warning">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fa-solid <?= $galeri_edit ? 'fa-pen-to-square' : 'fa-plus'; ?> me-1"></i> 
                <?= $galeri_edit ? 'Edit Data Media' : 'Tambah Media Baru'; ?>
              </h3>
            </div>
            <form method="POST" action="admin_galeri.php" enctype="multipart/form-data">
              <?php if($galeri_edit): ?>
                <input type="hidden" name="id_galeri" value="<?= $galeri_edit['id_galeri']; ?>">
              <?php endif; ?>

              <div class="card-body">
                <div class="mb-3">
                  <label class="form-label font-weight-bold">Judul Media</label>
                  <input type="text" name="judul" required 
                         value="<?= htmlspecialchars($galeri_edit['judul'] ?? ''); ?>" 
                         placeholder="Contoh: Aksi Panggung di Jakarta" class="form-control">
                </div>

                <div class="mb-3">
                  <label class="form-label font-weight-bold">Jenis Media</label>
                  <select name="jenis_media" class="form-select" id="jenis_media_select">
                    <option value="foto" <?= ($galeri_edit['jenis_media'] ?? '') === 'foto' ? 'selected' : ''; ?>>Foto / Gambar</option>
                    <option value="video" <?= ($galeri_edit['jenis_media'] ?? '') === 'video' ? 'selected' : ''; ?>>Video</option>
                  </select>
                </div>

                <!-- PILIHAN 1: UPLOAD DARI KOMPUTER -->
                <div class="mb-3 p-3 rounded" style="background:#191b22; border:1px solid #2e323f;">
                  <label class="form-label text-warning font-weight-bold mb-1">
                    <i class="fa-solid fa-upload me-1"></i> Opsi A: Upload dari Komputer (Lokal)
                  </label>
                  <input type="file" name="file_upload" class="form-control mb-1">
                  <small class="text-body-secondary d-block">Pilih file gambar (.jpg, .png, .webp) atau video (.mp4) dari perangkat Anda.</small>
                </div>

                <!-- PILIHAN 2: URL INTERNET -->
                <div class="mb-3 p-3 rounded" style="background:#191b22; border:1px solid #2e323f;">
                  <label class="form-label text-warning font-weight-bold mb-1">
                    <i class="fa-solid fa-link me-1"></i> Opsi B: Atau Masukkan URL / Link Web
                  </label>
                  <input type="text" name="url_media" 
                         value="<?= htmlspecialchars($galeri_edit['url_media'] ?? ''); ?>" 
                         placeholder="Contoh: https://youtube.com/watch?v=... atau link TikTok" class="form-control mb-1">
                  <small class="text-body-secondary d-block">
                    • <strong>Foto:</strong> URL gambar langsung (.jpg, .png, .webp).<br>
                    • <strong>Video:</strong> Link YouTube, YouTube Shorts, atau TikTok.
                  </small>
                </div>

                <div class="mb-3">
                  <label class="form-label font-weight-bold">Deskripsi Ringkas</label>
                  <textarea name="deskripsi" rows="2" class="form-control" placeholder="Keterangan singkat momen panggung..."><?= htmlspecialchars($galeri_edit['deskripsi'] ?? ''); ?></textarea>
                </div>
              </div>

              <div class="card-footer d-flex gap-2">
                <?php if($galeri_edit): ?>
                  <button type="submit" name="edit_galeri" class="btn btn-warning text-dark font-weight-bold flex-grow-1">Simpan Perubahan</button>
                  <a href="admin_galeri.php" class="btn btn-secondary">Batal</a>
                <?php else: ?>
                  <button type="submit" name="tambah_galeri" class="btn btn-warning text-dark font-weight-bold w-100">Tambah ke Galeri</button>
                <?php endif; ?>
              </div>
            </form>
          </div>
        </div>

        <!-- TABEL DATA GALERI -->
        <div class="col-lg-7">
          <div class="card card-outline card-warning">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h3 class="card-title"><i class="fa-solid fa-photo-film me-2"></i> Galeri Dokumentasi SLR Band</h3>
              <span class="badge text-bg-warning ms-auto">Total: <?= mysqli_num_rows($query_galeri); ?> Media</span>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">
                  <thead>
                    <tr>
                      <th style="width: 70px;">Preview</th>
                      <th>Judul & Deskripsi</th>
                      <th>Jenis</th>
                      <th class="text-center" style="width: 100px;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (mysqli_num_rows($query_galeri) > 0): ?>
                      <?php while ($row = mysqli_fetch_assoc($query_galeri)): ?>
                        <tr>
                          <td>
                            <?php if ($row['jenis_media'] === 'foto'): ?>
                              <img src="<?= htmlspecialchars($row['url_media']); ?>" 
                                   alt="Preview" class="rounded object-fit-cover" style="width:60px; height:60px;" 
                                   onerror="this.src='https://via.placeholder.com/60?text=Error';">
                            <?php else: ?>
                              <div class="rounded bg-black d-flex align-items-center justify-content-center text-warning" style="width:60px; height:60px;">
                                <i class="fa-solid fa-circle-play fa-xl"></i>
                              </div>
                            <?php endif; ?>
                          </td>
                          <td>
                            <strong class="text-warning"><?= htmlspecialchars($row['judul']); ?></strong><br>
                            <small class="text-body-secondary"><?= htmlspecialchars($row['deskripsi'] ?? ''); ?></small><br>
                            <small class="text-body-tertiary font-monospace" style="font-size:11px; word-break:break-all;">
                              <?= htmlspecialchars($row['url_media']); ?>
                            </small>
                          </td>
                          <td>
                            <span class="badge <?= $row['jenis_media'] === 'foto' ? 'text-bg-info' : 'text-bg-danger' ?> text-uppercase">
                              <?= $row['jenis_media']; ?>
                            </span>
                          </td>
                          <td class="text-center">
                            <div class="btn-group btn-group-sm">
                              <a href="admin_galeri.php?edit=<?= $row['id_galeri']; ?>" class="btn btn-warning" title="Edit Data">
                                <i class="fa-solid fa-pen"></i>
                              </a>
                              <a href="admin_galeri.php?hapus=<?= $row['id_galeri']; ?>" 
                                 onclick="return confirm('Yakin ingin menghapus media ini?');" 
                                 class="btn btn-danger" title="Hapus">
                                <i class="fa-solid fa-trash"></i>
                              </a>
                            </div>
                          </td>
                        </tr>
                      <?php endwhile; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="4" class="text-center text-body-secondary py-4">Belum ada media di galeri.</td>
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
