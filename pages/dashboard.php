<?php
// Dashboard: statistik singkat
require_once __DIR__ . '/../includes/header.php';
// Proteksi halaman (PROSEDUR)
require_login();

// Ambil data statistik (FUNGSI sederhana inline)
$total_produk = mysqli_fetch_row(mysqli_query($koneksi, 'SELECT COUNT(*) FROM produk'))[0];
$total_pelanggan = mysqli_fetch_row(mysqli_query($koneksi, 'SELECT COUNT(*) FROM pelanggan'))[0];
$total_penjualan = mysqli_fetch_row(mysqli_query($koneksi, 'SELECT COUNT(*) FROM penjualan'))[0];
?>
<h3>Dashboard</h3>
<div class="row g-3">
  <div class="col-md-4">
    <div class="card p-3">
      <div class="text-muted">Total Produk</div>
      <h4><?= $total_produk ?></h4>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card p-3">
      <div class="text-muted">Total Pelanggan</div>
      <h4><?= $total_pelanggan ?></h4>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card p-3">
      <div class="text-muted">Total Penjualan</div>
      <h4><?= $total_penjualan ?></h4>
    </div>
  </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
