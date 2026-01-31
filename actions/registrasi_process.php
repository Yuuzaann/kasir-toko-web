<?php
session_start();
require_once __DIR__ . '/../includes/koneksi.php';

if ($_SESSION['user']['Role'] !== 'Administrator') exit;

$act = $_POST['act'];

if ($act === 'create') {
    $u = mysqli_real_escape_string($koneksi, $_POST['username']);
    $p = md5($_POST['password']); // ubah password hash ke MD5
    $r = $_POST['role'];

    mysqli_query($koneksi,
      "INSERT INTO pengguna (Username, PasswordHash, Role)
       VALUES ('$u','$p','$r')");
}

if ($act === 'update') {
    $id = (int)$_POST['id'];
    $u = mysqli_real_escape_string($koneksi, $_POST['username']);
    $r = $_POST['role'];

    if (!empty($_POST['password'])) {
        $p = md5($_POST['password']); // ubah password hash ke MD5
        mysqli_query($koneksi,
          "UPDATE pengguna SET Username='$u', PasswordHash='$p', Role='$r'
           WHERE UserID=$id");
    } else {
        mysqli_query($koneksi,
          "UPDATE pengguna SET Username='$u', Role='$r'
           WHERE UserID=$id");
    }
}

header("Location: /kasir-app/dashboard.php?modul=registrasi&aksi=tampil&msg=success");
exit;
