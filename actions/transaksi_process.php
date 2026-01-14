<?php
session_start();
include_once __DIR__ . '/../includes/koneksi.php';

if (!isset($_SESSION['user'])) { 
    header('Location: /kasir-app/index.php'); 
    exit; 
}

if (!isset($_POST['checkout'])) { 
    header('Location: /kasir-app/pages/transaksi.php'); 
    exit; 
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) { 
    header('Location: /kasir-app/pages/transaksi.php'); 
    exit; 
}

$total = (float) $_POST['total'];
$bayar = (float) ($_POST['bayar'] ?? 0);

if ($bayar < $total) {
    header("Location: /kasir-app/pages/transaksi.php?msg=bayar_kurang");
    exit;
}

$kembalian = $bayar - $total;
$pelanggan = !empty($_POST['pelanggan_id']) ? (int) $_POST['pelanggan_id'] : 'NULL';
$user = $_SESSION['user']['UserID'] ?? 'NULL';

// Simpan penjualan
mysqli_query($koneksi, "INSERT INTO penjualan (TotalHarga, PelangganID, UserID, Bayar, Kembalian) VALUES ($total, $pelanggan, $user, $bayar, $kembalian)");
$penjualan_id = mysqli_insert_id($koneksi);

// Simpan detail & update stok
foreach ($cart as $pid => $qty) {
    $row = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT Harga, Stok FROM produk WHERE ProdukID = " . (int)$pid));
    $subtotal = $row['Harga'] * $qty;
    mysqli_query($koneksi, "INSERT INTO detailpenjualan (PenjualanID, ProdukID, JumlahProduk, Subtotal) VALUES ($penjualan_id, $pid, $qty, $subtotal)");
    $newstok = max(0, $row['Stok'] - $qty);
    mysqli_query($koneksi, "UPDATE produk SET Stok = $newstok WHERE ProdukID = $pid");
}

// Kosongkan keranjang
unset($_SESSION['cart']);

// Redirect balik dengan notifikasi sukses + kembalian
header("Location: /kasir-app/pages/transaksi.php?msg=success&kembalian=$kembalian");
exit;
?>
