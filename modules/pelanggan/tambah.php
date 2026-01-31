<h4 class="fw-bold mb-3">➕ Tambah Pelanggan</h4>

<div class="card shadow-sm">
  <div class="card-body">

    <form method="post"
          action="/kasir-app/actions/pelanggan_process.php"
          enctype="multipart/form-data"
          class="row g-3">

      <input type="hidden" name="act" value="create">

      <div class="col-md-4">
        <input name="nama" class="form-control" placeholder="Nama pelanggan" required>
      </div>

      <div class="col-md-4">
        <input name="telp" class="form-control" placeholder="Telepon">
      </div>

      <div class="col-md-4">
        <input name="alamat" class="form-control" placeholder="Alamat">
      </div>

      <div class="col-md-4">
        <input type="file" name="foto" class="form-control">
      </div>

      <div class="col-md-12 text-end">
        <button class="btn btn-success px-4">Simpan</button>
        <a href="dashboard.php?modul=pelanggan&aksi=tampil"
           class="btn btn-secondary ms-1">Batal</a>
      </div>

    </form>

  </div>
</div>
