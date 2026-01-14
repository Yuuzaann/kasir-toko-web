<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once __DIR__ . '/koneksi.php';

function is_logged() {
    return isset($_SESSION['user']);
}

function require_login() {
    if (!is_logged()) {
        header('Location: /kasir-app/index.php');
        exit;
    }
}

function current_user() {
    return $_SESSION['user'] ?? null;
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BitKasir</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="/kasir-app/assets/css/style.css">
  <link rel="stylesheet" href="/kasir-app/assets/css/dashboard.css">
</head>
<body class="bg-white">

<!-- Tombol toggle sidebar (mobile) -->
<button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <h5>BitKasir</h5>
    <small class="text-muted"><?=htmlspecialchars(current_user()['Username'] ?? 'Guest')?></small>
  </div>
  <ul class="nav flex-column">
    <?php if (is_logged()): ?>
      <li><a class="nav-link" href="/kasir-app/pages/dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
      <li><a class="nav-link" href="/kasir-app/pages/produk.php"><i class="bi bi-box-seam"></i> Produk</a></li>
      <li><a class="nav-link" href="/kasir-app/pages/pelanggan.php"><i class="bi bi-people"></i> Pelanggan</a></li>
      <li><a class="nav-link" href="/kasir-app/pages/transaksi.php"><i class="bi bi-cash-stack"></i> Transaksi</a></li>
      <li><a class="nav-link" href="/kasir-app/pages/laporan.php"><i class="bi bi-clipboard-data"></i> Laporan</a></li>
      <?php if (isset($_SESSION['user']) && $_SESSION['user']['Role'] === 'Administrator'): ?>
        <li><a class="nav-link" href="/kasir-app/pages/registrasi.php"><i class="bi bi-person-plus"></i> Registrasi</a></li>
      <?php endif; ?>
      <li><a class="nav-link logout-link" href="/kasir-app/actions/logout_process.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
    <?php endif; ?>
  </ul>
</div>

<!-- Konten -->
<div class="content">
  <div class="container-fluid my-3">
