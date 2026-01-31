<?php 
session_start();
if (isset($_SESSION['user'])) {
    header('Location: /kasir-app/pages/dashboard.php');
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Kasir App</title>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <!-- Google Font (optional) -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    .login-card {
      max-width: 400px;
      width: 100%;
      background: #fff;
      border-radius: 1rem;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      padding: 2rem;
    }
    .form-control:focus {
      box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25) !important;
      border-color: #3b82f6 !important;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-slate-100 to-slate-200 min-h-screen d-flex justify-content-center align-items-center">

  <div class="login-card mx-auto">

    <h1 class="text-center text-2xl fw-bold mb-4 text-gray-800">
      <i class="bi bi-box-seam me-2"></i> Login Kasir App
    </h1>

    <?php if (!empty($_GET['err'])): ?>
      <div class="alert alert-danger d-flex align-items-center" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <div><?= htmlspecialchars($_GET['err']) ?></div>
      </div>
    <?php endif; ?>

    <form action="/kasir-app/actions/login_process.php" method="post" class="mt-3">
      
      <div class="mb-3">
        <label for="username" class="form-label fw-medium">Username</label>
        <input
          type="text"
          id="username"
          name="username"
          required
          class="form-control rounded-lg shadow-sm"
          placeholder="Masukkan username..."
        >
      </div>

      <div class="mb-3">
        <label for="password" class="form-label fw-medium">Password</label>
        <input
          type="password"
          id="password"
          name="password"
          required
          class="form-control rounded-lg shadow-sm"
          placeholder="Masukkan password..."
        >
      </div>

      <button
        type="submit"
        class="btn btn-primary w-100 py-2 fw-semibold d-flex justify-content-center align-items-center gap-2"
      >
        <i class="bi bi-box-arrow-in-right"></i> Login
      </button>
    </form>

  </div>

</body>
</html>
