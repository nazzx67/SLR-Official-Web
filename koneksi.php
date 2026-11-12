<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_slr"; // Sesuai nama database di rancangan

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>