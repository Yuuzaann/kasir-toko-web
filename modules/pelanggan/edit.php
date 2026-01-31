<?php
$id = (int) ($_GET['id'] ?? 0);
$r  = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE PelangganID=$id LIMIT 1");

if (!$r || mysqli_num_rows($r) === 0) {
    echo '<div class="alert alert-danger">Data pelanggan tidak ditemukan</div>';
    return;
}

$p = mysqli_fetch_assoc($r);

$fotoDir = __DIR__ . '/../../uploads/pelanggan/';
$fotoUrl = '/kasir-app/uploads/pelanggan/';
?>

<h4 class="fw-bold mb-3">✏️ Edit Pelanggan</h4>

<div class="card shadow-sm">
  <div class="card-body">

    <form method="post"
          action="/kasir-app/actions/pelanggan_process.php"
          enctype="multipart/form-data"
          class="row g-3 align-items-center">

      <input type="hidden" name="act" value="update">
      <input type="hidden" name="id" value="<?= $p['PelangganID'] ?>">

      <div class="col-md-2 text-center">
        <?php if ($p['Foto'] && file_exists($fotoDir.$p['Foto'])): ?>
          <img src="<?= $fotoUrl.$p['Foto'] ?>"
               class="rounded-circle mb-2" width="80" height="80">
          <small class="text-muted d-block">Foto lama</small>
        <?php else: ?>
          <div class="rounded-circle bg-secondary opacity-25 mx-auto mb-2"
               style="width:80px;height:80px"></div>
        <?php endif; ?>
      </div>

      <div class="col-md-3">
        <input name="nama" class="form-control" required
               value="<?= htmlspecialchars($p['NamaPelanggan']) ?>">
      </div>

      <div class="col-md-3">
        <input name="telp" class="form-control"
               value="<?= htmlspecialchars($p['NomorTelepon']) ?>">
      </div>

      <div class="col-md-3">
        <input name="alamat" class="form-control"
               value="<?= htmlspecialchars($p['Alamat']) ?>">
      </div>

      <div class="col-md-3">
        <input type="file" name="foto" class="form-control">
        <small class="text-muted">Kosongkan jika tidak diganti</small>
      </div>

      <div class="col-md-12 text-end">
        <button class="btn btn-primary px-4">Update</button>
        <a href="dashboard.php?modul=pelanggan&aksi=tampil"
           class="btn btn-secondary ms-1">Batal</a>
      </div>

    </form>

  </div>
</div>
