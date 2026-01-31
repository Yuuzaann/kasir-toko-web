<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../includes/koneksi.php';

// ==========================
// VALIDASI LOGIN
// ==========================
if (!isset($_SESSION['user'])) {
    header('Location: /kasir-app/index.php');
    exit;
}

// ==========================
// VALIDASI KERANJANG
// ==========================
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    header('Location: dashboard.php?modul=penjualan&aksi=tampil&msg=cart_empty');
    exit;
}

// ==========================
// DATA INPUT
// ==========================
$total      = (float) ($_POST['total'] ?? 0);
$bayar      = (float) ($_POST['bayar'] ?? 0);
$pelanggan  = !empty($_POST['pelanggan_id']) ? (int)$_POST['pelanggan_id'] : NULL;
$userID     = $_SESSION['user']['UserID'] ?? NULL;

// ==========================
// VALIDASI BAYAR
// ==========================
if ($bayar < $total) {
    header('Location: dashboard.php?modul=penjualan&aksi=tampil&msg=bayar_kurang');
    exit;
}

$kembalian = $bayar - $total;

// ==========================
// SIMPAN PENJUALAN
// ==========================
mysqli_query($koneksi, "
    INSERT INTO penjualan 
    (TotalHarga, PelangganID, UserID, Bayar, Kembalian)
    VALUES (
        '$total',
        " . ($pelanggan ? "'$pelanggan'" : "NULL") . ",
        " . ($userID ? "'$userID'" : "NULL") . ",
        '$bayar',
        '$kembalian'
    )
");

$penjualanID = mysqli_insert_id($koneksi);

// ==========================
// SIMPAN DETAIL & UPDATE STOK
// ==========================
foreach ($cart as $produkID => $qty) {

    $q = mysqli_query(
        $koneksi,
        "SELECT Harga, Stok FROM produk WHERE ProdukID=".(int)$produkID
    );
    $produk = mysqli_fetch_assoc($q);

    if (!$produk) continue;

    $subtotal = $produk['Harga'] * $qty;

    mysqli_query($koneksi, "
        INSERT INTO detailpenjualan
        (PenjualanID, ProdukID, JumlahProduk, Subtotal)
        VALUES
        ('$penjualanID', '$produkID', '$qty', '$subtotal')
    ");

    $stokBaru = max(0, $produk['Stok'] - $qty);
    mysqli_query(
        $koneksi,
        "UPDATE produk SET Stok='$stokBaru' WHERE ProdukID='$produkID'"
    );
}

// ==========================
// CLEAR CART
// ==========================
unset($_SESSION['cart']);

// ==========================
// REDIRECT SUCCESS
// ==========================
header(
    "Location: dashboard.php?modul=penjualan&aksi=tampil" .
    "&msg=success&kembalian=" . (int)$kembalian
);
exit;
