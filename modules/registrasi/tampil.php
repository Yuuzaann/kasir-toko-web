<?php
if ($_SESSION['user']['Role'] !== 'Administrator') {
    echo '<div class="alert alert-danger">Akses ditolak</div>';
    return;
}

$res = mysqli_query($koneksi, "SELECT * FROM pengguna ORDER BY UserID DESC");
?>

<h4 class="fw-bold mb-3">👤 Manajemen Pengguna</h4>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'success'): ?>
<div class="alert alert-success alert-dismissible fade show">
  Berhasil diproses
  <button class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<a href="dashboard.php?modul=registrasi&aksi=tambah"
   class="btn btn-success btn-sm mb-3">+ Tambah Pengguna</a>

<div class="card shadow-sm">
<div class="card-body p-0">
<table class="table table-hover align-middle mb-0">
<thead class="table-light">
<tr>
  <th>#</th>
  <th>Username</th>
  <th>Role</th>
  <th width="150">Aksi</th>
</tr>
</thead>
<tbody>
<?php if (mysqli_num_rows($res) === 0): ?>
<tr><td colspan="4" class="text-center text-muted py-4">Kosong</td></tr>
<?php else: $no=1; while($u=mysqli_fetch_assoc($res)): ?>
<tr>
  <td><?= $no++ ?></td>
  <td><?= htmlspecialchars($u['Username']) ?></td>
  <td><?= $u['Role'] ?></td>
  <td>
    <a href="dashboard.php?modul=registrasi&aksi=edit&id=<?= $u['UserID'] ?>"
       class="btn btn-sm btn-warning">Edit</a>
    <a href="dashboard.php?modul=registrasi&aksi=hapus&id=<?= $u['UserID'] ?>"
       onclick="return confirm('Hapus user?')"
       class="btn btn-sm btn-danger">Hapus</a>
  </td>
</tr>
<?php endwhile; endif; ?>
</tbody>
</table>
</div>
</div>
