-- phpMyAdmin SQL Dump
-- Database: `db_slr`
-- Project Based Learning: SLR Official Band Website
-- Kelompok 3 - Praktikum Pemrograman Web
-- Anggota Tim: Nazriel Putra Febrinanda, Ahyas Ulumudin Wicaksono, Hilmi Andika

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- 1. TABEL ADMIN
-- --------------------------------------------------------
DROP TABLE IF EXISTS `detail_pesanan_merch`;
DROP TABLE IF EXISTS `pesanan_merch`;
DROP TABLE IF EXISTS `pesanan_tiket`;
DROP TABLE IF EXISTS `pesan_kontak`;
DROP TABLE IF EXISTS `produk_merch`;
DROP TABLE IF EXISTS `jadwal_tur`;
DROP TABLE IF EXISTS `lagu`;
DROP TABLE IF EXISTS `galeri`;
DROP TABLE IF EXISTS `pengguna`;
DROP TABLE IF EXISTS `admin`;

CREATE TABLE `admin` (
  `id_admin` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_pengguna` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `kata_sandi` varchar(255) NOT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `uq_admin_username` (`nama_pengguna`),
  UNIQUE KEY `uq_admin_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `admin` (`id_admin`, `nama_pengguna`, `email`, `kata_sandi`, `dibuat_pada`) VALUES
(1, 'admin', 'admin@slrband.com', '$2y$10$w82W3x9LhSdfEGyF9g9RweX5pM2h1E3lP2y7kI4kE0lQ7yN1o2Z2m', '2026-11-04 10:35:39');

-- --------------------------------------------------------
-- 2. TABEL PENGGUNA (FANS)
-- --------------------------------------------------------
CREATE TABLE `pengguna` (
  `id_pengguna` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `kata_sandi` varchar(255) NOT NULL,
  `Nama` varchar(100) NOT NULL,
  `Nomor_telepon` varchar(25) NOT NULL,
  `foto_profil` varchar(255) NOT NULL DEFAULT 'default.jpg',
  `alamat` text NOT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pengguna`),
  UNIQUE KEY `uq_pengguna_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pengguna` (`id_pengguna`, `email`, `kata_sandi`, `Nama`, `Nomor_telepon`, `foto_profil`, `alamat`, `dibuat_pada`) VALUES
(1, 'ahyas@gmail.com', '$2y$10$eE0m7aGgT.OqD6K8EwS/pefXzE5yP7XnF0M0Q4lR.p3L3Xn5a5P6e', 'Ahyas Ulumudin', '089797978686', 'default.jpg', 'Jl. Husein Hamzah Komp. Dwi Ratna Indah 3 C-23', '2026-11-05 07:27:59'),
(2, 'nazriel@gmail.com', '$2y$10$eE0m7aGgT.OqD6K8EwS/pefXzE5yP7XnF0M0Q4lR.p3L3Xn5a5P6e', 'Nazriel Putra', '081234567890', 'default.jpg', 'Jl. Reformasi No. 12, Pontianak', '2026-11-06 09:15:00'),
(3, 'hilmi@gmail.com', '$2y$10$eE0m7aGgT.OqD6K8EwS/pefXzE5yP7XnF0M0Q4lR.p3L3Xn5a5P6e', 'Hilmi Andika', '085298765432', 'default.jpg', 'Jl. Ahmad Yani No. 45, Pontianak', '2026-11-07 14:20:00');

-- --------------------------------------------------------
-- 3. TABEL KATALOG LAGU
-- --------------------------------------------------------
CREATE TABLE `lagu` (
  `id_lagu` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `judul_lagu` varchar(150) NOT NULL,
  `url_youtube` varchar(255) DEFAULT NULL,
  `file_audio` varchar(255) DEFAULT NULL,
  `durasi` varchar(10) DEFAULT '03:00',
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_lagu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lagu` (`id_lagu`, `judul_lagu`, `url_youtube`, `file_audio`, `durasi`) VALUES
(1, 'Maling', 'https://youtu.be/3vvkufiNSrQ', 'lagu1.mp3', '02:45'),
(2, 'Gak Nyaman', 'https://youtu.be/IHTSQVCLp0w', 'lagu2.mp3', '03:10'),
(3, 'Cantik Tapi Bau', 'https://youtu.be/_nnUozvLi9I', 'lagu3.mp3', '02:30'),
(4, 'Kebelet', 'https://youtu.be/A_1TywpTJng', 'lagu4.mp3', '02:15'),
(5, 'Toilet Sekolah', 'https://youtu.be/Imal2-1XwTI', 'lagu5.mp3', '03:05'),
(6, 'Mual', 'https://youtu.be/Wq9Aja-soFQ', 'lagu6.mp3', '02:50'),
(7, 'Ke Pasar Bareng Emak', 'https://youtu.be/ZPLg6KbPN5Q', 'lagu7.mp3', '03:20'),
(8, 'Libur Seminggu', 'https://youtu.be/37JHmJoy9vo', 'lagu8.mp3', '02:40'),
(9, 'Naber', 'https://youtu.be/lIisfY4agfA', 'lagu9.mp3', '02:25'),
(10, 'Gosok Gigi', 'https://youtu.be/3ClON0hsGhE', 'lagu10.mp3', '02:55'),
(11, 'Mandi Pagi', 'https://youtu.be/LZhvoVBSDcw', 'lagu11.mp3', '03:12'),
(12, 'Lebaran, So Far Away', 'https://youtu.be/Bw1MdVS0z9Q', 'lagu12.mp3', '03:45'),
(13, 'Pulpen Ilang', 'https://youtu.be/1VaMpBVTBCY', 'lagu13.mp3', '02:38');

-- --------------------------------------------------------
-- 4. TABEL GALERI (FOTO & VIDEO)
-- --------------------------------------------------------
CREATE TABLE `galeri` (
  `id_galeri` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `judul` varchar(150) NOT NULL,
  `url_media` text NOT NULL,
  `thumbnail_url` text DEFAULT NULL,
  `jenis_media` enum('foto','video') NOT NULL,
  `deskripsi` text,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_galeri`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `galeri` (`id_galeri`, `judul`, `url_media`, `thumbnail_url`, `jenis_media`, `deskripsi`) VALUES
(1, 'Live Konser Stage 1', 'Video/video1.mp4', 'Images/thumb1.jpeg', 'video', 'Keseruan aksi panggung SLR di panggung utama.'),
(2, 'Tour Video Dokumentasi', 'Video/video3.mp4', 'Images/thumb3.jpeg', 'video', 'Dokumentasi perjalanan tur konser SLR.'),
(3, 'After Movie Backstage', 'Video/video2.mp4', 'Images/thumb2.jpeg', 'video', 'Suasana santai dan persiapan sebelum manggung.'),
(4, 'Latihan Studio Jamming', 'Images/foto1.jpeg', NULL, 'foto', 'Sesi latihan rutin menyusun aransemen lagu baru.'),
(5, 'Foto Bersama Penggemar', 'Images/foto2.jpeg', NULL, 'foto', 'Momen kebersamaan fans setia di garis depan stage.'),
(6, 'Penampilan Enerjik Panggung', 'Images/foto3.jpeg', NULL, 'foto', 'Aksi distorsi gitar dan teriakan vokalis di atas panggung.');

-- --------------------------------------------------------
-- 5. TABEL JADWAL TUR KONSER
-- --------------------------------------------------------
CREATE TABLE `jadwal_tur` (
  `id_acara` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_acara` varchar(150) NOT NULL,
  `kota` varchar(100) NOT NULL,
  `lokasi_venue` varchar(150) NOT NULL,
  `tanggal_acara` datetime NOT NULL,
  `harga_tiket` decimal(12,2) NOT NULL,
  `sisa_kuota` int UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_acara`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `jadwal_tur` (`id_acara`, `nama_acara`, `kota`, `lokasi_venue`, `tanggal_acara`, `harga_tiket`, `sisa_kuota`) VALUES
(1, 'SLR Tour Jakarta', 'Jakarta', 'Punk Arena Senayan', '2026-06-20 19:00:00', 150000.00, 18),
(2, 'SLR Tour Bandung', 'Bandung', 'Chaos Hall Dago', '2026-06-25 19:00:00', 120000.00, 68),
(3, 'SLR Tour Bekasi', 'Bekasi', 'SLR Underground Stage', '2026-07-01 19:00:00', 100000.00, 45),
(4, 'SLR Pontianak Rockfest', 'Pontianak', 'PCC Auditorium', '2026-11-28 14:05:00', 100000.00, 50),
(5, 'SLR Singkawang Chaos', 'Singkawang', 'Stadion Kridasana', '2026-12-05 13:55:00', 95000.00, 120);

-- --------------------------------------------------------
-- 6. TABEL PRODUK MERCHANDISE
-- --------------------------------------------------------
CREATE TABLE `produk_merch` (
  `id_produk` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_produk` varchar(150) NOT NULL,
  `deskripsi` text,
  `harga` decimal(12,2) NOT NULL,
  `stok` int UNSIGNED NOT NULL DEFAULT '0',
  `gambar_produk` text DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_produk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `produk_merch` (`id_produk`, `nama_produk`, `deskripsi`, `harga`, `stok`, `gambar_produk`) VALUES
(1, 'Kaos Official SLR', 'Kaos bahan Cotton Combed 30s sablon plastisol premium.', 100000.00, 50, 'Images/baju.jpeg'),
(2, 'Album CD Official SLR', 'Physical CD Album SLR berisi 13 trek lagu lengkap booklet.', 50000.00, 100, 'Images/cd.jpeg'),
(3, 'Poster Official SLR', 'Poster eksklusif bahan Art Carton A2 bertanda tangan personel.', 30000.00, 199, 'Images/poster.jpg'),
(4, 'Sepatu Sneakers SLR Punk', 'Sneakers canvas edisi terbatas SLR Punk Rock.', 250000.00, 40, 'Images/spt.JPG'),
(5, 'Meja Miniatur Stage SLR', 'Merchandise meja miniatur stage edisi kolektor.', 150000.00, 25, 'Images/mj.PNG');

-- --------------------------------------------------------
-- 7. TABEL PESANAN TIKET KONSER
-- --------------------------------------------------------
CREATE TABLE `pesanan_tiket` (
  `id_pesanan` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_tiket` varchar(30) NOT NULL,
  `id_pengguna` int UNSIGNED NOT NULL,
  `id_acara` int UNSIGNED NOT NULL,
  `jumlah_tiket` int UNSIGNED NOT NULL,
  `biaya_admin` decimal(12,2) NOT NULL DEFAULT '5000.00',
  `total_bayar` decimal(12,2) NOT NULL,
  `metode_pembayaran` enum('qris','dana','ovo') NOT NULL DEFAULT 'qris',
  `status_pembayaran` enum('menunggu','lunas','batal') NOT NULL DEFAULT 'lunas',
  `waktu_transaksi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pesanan`),
  UNIQUE KEY `uq_kode_tiket` (`kode_tiket`),
  KEY `fk_tiket_pengguna` (`id_pengguna`),
  KEY `fk_tiket_acara` (`id_acara`),
  CONSTRAINT `fk_pesanan_tiket_acara` FOREIGN KEY (`id_acara`) REFERENCES `jadwal_tur` (`id_acara`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_pesanan_tiket_pengguna` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pesanan_tiket` (`id_pesanan`, `kode_tiket`, `id_pengguna`, `id_acara`, `jumlah_tiket`, `biaya_admin`, `total_bayar`, `metode_pembayaran`, `status_pembayaran`, `waktu_transaksi`) VALUES
(1, 'SLR-TKT-10821', 1, 1, 1, 5000.00, 155000.00, 'qris', 'lunas', '2026-11-05 07:29:06'),
(2, 'SLR-TKT-29402', 1, 2, 2, 5000.00, 245000.00, 'dana', 'lunas', '2026-11-05 08:07:30'),
(3, 'SLR-TKT-38194', 2, 4, 1, 5000.00, 105000.00, 'ovo', 'lunas', '2026-11-06 10:11:00');

-- --------------------------------------------------------
-- 8. TABEL PESANAN MERCHANDISE (MASTER)
-- --------------------------------------------------------
CREATE TABLE `pesanan_merch` (
  `id_pesanan` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pengguna` int UNSIGNED DEFAULT NULL,
  `kode_pesanan` varchar(30) NOT NULL,
  `nama_penerima` varchar(100) NOT NULL,
  `nomor_telepon` varchar(25) NOT NULL,
  `alamat_pengiriman` text NOT NULL,
  `biaya_admin` decimal(12,2) NOT NULL DEFAULT '5000.00',
  `ongkos_kirim` decimal(12,2) NOT NULL DEFAULT '15000.00',
  `total_bayar` decimal(12,2) NOT NULL,
  `metode_pembayaran` enum('qris','dana','ovo') NOT NULL DEFAULT 'qris',
  `status_pesanan` enum('menunggu','diproses','dikirim','selesai','dibatalkan') NOT NULL DEFAULT 'diproses',
  `nomor_resi` varchar(50) DEFAULT NULL,
  `waktu_transaksi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pesanan`),
  UNIQUE KEY `uq_kode_pesanan_merch` (`kode_pesanan`),
  KEY `fk_merch_pengguna` (`id_pengguna`),
  CONSTRAINT `fk_pesanan_merch_pengguna` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pesanan_merch` (`id_pesanan`, `id_pengguna`, `kode_pesanan`, `nama_penerima`, `nomor_telepon`, `alamat_pengiriman`, `biaya_admin`, `ongkos_kirim`, `total_bayar`, `metode_pembayaran`, `status_pesanan`, `nomor_resi`, `waktu_transaksi`) VALUES
(1, 1, 'SLR-MERCH-24739', 'Ahyas Ulumudin', '089797978686', 'Jl. Husein Hamzah Komp. Dwi Ratna Indah 3 C-23', 5000.00, 15000.00, 120000.00, 'qris', 'dikirim', 'JNE-SLR9981273', '2026-11-05 08:05:16'),
(2, 2, 'SLR-MERCH-46095', 'Nazriel Putra', '081234567890', 'Jl. Reformasi No. 12, Pontianak', 5000.00, 15000.00, 70000.00, 'ovo', 'diproses', NULL, '2026-11-06 11:30:22');

-- --------------------------------------------------------
-- 9. TABEL DETAIL PESANAN MERCHANDISE (DETAIL ITEM)
-- --------------------------------------------------------
CREATE TABLE `detail_pesanan_merch` (
  `id_detail` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pesanan` int UNSIGNED NOT NULL,
  `id_produk` int UNSIGNED NOT NULL,
  `ukuran` varchar(10) DEFAULT NULL,
  `catatan` text,
  `jumlah` int UNSIGNED NOT NULL DEFAULT '1',
  `harga_satuan` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id_detail`),
  KEY `fk_detail_merch_pesanan` (`id_pesanan`),
  KEY `fk_detail_merch_produk` (`id_produk`),
  CONSTRAINT `fk_detail_merch_pesanan` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan_merch` (`id_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_detail_merch_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk_merch` (`id_produk`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `detail_pesanan_merch` (`id_detail`, `id_pesanan`, `id_produk`, `ukuran`, `catatan`, `jumlah`, `harga_satuan`, `subtotal`) VALUES
(1, 1, 1, 'L', 'Tolong packing rapi ya min', 1, 100000.00, 100000.00),
(2, 2, 2, NULL, 'Stiker jangan lupa min', 1, 50000.00, 50000.00);

-- --------------------------------------------------------
-- 10. TABEL PESAN KONTAK
-- --------------------------------------------------------
CREATE TABLE `pesan_kontak` (
  `id_pesan` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pengguna` int UNSIGNED DEFAULT NULL,
  `nama_pengirim` varchar(100) NOT NULL,
  `email_pengirim` varchar(100) NOT NULL,
  `isi_pesan` text NOT NULL,
  `waktu_diterima` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pesan`),
  KEY `fk_pesan_kontak_pengguna` (`id_pengguna`),
  CONSTRAINT `fk_pesan_kontak_pengguna` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pesan_kontak` (`id_pesan`, `id_pengguna`, `nama_pengirim`, `email_pengirim`, `isi_pesan`, `waktu_diterima`) VALUES
(1, 1, 'Ahyas Ulumudin', 'ahyas@gmail.com', 'Keren banget konsernya min, ditunggu tour selanjutnya di Pontianak!', '2026-11-05 09:00:15'),
(2, NULL, 'Budi Santoso', 'budi@gmail.com', 'Apakah kaos ukuran XXL masih restock lagi minggu depan?', '2026-11-08 13:20:44');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
