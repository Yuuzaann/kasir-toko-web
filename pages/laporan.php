<?php
require_once __DIR__ . '/../includes/header.php';
require_login();
?>
<h3 class="d-flex justify-content-between align-items-center">
  Laporan Penjualan
  <button class="btn btn-outline-primary btn-sm" onclick="window.print()">
    <i class="bi bi-printer"></i> Cetak Laporan
  </button>
</h3>

<!-- Header toko (muncul pas print) -->
<div class="mb-3 d-none d-print-block text-center">
  <h5 class="mb-0">Kasir-App</h5>
  <small class="text-muted">Dicetak pada: <?=date('d/m/Y H:i')?> WIB</small>
</div>

<div class="card mt-3">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-sm table-bordered align-middle">
        <thead class="table-light">
          <tr class="text-center">
            <th>ID</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Bayar</th>
            <th>Kembalian</th>
            <th>Pelanggan</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $res = mysqli_query($koneksi, 'SELECT p.*, c.NamaPelanggan FROM penjualan p LEFT JOIN pelanggan c ON p.PelangganID = c.PelangganID ORDER BY p.PenjualanID DESC');
          if (mysqli_num_rows($res) > 0):
            while ($r = mysqli_fetch_assoc($res)):
          ?>
            <tr>
              <td class="text-center"><?= $r['PenjualanID'] ?></td>
              <td><?= $r['TanggalPenjualan'] ?></td>
              <td>Rp<?= number_format($r['TotalHarga'],0,',','.') ?></td>
              <td>Rp<?= number_format($r['Bayar'],0,',','.') ?></td>
              <td>Rp<?= number_format($r['Kembalian'],0,',','.') ?></td>
              <td><?= htmlspecialchars($r['NamaPelanggan'] ?? 'Umum') ?></td>
            </tr>
          <?php
            endwhile;
          else:
          ?>
            <tr><td colspan="6" class="text-center text-muted">Belum ada data penjualan.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>

<!-- CSS Print -->
<style>
@media print {
  body {
    background: white;
    font-size: 13px;
  }
  .sidebar, .toggle-btn, h3 button, footer {
    display: none !important; /* sembunyikan sidebar, tombol, footer waktu print */
  }
  .content {
    margin: 0 !important;
    padding: 0 !important;
  }
  h3 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 18px;
  }
  .table {
    font-size: 13px;
    border: 1px solid #000;
  }
  .table th, .table td {
    border: 1px solid #000 !important;
    padding: 4px 6px;
  }
}
</style>
