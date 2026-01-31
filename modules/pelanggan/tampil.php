<?php
require_once __DIR__ . '/../../includes/koneksi.php';

$res = mysqli_query($koneksi, "SELECT * FROM pelanggan ORDER BY PelangganID DESC");

$fotoDir = __DIR__ . '/../../uploads/pelanggan/';
$fotoUrl = '/kasir-app/uploads/pelanggan/';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0">👥 Pelanggan</h4>
  <a href="dashboard.php?modul=pelanggan&aksi=tambah"
     class="btn btn-success btn-sm">+ Tambah Pelanggan</a>
</div>

<div class="card shadow-sm">
  <div class="card-body p-0">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Foto</th>
          <th>Nama</th>
          <th>Telp</th>
          <th>Alamat</th>
          <th width="160">Aksi</th>
        </tr>
      </thead>
      <tbody>

      <?php if (mysqli_num_rows($res) === 0): ?>
        <tr>
          <td colspan="6" class="text-center text-muted py-4">
            Belum ada pelanggan
          </td>
        </tr>
      <?php else: $no=1; while($p=mysqli_fetch_assoc($res)): ?>

        <tr>
          <td><?= $no++ ?></td>
          <td>
            <?php if ($p['Foto'] && file_exists($fotoDir.$p['Foto'])): ?>
              <img src="<?= $fotoUrl.$p['Foto'] ?>"
                   class="rounded-circle" width="40" height="40">
            <?php else: ?>
              <div class="rounded-circle bg-secondary opacity-25"
                   style="width:40px;height:40px"></div>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($p['NamaPelanggan']) ?></td>
          <td><?= htmlspecialchars($p['NomorTelepon']) ?></td>
          <td><?= htmlspecialchars($p['Alamat']) ?></td>
          <td>
            <a href="dashboard.php?modul=pelanggan&aksi=edit&id=<?= $p['PelangganID'] ?>"
               class="btn btn-sm btn-warning">Edit</a>

            <a href="dashboard.php?modul=pelanggan&aksi=hapus&id=<?= $p['PelangganID'] ?>"
               onclick="return confirm('Hapus pelanggan ini?')"
               class="btn btn-sm btn-danger">Hapus</a>
          </td>
        </tr>

      <?php endwhile; endif; ?>

      </tbody>
    </table>
  </div>
</div>
