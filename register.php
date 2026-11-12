<?php
include 'koneksi.php';
$msg = '';

if (isset($_POST['register'])) {
    $nama          = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email         = mysqli_real_escape_string($koneksi, $_POST['email']);
    $nomor_telepon = mysqli_real_escape_string($koneksi, $_POST['nomor_telepon']);
    $alamat        = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $kata_sandi    = password_hash($_POST['kata_sandi'], PASSWORD_DEFAULT);

    $check = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $msg = "Email sudah terdaftar!";
    } else {
        $query = "INSERT INTO pengguna (email, kata_sandi, Nama, Nomor_telepon, alamat, foto_profil) 
                  VALUES ('$email', '$kata_sandi', '$nama', '$nomor_telepon', '$alamat', 'default.jpg')";
        if (mysqli_query($koneksi, $query)) {
            header("Location: login.php");
            exit;
        } else {
            $msg = "Gagal membuat akun: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Registrasi - SLR Band</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-black text-white flex items-center justify-center min-h-screen py-10 px-4">

  <!-- KOTAK FORM REGISTRASI -->
  <div class="w-full max-w-md bg-[#111] p-8 border border-yellow-400 rounded-lg relative shadow-2xl">
    
    <!-- TOMBOL CLOSE (X) KEMBALI KE BERANDA -->
    <a href="index.php" class="absolute top-4 right-4 text-gray-400 hover:text-yellow-400 text-3xl font-bold leading-none transition" title="Kembali ke Beranda">
      &times;
    </a>

    <h2 class="text-3xl font-extrabold text-yellow-400 text-center mb-6">REGISTRASI FANS</h2>
    
    <?php if($msg): ?>
      <div class="bg-red-500/20 border border-red-500 text-red-400 p-3 rounded mb-4 text-center text-sm font-bold"><?= $msg ?></div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block mb-1 text-xs font-bold uppercase text-gray-400">Nama Lengkap</label>
        <input type="text" name="nama" required placeholder="Nama lengkap Anda" class="w-full bg-black border border-yellow-400 p-3 text-white rounded">
      </div>
      <div>
        <label class="block mb-1 text-xs font-bold uppercase text-gray-400">Email</label>
        <input type="email" name="email" required placeholder="email@contoh.com" class="w-full bg-black border border-yellow-400 p-3 text-white rounded">
      </div>
      <div>
        <label class="block mb-1 text-xs font-bold uppercase text-gray-400">Nomor Telepon / WA</label>
        <input type="tel" name="nomor_telepon" required placeholder="08xxxxxxxxxx" class="w-full bg-black border border-yellow-400 p-3 text-white rounded">
      </div>
      <div>
        <label class="block mb-1 text-xs font-bold uppercase text-gray-400">Alamat Pengiriman</label>
        <textarea name="alamat" required placeholder="Alamat lengkap rumah/tempat tinggal" class="w-full bg-black border border-yellow-400 p-3 text-white rounded" rows="2"></textarea>
      </div>
      <div>
        <label class="block mb-1 text-xs font-bold uppercase text-gray-400">Kata Sandi</label>
        <input type="password" name="kata_sandi" required placeholder="••••••••" class="w-full bg-black border border-yellow-400 p-3 text-white rounded">
      </div>
      <button type="submit" name="register" class="w-full bg-yellow-400 text-black py-3 font-extrabold hover:bg-yellow-300 transition rounded">DAFTAR AKUN</button>
    </form>
    
    <div class="mt-6 text-center text-sm text-gray-400 border-t border-gray-800 pt-4">
      Sudah punya akun? <a href="login.php" class="text-yellow-400 font-bold underline hover:text-yellow-300">Login</a>
    </div>

  </div>

</body>
</html>