<?php
session_start();
include_once __DIR__ . '/../includes/koneksi.php';

if (!isset($_SESSION['user'])) {
    header('Location: /kasir-app/index.php');
    exit;
}

$act = $_POST['act'] ?? '';

/* =======================
   FOLDER UPLOAD
======================= */
$uploadDir = __DIR__ . '/../uploads/pelanggan/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

/* =======================
   FUNCTION UPLOAD FOTO
======================= */
function uploadFoto($file)
{
    global $uploadDir;

    if (!isset($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg','jpeg','png','gif'])) {
        return null;
    }

    $newName = 'pelanggan_' . uniqid() . '.' . $ext;
    move_uploaded_file($file['tmp_name'], $uploadDir . $newName);
    return $newName;
}

/* =======================
   CREATE
======================= */
if ($act === 'create') {

    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $telp   = mysqli_real_escape_string($koneksi, $_POST['telp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $foto   = uploadFoto($_FILES['foto']);

    mysqli_query($koneksi, "
        INSERT INTO pelanggan 
        (NamaPelanggan, Alamat, NomorTelepon, Foto)
        VALUES ('$nama','$alamat','$telp','$foto')
    ");

    header('Location: /kasir-app/dashboard.php?modul=pelanggan&aksi=tampil');
    exit;
}

/* =======================
   DELETE
======================= */
if ($act === 'delete') {

    $id = (int) $_POST['id'];

    $q = mysqli_query($koneksi, "SELECT Foto FROM pelanggan WHERE PelangganID=$id");
    if ($r = mysqli_fetch_assoc($q)) {
        if (!empty($r['Foto']) && file_exists($uploadDir.$r['Foto'])) {
            unlink($uploadDir.$r['Foto']);
        }
    }

    mysqli_query($koneksi, "DELETE FROM pelanggan WHERE PelangganID=$id");

    header('Location: /kasir-app/dashboard.php?modul=pelanggan&aksi=tampil');
    exit;
}

/* =======================
   UPDATE
======================= */
if ($act === 'update') {

    $id     = (int) $_POST['id'];
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $telp   = mysqli_real_escape_string($koneksi, $_POST['telp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    $fotoBaru = uploadFoto($_FILES['foto']);

    if ($fotoBaru) {

        $q = mysqli_query($koneksi, "SELECT Foto FROM pelanggan WHERE PelangganID=$id");
        if ($r = mysqli_fetch_assoc($q)) {
            if (!empty($r['Foto']) && file_exists($uploadDir.$r['Foto'])) {
                unlink($uploadDir.$r['Foto']);
            }
        }

        mysqli_query($koneksi, "
            UPDATE pelanggan SET
                NamaPelanggan='$nama',
                NomorTelepon='$telp',
                Alamat='$alamat',
                Foto='$fotoBaru'
            WHERE PelangganID=$id
        ");

    } else {

        mysqli_query($koneksi, "
            UPDATE pelanggan SET
                NamaPelanggan='$nama',
                NomorTelepon='$telp',
                Alamat='$alamat'
            WHERE PelangganID=$id
        ");
    }

    header('Location: /kasir-app/dashboard.php?modul=pelanggan&aksi=tampil');
    exit;
}

/* =======================
   DEFAULT
======================= */
header('Location: /kasir-app/dashboard.php?modul=pelanggan&aksi=tampil');
exit;
