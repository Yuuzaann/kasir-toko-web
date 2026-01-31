<?php
session_start();
include_once __DIR__ . '/../includes/koneksi.php';

if (!isset($_SESSION['user'])) { 
    header('Location: /kasir-app/index.php'); 
    exit; 
}

if (!isset($_POST['checkout'])) { 
    header('Location: /kasir-app/dashboard.php?modul=penjualan&aksi=tampil'); 
    exit; 
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) { 
    header('Location: /kasir-app/dashboard.php?modul=penjualan&aksi=tampil'); 
    exit; 
}

$total = (float) $_POST['total'];
$bayar = (float) ($_POST['bayar'] ?? 0);

if ($bayar < $total) {
    header("Location: /kasir-app/dashboard.php?modul=penjualan&aksi=tampil&msg=bayar_kurang");
    exit;
}

$kembalian = $bayar - $total;
$pelanggan = !empty($_POST['pelanggan_id']) ? (int) $_POST['pelanggan_id'] : 'NULL';
$user = $_SESSION['user']['UserID'] ?? 'NULL';

/* ================= SIMPAN PENJUALAN ================= */
mysqli_query($koneksi, "
    INSERT INTO penjualan 
    (TotalHarga, PelangganID, UserID, Bayar, Kembalian)
    VALUES ($total, $pelanggan, $user, $bayar, $kembalian)
");

$penjualan_id = mysqli_insert_id($koneksi);

/* ================= DETAIL + STOK ================= */
foreach ($cart as $pid => $qty) {
    $pid = (int)$pid;
    $qty = (int)$qty;

    $produk = mysqli_fetch_assoc(
        mysqli_query($koneksi, "SELECT Harga, Stok FROM produk WHERE ProdukID = $pid")
    );

    $subtotal = $produk['Harga'] * $qty;

    mysqli_query($koneksi, "
        INSERT INTO detailpenjualan 
        (PenjualanID, ProdukID, JumlahProduk, Subtotal)
        VALUES ($penjualan_id, $pid, $qty, $subtotal)
    ");

    $stok_baru = max(0, $produk['Stok'] - $qty);
    mysqli_query($koneksi, "
        UPDATE produk SET Stok = $stok_baru WHERE ProdukID = $pid
    ");
}

/* ================= CLEAR CART ================= */
unset($_SESSION['cart']);

/* ================= REDIRECT DASHBOARD ================= */
header("Location: /kasir-app/dashboard.php?modul=penjualan&aksi=tampil&msg=success&kembalian=$kembalian");
exit;
