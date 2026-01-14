# 🧾 Kasir Toko Web

**Kasir Toko Web** adalah aplikasi kasir berbasis web (Point of Sale / POS) yang digunakan untuk mengelola transaksi penjualan, produk, pelanggan, dan laporan penjualan dalam satu sistem terintegrasi.  
Aplikasi ini cocok untuk toko kecil, UMKM, maupun sebagai bahan pembelajaran dan tugas sekolah.

---

## 🚀 Fitur Utama
- Manajemen Produk & Stok
- Transaksi Penjualan (Kasir)
- Keranjang Belanja
- Perhitungan Total & Kembalian Otomatis
- Manajemen Pelanggan
- Laporan Penjualan
- Sistem Login (Admin / Kasir)

---

## 🛠️ Teknologi yang Digunakan
- PHP
- MySQL
- HTML, CSS, JavaScript
- Bootstrap
- XAMPP (Apache & MySQL)

---

## 📂 Cara Instalasi & Menjalankan Aplikasi

### 1️⃣ Persiapan
Pastikan sudah terinstall:
- **XAMPP**
- Browser (Chrome / Firefox / Edge)

---

### 2️⃣ Extract & Pindahkan File
1. Extract file project (ZIP/RAR)
2. Salin atau cut folder **kasir-toko-web**
3. Pindahkan ke direktori: C:\xampp\htdocs\

---

### 3️⃣ Jalankan XAMPP
1. Buka **XAMPP Control Panel**
2. Start service:
   - ✅ Apache
   - ✅ MySQL

---

### 4️⃣ Buat Database
1. Buka browser
2. Akses: http://localhost/phpmyadmin/
3. Klik **New**
4. Buat database dengan nama: **kasir_db**

---

### 5️⃣ Import Database
1. Pilih database **kasir_db**
2. Klik tab **Import**
3. Pilih file database `.sql` dari folder project
4. Klik **Go**

---

### 6️⃣ Konfigurasi Koneksi Database
Buka file konfigurasi database, contoh:

Pastikan konfigurasi seperti berikut:

<?php
$db_host = '127.0.0.1';
$db_user = 'root';
$db_pass = '';
$db_name = 'kasir_db';

$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$koneksi) {
    die('Koneksi gagal: ' . mysqli_connect_error());
}
mysqli_set_charset($koneksi, 'utf8mb4');
?>

---

### 7️⃣ Jalankan Aplikasi
1. Buka browser dan akses: http://localhost/kasir-toko-web


---

### 🔐 Login Default 
1. Username : admin
2. Password : admin



