<?php
if ($_SESSION['user']['Role'] !== 'Administrator') {
    echo '<div class="alert alert-danger">Akses ditolak</div>';
    return;
}

$id = (int)$_GET['id'];
$q = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE UserID=$id");
$u = mysqli_fetch_assoc($q);
?>

<h4 class="fw-bold mb-3">✏ Edit Pengguna</h4>

<div class="card shadow-sm">
<div class="card-body">
<form method="post" action="actions/registrasi_process.php">
<input type="hidden" name="act" value="update">
<input type="hidden" name="id" value="<?= $id ?>">

<input name="username"
       class="form-control mb-2"
       value="<?= htmlspecialchars($u['Username']) ?>" required>

<input name="password" type="password"
       class="form-control mb-2"
       placeholder="Kosongkan jika tidak diubah">

<select name="role" class="form-select mb-3">
  <option <?= $u['Role']=='Petugas'?'selected':'' ?>>Petugas</option>
  <option <?= $u['Role']=='Administrator'?'selected':'' ?>>Administrator</option>
</select>

<button class="btn btn-primary">Update</button>
<a href="dashboard.php?modul=registrasi&aksi=tampil"
   class="btn btn-secondary">Batal</a>
</form>
</div>
</div>
