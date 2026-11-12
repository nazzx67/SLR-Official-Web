<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama  = isset($_POST['name']) ? mysqli_real_escape_string($koneksi, trim($_POST['name'])) : '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($koneksi, trim($_POST['email'])) : '';
    $pesan = isset($_POST['message']) ? mysqli_real_escape_string($koneksi, trim($_POST['message'])) : '';
    $id_pengguna = isset($_SESSION['id_pengguna']) ? (int)$_SESSION['id_pengguna'] : "NULL";

    if (empty($nama) || empty($email) || empty($pesan)) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Semua kolom formulir wajib diisi!']);
        exit;
    }

    $query = "INSERT INTO pesan_kontak (id_pengguna, nama_pengirim, email_pengirim, isi_pesan, waktu_diterima) 
              VALUES ($id_pengguna, '$nama', '$email', '$pesan', NOW())";

    if (mysqli_query($koneksi, $query)) {
        echo json_encode(['ok' => true, 'message' => 'Pesan berhasil terkirim ke tim SLR Band!']);
    } else {
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => 'Gagal menyimpan pesan: ' . mysqli_error($koneksi)]);
    }
} else {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Metode request tidak diizinkan.']);
}
