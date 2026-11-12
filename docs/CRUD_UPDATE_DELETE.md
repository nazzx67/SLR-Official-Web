# LAPORAN IMPLEMENTASI WBS 2.3: UPDATE & DELETE
**Kelompok 3 - Praktikum Pemrograman Web (Sesi 15)**
**Assignee:** Hilmi Andika
**Proyek:** SLR Band Official Website

---

## 1. Ringkasan Implementasi
Pada tahap ini (WBS 2.3), seluruh operasi **Update** dan **Delete** untuk manajemen konten dan data transaksi telah diimplementasikan:

| No | Modul / Tabel | Operasi | File Implementasi | Deskripsi Aksi |
|---|---|---|---|---|
| 1 | `pengguna` | Update | `profil.php` | Update nama, no_hp, alamat, dan upload foto profil (`Images/profile/`) |
| 2 | `lagu` | Update & Delete | `admin_lagu.php` | Update judul, durasi, file audio, serta hapus data lagu |
| 3 | `galeri` | Update & Delete | `admin_galeri.php` | Update judul, jenis media (video/gambar), upload file lokal / URL YouTube & TikTok, serta hapus item galeri |
| 4 | `jadwal_tur` | Update & Delete | `admin_tur.php` | Update tanggal, kota, venue, harga tiket, kuota, serta hapus jadwal konser |
| 5 | `produk_merch` | Update & Delete | `admin_merch.php` | Update nama produk, harga, stok, upload gambar produk, serta hapus produk |
| 6 | `pesanan_tiket` | Update & Delete | `admin_pesanan.php` | Update status pembayaran (menunggu, lunas, batal) & hapus riwayat pesanan tiket |
| 7 | `pesanan_merch` | Update & Delete | `admin_pesanan.php` | Update status pesanan (menunggu, diproses, dikirim, selesai, dibatalkan), update nomor resi pengiriman, & hapus pesanan merch beserta detailnya (CASCADE) |
| 8 | `pesan_kontak` | Delete | `admin_kontak.php` | Hapus pesan aspirasi / booking dari user |

---

## 2. Fitur Keamanan dan Validasi
1. **Pencegahan Data Yatim (Orphan Records)**: Penghapusan pesanan merchandise menggunakan `CASCADE` pada `detail_pesanan_merch` sehingga data konsisten.
2. **Validasi File Upload**: Validasi ekstensi dan tipe MIME untuk upload foto profil (`JPG, PNG, WEBP`), audio lagu (`MP3, WAV`), dan media galeri.
3. **Konfirmasi Aksi Penghapusan**: Dilengkapi popup konfirmasi JavaScript `confirm('Yakin ingin menghapus data ini?')` sebelum query `DELETE` dieksekusi.
