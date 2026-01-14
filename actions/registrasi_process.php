<?php
session_start();
include_once __DIR__ . '/../includes/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] !== 'Administrator') {
    header('Location: /kasir-app/index.php?err=' . urlencode('Akses ditolak'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];
    $role     = mysqli_real_escape_string($koneksi, $_POST['role']);

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO pengguna (Username, PasswordHash, Role) VALUES ('$username', '$hash', '$role')";
    mysqli_query($koneksi, $sql);
    header('Location: /kasir-app/pages/registrasi.php?success=1');
    exit;
}
?>
