<?php
require_once __DIR__ . '/../includes/header.php';
require_login();

if ($_SESSION['user']['Role'] !== 'Administrator') {
    echo '<div class="alert alert-danger">Akses ditolak. Hanya Administrator.</div>';
    include_once __DIR__ . '/../includes/footer.php';
    exit;
}
?>
<h3>Registrasi Pengguna</h3>
<div class="card">
  <div class="card-body">
    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success">Pengguna berhasil dibuat.</div>
    <?php endif; ?>
    <form method="post" action="/kasir-app/actions/registrasi_process.php" class="row g-2">
      <div class="col-md-4"><input name="username" class="form-control" placeholder="Username" required></div>
      <div class="col-md-4"><input name="password" type="password" class="form-control" placeholder="Password" required></div>
      <div class="col-md-3">
        <select name="role" class="form-select">
          <option value="Petugas">Petugas</option>
          <option value="Administrator">Administrator</option>
        </select>
      </div>
      <div class="col-md-1 text-end"><button class="btn btn-primary">Simpan</button></div>
    </form>
  </div>
</div>
<?php include_once __DIR__ . '/../includes/footer.php'; ?>
