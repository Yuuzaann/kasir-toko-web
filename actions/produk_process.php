<?php
session_start();
include_once __DIR__ . '/../includes/koneksi.php';
if (!isset($_SESSION['user'])) { 
    header('Location: /kasir-app/index.php'); 
    exit; 
}

$act = $_POST['act'] ?? '';
$uploadDir = __DIR__ . '/../uploads/produk/'; // perbaiki path dengan slash di akhir

if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

function uploadFoto($file) {
    global $uploadDir;
    if (!isset($file['name']) || $file['error'] !== UPLOAD_ERR_OK) return null;
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) return null;
    $newName = uniqid('foto_') . '.' . $ext;
    move_uploaded_file($file['tmp_name'], $uploadDir . $newName);
    return $newName;
}

if ($act === 'create') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $harga = (float) $_POST['harga'];
    $stok = (int) $_POST['stok'];
    $foto = uploadFoto($_FILES['foto']);

    mysqli_query($koneksi, "INSERT INTO produk (NamaProduk, Harga, Stok, Foto) VALUES ('$nama', '$harga', '$stok', '$foto')");
    header('Location: /kasir-app/pages/produk.php');
    exit;
}

if ($act === 'delete') {
    $id = (int) $_POST['id'];
    $q = mysqli_query($koneksi, "SELECT Foto FROM produk WHERE ProdukID = $id");
    if ($r = mysqli_fetch_assoc($q)) {
        if (!empty($r['Foto']) && file_exists($uploadDir . $r['Foto'])) {
            unlink($uploadDir . $r['Foto']);
        }
    }
    mysqli_query($koneksi, "DELETE FROM produk WHERE ProdukID = $id");
    header('Location: /kasir-app/pages/produk.php');
    exit;
}

if ($act === 'update') {
    $id = (int) $_POST['id'];
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $harga = (float) $_POST['harga'];
    $stok = (int) $_POST['stok'];

    $fotoBaru = uploadFoto($_FILES['foto']);
    if ($fotoBaru) {
        $q = mysqli_query($koneksi, "SELECT Foto FROM produk WHERE ProdukID = $id");
        if ($r = mysqli_fetch_assoc($q)) {
            if (!empty($r['Foto']) && file_exists($uploadDir . $r['Foto'])) {
                unlink($uploadDir . $r['Foto']);
            }
        }
        mysqli_query($koneksi, "UPDATE produk SET NamaProduk='$nama', Harga=$harga, Stok=$stok, Foto='$fotoBaru' WHERE ProdukID=$id");
    } else {
        mysqli_query($koneksi, "UPDATE produk SET NamaProduk='$nama', Harga=$harga, Stok=$stok WHERE ProdukID=$id");
    }

    header('Location: /kasir-app/pages/produk.php');
    exit;
}

header('Location: /kasir-app/pages/produk.php');
exit;
?>
