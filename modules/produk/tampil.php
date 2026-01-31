<?php
$res = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY ProdukID DESC");

echo '
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="fw-bold mb-0">📦 Produk</h3>
  <a href="dashboard.php?modul=produk&aksi=tambah"
     class="btn btn-success btn-sm">+ Tambah Produk</a>
</div>

<div class="card shadow-sm">
  <div class="card-body p-0">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Foto</th>
          <th>Nama</th>
          <th>Harga</th>
          <th>Stok</th>
          <th width="160">Aksi</th>
        </tr>
      </thead>
      <tbody>
';

if (mysqli_num_rows($res) === 0) {

  echo '
    <tr>
      <td colspan="6" class="text-center text-muted py-4">
        Belum ada produk
      </td>
    </tr>
  ';

} else {
  $no = 1;
  while ($p = mysqli_fetch_assoc($res)) {

    $fotoPath = __DIR__ . '/../../uploads/produk/' . $p['Foto'];
    $fotoHtml = file_exists($fotoPath) && $p['Foto']
      ? '<img src="/kasir-app/uploads/produk/'.$p['Foto'].'" class="rounded-circle" width="40" height="40">'
      : '<div class="rounded-circle bg-secondary opacity-25" style="width:40px;height:40px"></div>';

    echo '
    <tr>
      <td>'.$no++.'</td>
      <td>'.$fotoHtml.'</td>
      <td>'.htmlspecialchars($p['NamaProduk']).'</td>
      <td>Rp'.number_format($p['Harga'],2,',','.').'</td>
      <td>'.$p['Stok'].'</td>
      <td>
        <a href="dashboard.php?modul=produk&aksi=edit&id='.$p['ProdukID'].'"
           class="btn btn-sm btn-warning">Edit</a>

        <a href="dashboard.php?modul=produk&aksi=hapus&id='.$p['ProdukID'].'"
           onclick="return confirm(\'Hapus produk ini?\')"
           class="btn btn-sm btn-danger">Hapus</a>
      </td>
    </tr>
    ';
  }
}

echo '
      </tbody>
    </table>
  </div>
</div>
';
