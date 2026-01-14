<?php
session_start();
include_once __DIR__ . '/../includes/koneksi.php';

if (!isset($_SESSION['user'])) {
    header('Location: /kasir-app/index.php');
    exit;
}

$act = $_POST['act'] ?? '';

// folder upload khusus pelanggan
$uploadDir = __DIR__ . '/../uploads/pelanggan/';

// buat folder kalau belum ada
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// fungsi upload foto
function uploadFoto($file)
{
    global $uploadDir;

    if (
        !isset($file['name']) ||
        $file['error'] !== UPLOAD_ERR_OK
    ) {
        return null;
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($ext, $allowed)) {
        return null;
    }

    $newName = 'pelanggan_' . uniqid() . '.' . $ext;

    if (move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
        return $newName;
    }

    return null;
}

/* =======================
   CREATE
======================= */
if ($act === 'create') {

    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $telp   = mysqli_real_escape_string($koneksi, $_POST['telp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    if (!is_numeric($telp)) {
        echo "<script>alert('Nomor telepon harus berupa angka!');history.back();</script>";
        exit;
    }

    $foto = uploadFoto($_FILES['foto']);

    mysqli_query($koneksi, "
        INSERT INTO pelanggan 
        (NamaPelanggan, Alamat, NomorTelepon, Foto)
        VALUES ('$nama','$alamat','$telp','$foto')
    ");

    header('Location: /kasir-app/pages/pelanggan.php');
    exit;
}

/* =======================
   DELETE
======================= */
if ($act === 'delete') {

    $id = (int) $_POST['id'];

    $q = mysqli_query($koneksi, "
        SELECT Foto FROM pelanggan 
        WHERE PelangganID = $id
    ");

    if ($r = mysqli_fetch_assoc($q)) {
        if (!empty($r['Foto']) && file_exists($uploadDir . $r['Foto'])) {
            unlink($uploadDir . $r['Foto']);
        }
    }

    mysqli_query($koneksi, "
        DELETE FROM pelanggan 
        WHERE PelangganID = $id
    ");

    header('Location: /kasir-app/pages/pelanggan.php');
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

    if (!is_numeric($telp)) {
        echo "<script>alert('Nomor telepon harus berupa angka!');history.back();</script>";
        exit;
    }

    $fotoBaru = uploadFoto($_FILES['foto']);

    if ($fotoBaru) {

        // hapus foto lama
        $q = mysqli_query($koneksi, "
            SELECT Foto FROM pelanggan 
            WHERE PelangganID = $id
        ");

        if ($r = mysqli_fetch_assoc($q)) {
            if (!empty($r['Foto']) && file_exists($uploadDir . $r['Foto'])) {
                unlink($uploadDir . $r['Foto']);
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

    header('Location: /kasir-app/pages/pelanggan.php');
    exit;
}

header('Location: /kasir-app/pages/pelanggan.php');
exit;
?>
