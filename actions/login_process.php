<?php
// actions/login_process.php
session_start();
include_once __DIR__ . '/../includes/koneksi.php';

// Cek kalau request bukan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '<meta http-equiv="refresh" content="0.1;url=/kasir-app/index.php">';
    exit;
}

// Ambil username dan password
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$hash = md5($password);

// Sanitasi input
$username = mysqli_real_escape_string($koneksi, $username);

// Cek ke database
$sql = "SELECT * FROM pengguna WHERE Username='$username' AND PasswordHash='$hash' LIMIT 1";
$q   = mysqli_query($koneksi, $sql);

if (mysqli_num_rows($q) > 0) {
    $user = mysqli_fetch_assoc($q);
    $_SESSION['user'] = $user;
    echo '<meta http-equiv="refresh" content="0.1;url=/kasir-app/dashboard.php">';
} else {
    echo '<meta http-equiv="refresh" content="0.1;url=/kasir-app/index.php?err=Login gagal!">';
}
?>
