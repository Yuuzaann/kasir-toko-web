<?php
/********************************
 * INIT & SECURITY
 ********************************/
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/includes/koneksi.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$user = $_SESSION['user'];

/********************************
 * DASHBOARD COUNTER (SAFE)
 ********************************/
function countData($koneksi, $table) {
    $cek = mysqli_query($koneksi, "SHOW TABLES LIKE '$table'");
    if (mysqli_num_rows($cek) === 0) return 0;

    $q = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM $table");
    $r = mysqli_fetch_assoc($q);
    return $r['total'] ?? 0;
}

$totalProduk    = countData($koneksi, 'produk');
$totalPelanggan = countData($koneksi, 'pelanggan');
$totalPenjualan = countData($koneksi, 'penjualan');

/********************************
 * ROUTING
 ********************************/
$modul = $_GET['modul'] ?? 'dashboard';
$aksi  = $_GET['aksi'] ?? 'tampil';

$modulFile = __DIR__ . "/modules/$modul/$aksi.php";
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard | BitKasir</title>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
  background:#f1f5f9;
  font-family: 'Inter', sans-serif;
}

/* SIDEBAR */
#sidebar {
  width:240px;
  z-index:1040;
}

/* MAIN CONTENT */
@media (min-width:768px) {
  #mainContent {
    margin-left:240px;
  }
}

.nav-link {
  border-radius:.5rem;
}
.nav-link.active {
  background:#e0e7ff;
  font-weight:600;
}

/* MOBILE SIDEBAR */
@media (max-width:767px) {
  #sidebar {
    position:fixed;
    top:0;
    left:0;
    height:100vh;
    background:#fff;
  }
  #sidebarOverlay {
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.3);
    z-index:1030;
    display:none;
  }
}

/* TABLE FIX */
.table td, .table th {
  vertical-align:middle;
}
</style>
</head>

<body>

<!-- OVERLAY MOBILE -->
<div id="sidebarOverlay"></div>

<!-- TOPBAR MOBILE -->
<nav class="navbar bg-white shadow-sm d-md-none px-3">
  <button class="btn btn-outline-secondary btn-sm" id="toggleSidebar"><i class="bi bi-list"></i></button>
  <span class="fw-bold text-primary">BitKasir</span>
</nav>

<!-- SIDEBAR -->
<aside id="sidebar"
  class="bg-white shadow-sm position-fixed position-md-fixed vh-100 d-none d-md-block">

  <div class="p-3 border-bottom">
    <h5 class="fw-bold text-primary mb-0">BitKasir</h5>
    <small class="text-muted"><?= htmlspecialchars($user['Username']) ?></small>
  </div>

  <ul class="nav flex-column p-2 gap-1">

    <li class="nav-item">
      <a class="nav-link <?= $modul==='dashboard'?'active':'' ?>"
         href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    </li>

    <li class="nav-item">
      <a class="nav-link <?= $modul==='produk'?'active':'' ?>"
         href="dashboard.php?modul=produk&aksi=tampil"><i class="bi bi-box-seam me-2"></i> Produk</a>
    </li>

    <li class="nav-item">
      <a class="nav-link <?= $modul==='pelanggan'?'active':'' ?>"
         href="dashboard.php?modul=pelanggan&aksi=tampil"><i class="bi bi-people me-2"></i> Pelanggan</a>
    </li>

    <li class="nav-item">
      <a class="nav-link <?= $modul==='penjualan'?'active':'' ?>"
         href="dashboard.php?modul=penjualan&aksi=tampil"><i class="bi bi-currency-dollar me-2"></i> Penjualan</a>
    </li>

    <li class="nav-item">
      <a class="nav-link <?= $modul==='laporan'?'active':'' ?>"
         href="dashboard.php?modul=laporan&aksi=tampil"><i class="bi bi-file-text me-2"></i> Laporan</a>
    </li>

    <?php if ($user['Role'] === 'Administrator'): ?>
    <li class="nav-item">
      <a class="nav-link <?= $modul==='registrasi'?'active':'' ?>"
         href="dashboard.php?modul=registrasi&aksi=tampil"><i class="bi bi-person-plus me-2"></i> Registrasi</a>
    </li>
    <?php endif; ?>

    <hr>

    <li class="nav-item">
      <a class="nav-link text-danger"
         href="actions/logout_process.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
    </li>

  </ul>
</aside>

<!-- MAIN CONTENT -->
<main id="mainContent" class="p-3">
  <div class="container-fluid">

<?php if ($modul === 'dashboard'): ?>

  <h4 class="fw-bold mb-4">Dashboard</h4>

  <div class="row g-3">
    <div class="col-md-4">
      <div class="bg-white rounded-3 shadow-sm p-4">
        <p class="text-muted mb-1">Total Produk</p>
        <h3 class="fw-bold text-primary"><?= $totalProduk ?></h3>
      </div>
    </div>

    <div class="col-md-4">
      <div class="bg-white rounded-3 shadow-sm p-4">
        <p class="text-muted mb-1">Total Pelanggan</p>
        <h3 class="fw-bold text-success"><?= $totalPelanggan ?></h3>
      </div>
    </div>

    <div class="col-md-4">
      <div class="bg-white rounded-3 shadow-sm p-4">
        <p class="text-muted mb-1">Total Penjualan</p>
        <h3 class="fw-bold text-warning"><?= $totalPenjualan ?></h3>
      </div>
    </div>
  </div>

<?php else: ?>

  <?php
  if (file_exists($modulFile)) {
      include $modulFile;
  } else {
      echo '<div class="alert alert-danger">Modul tidak ditemukan</div>';
  }
  ?>

<?php endif; ?>

  </div>
</main>

<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebarOverlay');

document.getElementById('toggleSidebar')?.addEventListener('click', () => {
  sidebar.classList.toggle('d-none');
  overlay.style.display = sidebar.classList.contains('d-none') ? 'none' : 'block';
});

overlay?.addEventListener('click', () => {
  sidebar.classList.add('d-none');
  overlay.style.display = 'none';
});
</script>

</body>
</html>

