<?php
require_once __DIR__ . '/../includes/header.php';
require_login();

// Ambil semua data pelanggan
$pelanggan = [];
$res = mysqli_query($koneksi, 'SELECT * FROM pelanggan ORDER BY PelangganID DESC');
while ($row = mysqli_fetch_assoc($res)) $pelanggan[] = $row;

// Cek mode edit
$editing = null;
if (isset($_GET['edit'])) {
    $eid = (int) $_GET['edit'];
    $r = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE PelangganID = $eid LIMIT 1");
    if ($r && mysqli_num_rows($r)) $editing = mysqli_fetch_assoc($r);
}

// path foto pelanggan
$fotoDir = __DIR__ . '/../uploads/pelanggan/';
$fotoUrl = '/kasir-app/uploads/pelanggan/';
?>

<h3>Pelanggan</h3>

<div class="card mb-3">
  <div class="card-body">
    <?php if ($editing): ?>
      <form method="post" action="/kasir-app/actions/pelanggan_process.php" enctype="multipart/form-data" class="row g-2 align-items-center">
        <input type="hidden" name="act" value="update">
        <input type="hidden" name="id" value="<?= $editing['PelangganID'] ?>">

        <!-- Preview Foto Lama -->
        <div class="col-md-2 text-center">
          <?php if (!empty($editing['Foto']) && file_exists($fotoDir . $editing['Foto'])): ?>
            <img src="<?= $fotoUrl . htmlspecialchars($editing['Foto']) ?>"
                 alt="Foto Pelanggan"
                 class="rounded-circle mb-2"
                 width="80" height="80">
            <div><small class="text-muted">Foto lama</small></div>
          <?php else: ?>
            <div class="rounded-circle bg-secondary d-inline-block mb-2"
                 style="width:80px;height:80px;"></div>
            <div><small class="text-muted">Belum ada foto</small></div>
          <?php endif; ?>
        </div>

        <div class="col-md-2">
          <input name="nama" class="form-control" placeholder="Nama pelanggan" required
                 value="<?= htmlspecialchars($editing['NamaPelanggan']) ?>">
        </div>

        <div class="col-md-3">
          <input name="telp" class="form-control" placeholder="Telepon"
                 value="<?= htmlspecialchars($editing['NomorTelepon']) ?>">
        </div>

        <div class="col-md-3">
          <input name="alamat" class="form-control" placeholder="Alamat"
                 value="<?= htmlspecialchars($editing['Alamat']) ?>">
        </div>

        <div class="col-md-2">
          <input type="file" name="foto" class="form-control">
          <small class="text-muted">Pilih untuk ganti foto</small>
        </div>

        <div class="col-md-12 text-end">
          <button class="btn btn-primary">Simpan</button>
          <a href="/kasir-app/pages/pelanggan.php" class="btn btn-secondary">Batal</a>
        </div>
      </form>

    <?php else: ?>

      <form method="post" action="/kasir-app/actions/pelanggan_process.php" enctype="multipart/form-data" class="row g-2">
        <input type="hidden" name="act" value="create">

        <div class="col-md-2">
          <input name="nama" class="form-control" placeholder="Nama pelanggan" required>
        </div>

        <div class="col-md-3">
          <input name="telp" class="form-control" placeholder="Telepon">
        </div>

        <div class="col-md-3">
          <input name="alamat" class="form-control" placeholder="Alamat">
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

<!-- Tabel daftar pelanggan -->
<div class="card">
  <div class="card-body">
    <table class="table table-sm align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Foto</th>
          <th>Nama</th>
          <th>Telp</th>
          <th>Alamat</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($pelanggan) === 0): ?>
          <tr>
            <td colspan="6" class="text-center text-muted">Belum ada pelanggan</td>
          </tr>
        <?php else: $no = 1; foreach ($pelanggan as $pl): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td>
              <?php if (!empty($pl['Foto']) && file_exists($fotoDir . $pl['Foto'])): ?>
                <a href="<?= $fotoUrl . htmlspecialchars($pl['Foto']) ?>" target="_blank">
                  <img src="<?= $fotoUrl . htmlspecialchars($pl['Foto']) ?>"
                       class="rounded-circle"
                       width="40" height="40">
                </a>
              <?php else: ?>
                <div class="rounded-circle bg-secondary d-inline-block"
                     style="width:40px;height:40px;"></div>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($pl['NamaPelanggan']) ?></td>
            <td><?= htmlspecialchars($pl['NomorTelepon']) ?></td>
            <td><?= htmlspecialchars($pl['Alamat']) ?></td>
            <td>
              <a href="/kasir-app/pages/pelanggan.php?edit=<?= $pl['PelangganID'] ?>" class="btn btn-sm btn-warning">Edit</a>
              <form method="post" action="/kasir-app/actions/pelanggan_process.php" style="display:inline">
                <input type="hidden" name="act" value="delete">
                <input type="hidden" name="id" value="<?= $pl['PelangganID'] ?>">
                <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus pelanggan?')">Hapus</button>
              </form>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('input[name="telp"]').forEach(input => {

    const warning = document.createElement('small');
    warning.classList.add('text-danger');
    warning.style.display = 'none';
    warning.textContent = 'Nomor tlp hanya boleh angka!';
    input.insertAdjacentElement('afterend', warning);

    input.addEventListener('input', () => {
      if (/[^0-9]/.test(input.value)) {
        warning.style.display = 'block';
      } else {
        warning.style.display = 'none';
      }
      input.value = input.value.replace(/[^0-9]/g, '');
    });

    input.addEventListener('paste', (e) => {
      let pasteData = (e.clipboardData || window.clipboardData).getData('text');
      if (/[^0-9]/.test(pasteData)) {
        e.preventDefault();
        warning.style.display = 'block';
      }
    });
  });
});
</script>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
