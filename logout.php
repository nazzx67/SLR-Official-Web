<?php
// 1. Jalankan session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Kosongkan semua variabel di array $_SESSION
$_SESSION = array();

// 3. Hapus cookie session jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Hancurkan session secara permanen
session_destroy();

// 5. Pengalihan halaman ke index.php
if (!headers_sent()) {
    header("Location: index.php");
    exit;
} else {
    // Pengalihan cadangan jika header terlanjur terkirim
    echo "<script>window.location.href='index.php';</script>";
    exit;
}
?>