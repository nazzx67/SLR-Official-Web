<?php
session_start();
include 'koneksi.php';

$error = '';

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    // 1. Cek di tabel admin
    $query_admin = mysqli_query($koneksi, "SELECT * FROM admin WHERE email='$email' OR nama_pengguna='$email'");
    if (mysqli_num_rows($query_admin) === 1) {
        $admin = mysqli_fetch_assoc($query_admin);
        if (password_verify($password, $admin['kata_sandi']) || $password === $admin['kata_sandi']) {
            $_SESSION['id_admin'] = $admin['id_admin'];
            $_SESSION['nama_pengguna'] = $admin['nama_pengguna'];
            $_SESSION['role'] = 'admin';
            header("Location: admin.php");
            exit;
        }
    }

    // 2. Cek di tabel pengguna
    $query_user = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE email='$email'");
    if (mysqli_num_rows($query_user) === 1) {
        $user = mysqli_fetch_assoc($query_user);
        if (password_verify($password, $user['kata_sandi']) || $password === $user['kata_sandi']) {
            $_SESSION['id_pengguna'] = $user['id_pengguna'];
            $_SESSION['nama_pengguna'] = $user['Nama'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = 'user';
            header("Location: index.php");
            exit;
        }
    }

    $error = "Email/Username atau Kata Sandi salah!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - SLR Band</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-black text-white flex items-center justify-center min-h-screen px-4">

  <!-- KOTAK FORM LOGIN -->
  <div class="w-full max-w-md bg-[#111] p-8 border border-yellow-400 rounded-lg relative shadow-2xl">
    
    <!-- TOMBOL CLOSE (X) KEMBALI KE BERANDA -->
    <a href="index.php" class="absolute top-4 right-4 text-gray-400 hover:text-yellow-400 text-3xl font-bold leading-none transition" title="Kembali ke Beranda">
      &times;
    </a>

    <h2 class="text-3xl font-extrabold text-yellow-400 text-center mb-6">LOGIN SLR</h2>
    
    <?php if($error): ?>
      <div class="bg-red-500/20 border border-red-500 text-red-400 p-3 rounded mb-4 text-center text-sm font-bold"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block mb-1 text-xs font-bold uppercase text-gray-400">Email / Username Admin</label>
        <input type="text" name="email" required placeholder="Email atau username" class="w-full bg-black border border-yellow-400 p-3 text-white rounded focus:outline-none focus:border-yellow-300">
      </div>
      <div>
        <label class="block mb-1 text-xs font-bold uppercase text-gray-400">Kata Sandi</label>
        <input type="password" name="password" required placeholder="••••••••" class="w-full bg-black border border-yellow-400 p-3 text-white rounded focus:outline-none focus:border-yellow-300">
      </div>
      <button type="submit" name="login" class="w-full bg-yellow-400 text-black py-3 font-extrabold hover:bg-yellow-300 transition rounded">LOGIN</button>
    </form>
    
    <div class="mt-6 text-center text-sm text-gray-400 border-t border-gray-800 pt-4">
      Belum punya akun? <a href="register.php" class="text-yellow-400 font-bold underline hover:text-yellow-300">Daftar di sini</a>
    </div>

  </div>

</body>
</html>