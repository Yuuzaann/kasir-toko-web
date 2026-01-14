<?php
// Halaman login utama
session_start();
if (isset($_SESSION['user'])) {
    header('Location: /kasir-app/pages/dashboard.php');
    exit;
}
$error = '';
// jika form login disubmit, arahkan ke actions/login_process.php
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Kasir App</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/kasir-app/assets/css/style.css">
</head>
<body class="bg-light">
  <div class="d-flex justify-content-center align-items-center" style="min-height:100vh">
    <div class="card shadow-sm" style="width:420px">
      <div class="card-body">
        <h4 class="card-title mb-3">Login Aplikasi Kasir</h4>
        <?php if (!empty($_GET['err'])): ?>
          <div class="alert alert-danger"><?=htmlspecialchars($_GET['err'])?></div>
        <?php endif; ?>
        <form action="/kasir-app/actions/login_process.php" method="post">
          <div class="mb-2">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="d-grid">
            <button class="btn btn-primary">Login</button>
          </div>
        </form>
        <p class="mt-3 small text-muted">Default admin: buat lewat halaman registrasi atau ganti hash di SQL.</p>
      </div>
    </div>
  </div>
</body>
</html>
