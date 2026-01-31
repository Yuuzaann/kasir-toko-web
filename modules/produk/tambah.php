<h3 class="fw-bold mb-3">➕ Tambah Produk</h3>

<div class="card shadow-sm">
  <div class="card-body">
    <form method="post"
          action="actions/produk_process.php"
          enctype="multipart/form-data"
          class="row g-3">

      <input type="hidden" name="act" value="create">

      <div class="col-md-4">
        <input name="nama" class="form-control" placeholder="Nama Produk" required>
      </div>

      <div class="col-md-3">
        <input name="harga" type="number" step="0.01"
               class="form-control" placeholder="Harga" required>
      </div>

      <div class="col-md-2">
        <input name="stok" type="number"
               class="form-control" placeholder="Stok" required>
      </div>

      <div class="col-md-3">
        <input type="file" name="foto" class="form-control">
      </div>

      <div class="col-md-12 text-end">
        <button class="btn btn-success px-4">Simpan</button>
        <a href="dashboard.php?modul=produk&aksi=tampil"
           class="btn btn-secondary">Batal</a>
      </div>

    </form>
  </div>
</div>
