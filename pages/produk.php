<?php
require_once __DIR__ . '/../includes/header.php';
require_login();

$produk = [];
$res = mysqli_query($koneksi, 'SELECT * FROM produk ORDER BY ProdukID DESC');
while ($row = mysqli_fetch_assoc($res)) {
    $produk[] = $row;
}

$editing = null;
if (isset($_GET['edit'])) {
    $eid = (int) $_GET['edit'];
    $r = mysqli_query($koneksi, "SELECT * FROM produk WHERE ProdukID = $eid LIMIT 1");
    if ($r && mysqli_num_rows($r)) {
        $editing = mysqli_fetch_assoc($r);
    }
}
?>
<h3>Produk</h3>

<div class="card mb-3">
  <div class="card-body">
    <?php if ($editing): ?>
      <!-- Form Edit Produk -->
      <form method="post" action="/kasir-app/actions/produk_process.php" enctype="multipart/form-data" class="row g-2">
        <input type="hidden" name="act" value="update">
        <input type="hidden" name="id" value="<?= $editing['ProdukID'] ?>">

        <div class="col-md-2 text-center">
          <?php if (!empty($editing['Foto']) && file_exists(__DIR__ . '/../uploads/produk/' . $editing['Foto'])): ?>
            <img src="/kasir-app/uploads/produk/<?= htmlspecialchars($editing['Foto']) ?>" 
                 alt="Foto Produk" 
                 class="rounded-circle mb-2" 
                 width="80" height="80">
            <div><small class="text-muted">Foto lama</small></div>
          <?php else: ?>
            <div class="rounded-circle bg-secondary d-inline-block mb-2" style="width:80px;height:80px;"></div>
            <div><small class="text-muted">Belum ada foto</small></div>
          <?php endif; ?>
        </div>

        <div class="col-md-3">
          <input name="nama" class="form-control" placeholder="Nama produk" required value="<?= htmlspecialchars($editing['NamaProduk']) ?>">
        </div>
        <div class="col-md-2">
          <input name="harga" type="number" step="0.01" class="form-control" placeholder="Harga" required value="<?= $editing['Harga'] ?>">
        </div>
        <div class="col-md-2">
          <input name="stok" type="number" class="form-control" placeholder="Stok" required value="<?= $editing['Stok'] ?>">
        </div>
        <div class="col-md-3">
          <input type="file" name="foto" class="form-control">
          <small class="text-muted">Pilih untuk ganti foto</small>
        </div>
        <div class="col-md-1 text-end">
          <button class="btn btn-primary">Simpan</button>
        </div>
        <div class="col-md-1 text-end">
          <a href="/kasir-app/pages/produk.php" class="btn btn-secondary">Batal</a>
        </div>
      </form>
    <?php else: ?>
      <!-- Form Tambah Produk -->
      <form method="post" action="/kasir-app/actions/produk_process.php" enctype="multipart/form-data" class="row g-2">
        <input type="hidden" name="act" value="create">
        <div class="col-md-3">
          <input name="nama" class="form-control" placeholder="Nama produk" required>
        </div>
        <div class="col-md-3">
          <input name="harga" type="number" step="0.01" class="form-control" placeholder="Harga" required>
        </div>
        <div class="col-md-2">
          <input name="stok" type="number" class="form-control" placeholder="Stok" required>
        </div>
        <div class="col-md-2">
          <input type="file" name="foto" class="form-control">
        </div>
        <div class="col-md-2 text-end">
          <button class="btn btn-success">Tambah</button>
        </div>
      </form>
    <?php endif; ?>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <table class="table table-sm align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Foto</th>
          <th>Nama</th>
          <th>Harga</th>
          <th>Stok</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($produk) === 0): ?>
          <tr><td colspan="6" class="text-center text-muted">Belum ada produk</td></tr>
        <?php else: $no=1; foreach($produk as $p): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td>
              <?php if (!empty($p['Foto']) && file_exists(__DIR__ . '/../uploads/produk/' . $p['Foto'])): ?>
                <a href="/kasir-app/uploads/produk/<?= htmlspecialchars($p['Foto']) ?>" target="_blank">
                  <img src="/kasir-app/uploads/produk/<?= htmlspecialchars($p['Foto']) ?>" class="rounded-circle" width="40" height="40">
                </a>
              <?php else: ?>
                <div class="rounded-circle bg-secondary d-inline-block" style="width:40px;height:40px;"></div>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($p['NamaProduk']) ?></td>
            <td>Rp<?= number_format($p['Harga'],2,',','.') ?></td>
            <td><?= $p['Stok'] ?></td>
            <td>
              <a href="/kasir-app/pages/produk.php?edit=<?= $p['ProdukID'] ?>" class="btn btn-sm btn-warning">Edit</a>
              <form method="post" action="/kasir-app/actions/produk_process.php" style="display:inline">
                <input type="hidden" name="act" value="delete">
                <input type="hidden" name="id" value="<?= $p['ProdukID'] ?>">
                <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk?')">Hapus</button>
              </form>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
