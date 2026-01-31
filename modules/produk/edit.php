<?php
$id = (int)($_GET['id'] ?? 0);
$q = mysqli_query($koneksi, "SELECT * FROM produk WHERE ProdukID=$id");
$data = mysqli_fetch_assoc($q);

if (!$data) {
  echo '<div class="alert alert-danger">Produk tidak ditemukan</div>';
  return;
}
?>

<h3 class="fw-bold mb-3">✏️ Edit Produk</h3>

<div class="card shadow-sm">
  <div class="card-body">
    <form method="post"
          action="actions/produk_process.php"
          enctype="multipart/form-data"
          class="row g-3">

      <input type="hidden" name="act" value="update">
      <input type="hidden" name="id" value="<?= $data['ProdukID'] ?>">

      <div class="col-md-2 text-center">
        <?php if ($data['Foto']): ?>
          <img src="/kasir-app/uploads/produk/<?= $data['Foto'] ?>"
               class="rounded-circle mb-2" width="80" height="80">
        <?php endif; ?>
      </div>

      <div class="col-md-3">
        <input name="nama" class="form-control"
               value="<?= htmlspecialchars($data['NamaProduk']) ?>" required>
      </div>

      <div class="col-md-2">
        <input name="harga" type="number" step="0.01"
               class="form-control" value="<?= $data['Harga'] ?>" required>
      </div>

      <div class="col-md-2">
        <input name="stok" type="number"
               class="form-control" value="<?= $data['Stok'] ?>" required>
      </div>

      <div class="col-md-3">
        <input type="file" name="foto" class="form-control">
        <small class="text-muted">Kosongkan jika tidak ganti</small>
      </div>

      <div class="col-md-12 text-end">
        <button class="btn btn-primary px-4">Update</button>
        <a href="dashboard.php?modul=produk&aksi=tampil"
           class="btn btn-secondary">Batal</a>
      </div>

    </form>
  </div>
</div>
