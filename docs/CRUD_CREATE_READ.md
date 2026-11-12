# LAPORAN IMPLEMENTASI WBS 2.2: CREATE & READ
**Kelompok 3 - Praktikum Pemrograman Web (Sesi 15)**
**Assignee:** Ahyas Ulumudin Wicaksono
**Proyek:** SLR Band Official Website

---

## 1. Ringkasan Implementasi
Pada tahap ini (WBS 2.2), seluruh operasi **Create (INSERT)** dan **Read (SELECT)** untuk 10 tabel basis data `db_slr` telah diimplementasikan secara terintegrasi antara frontend user dan backend database MySQL:

| No | Nama Tabel | Operasi | File Implementasi | Query SQL Utama |
|---|---|---|---|---|
| 1 | `lagu` | Read (SELECT) | `index.php`, `admin_lagu.php` | `SELECT * FROM lagu ORDER BY id_lagu ASC` |
| 2 | `galeri` | Read (SELECT) | `index.php`, `admin_galeri.php` | `SELECT * FROM galeri ORDER BY id_galeri DESC` |
| 3 | `jadwal_tur` | Read (SELECT) | `index.php`, `admin_tur.php` | `SELECT * FROM jadwal_tur ORDER BY tanggal ASC` |
| 4 | `produk_merch` | Read (SELECT) | `index.php`, `admin_merch.php` | `SELECT * FROM produk_merch ORDER BY id_produk DESC` |
| 5 | `pengguna` | Create (INSERT) | `register.php` | `INSERT INTO pengguna (nama_lengkap, email, password, no_hp, alamat) VALUES (...)` |
| 6 | `pengguna` | Read (SELECT) | `login.php`, `profil.php`, `header.php` | `SELECT * FROM pengguna WHERE email = '$email'` |
| 7 | `admin` | Read (SELECT) | `admin_login.php`, `admin.php` | `SELECT * FROM admin WHERE username = '$username'` |
| 8 | `pesanan_tiket` | Create (INSERT) | `beli_tiket.php` | `INSERT INTO pesanan_tiket (id_pengguna, id_tur, kode_tiket, jumlah_tiket, total_harga, status_pembayaran) VALUES (...)` |
| 9 | `pesanan_tiket` | Read (SELECT) | `profil.php`, `admin_pesanan.php` | `SELECT t.*, j.nama_konser, j.kota FROM pesanan_tiket t JOIN jadwal_tur j ...` |
| 10 | `pesanan_merch` | Create (INSERT) | `beli_merch.php` | `INSERT INTO pesanan_merch (id_pengguna, total_pembayaran, status_pesanan, ...) VALUES (...)` |
| 11 | `detail_pesanan_merch` | Create (INSERT) | `beli_merch.php` | `INSERT INTO detail_pesanan_merch (id_pesanan, id_produk, jumlah, harga_satuan, ukuran, subtotal) VALUES (...)` |
| 12 | `detail_pesanan_merch` | Read (SELECT) | `profil.php`, `admin_pesanan.php` | `SELECT d.*, p.nama_produk FROM detail_pesanan_merch d JOIN produk_merch p ...` |
| 13 | `pesan_kontak` | Create (INSERT) | `kirim_pesan.php` | `INSERT INTO pesan_kontak (nama, email, subjek, pesan) VALUES (...)` |
| 14 | `pesan_kontak` | Read (SELECT) | `admin_kontak.php` | `SELECT * FROM pesan_kontak ORDER BY tanggal_kirim DESC` |

---

## 2. Fitur Tambahan Create & Read
1. **Otomatisasi Kode Tiket Unik**: Generate `kode_tiket` acak (contoh: `SLR-TKT-12345`) saat user membeli tiket konser di `beli_tiket.php`.
2. **Auto-Fill Data User**: Pada form pembelian dan Contact Us (`index.php`), nama dan email terisi otomatis dari sesi login.
3. **Penyimpanan Detail Transaksi**: Pembelian merchandise menyimpan transaksi induk ke `pesanan_merch` dan detail item ke `detail_pesanan_merch` secara bersamaan menggunakan `mysqli_insert_id()`.
