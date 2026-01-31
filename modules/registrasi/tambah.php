<?php
if ($_SESSION['user']['Role'] !== 'Administrator') {
    echo '<div class="alert alert-danger">Akses ditolak</div>';
    return;
}
?>

<h4 class="fw-bold mb-3">➕ Tambah Pengguna</h4>

<div class="card shadow-sm">
<div class="card-body">
<form method="post" action="actions/registrasi_process.php">
<input type="hidden" name="act" value="create">

<input name="username" class="form-control mb-2" placeholder="Username" required>
<input name="password" type="password" class="form-control mb-2" placeholder="Password" required>

<select name="role" class="form-select mb-3">
  <option value="Petugas">Petugas</option>
  <option value="Administrator">Administrator</option>
</select>

<button class="btn btn-success">Simpan</button>
<a href="dashboard.php?modul=registrasi&aksi=tampil"
   class="btn btn-secondary">Batal</a>
</form>
</div>
</div>
