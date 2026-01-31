<?php
$id = (int)($_GET['id'] ?? 0);

$q = mysqli_query($koneksi, "SELECT Foto FROM produk WHERE ProdukID=$id");
$data = mysqli_fetch_assoc($q);

if ($data && $data['Foto']) {
    $file = __DIR__ . '/../../uploads/produk/' . $data['Foto'];
    if (file_exists($file)) unlink($file);
}

mysqli_query($koneksi, "DELETE FROM produk WHERE ProdukID=$id");

header('Location: dashboard.php?modul=produk&aksi=tampil');
exit;
