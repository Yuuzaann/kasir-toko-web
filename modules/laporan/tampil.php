<?php
require_once __DIR__ . '/../../includes/koneksi.php';

// Ambil data penjualan
$res = mysqli_query($koneksi, "
    SELECT p.*, c.NamaPelanggan 
    FROM penjualan p 
    LEFT JOIN pelanggan c ON p.PelangganID = c.PelangganID 
    ORDER BY p.PenjualanID DESC
");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0">🧾 Laporan Penjualan</h4>
  <button class="btn btn-outline-primary btn-sm" onclick="window.print()">
    <i class="bi bi-printer"></i> Cetak
  </button>
</div>

<div class="card shadow-sm">
  <div class="card-body p-0">
    <!-- Table responsive -->
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light text-center">
          <tr>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Bayar</th>
            <th>Kembalian</th>
            <th>Pelanggan</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($res) === 0): ?>
            <tr>
              <td colspan="6" class="text-center text-muted py-4">
                Belum ada data penjualan
              </td>
            </tr>
          <?php else: while($r = mysqli_fetch_assoc($res)): ?>
            <tr>
              <td class="text-center"><?= $r['PenjualanID'] ?></td>
              <td><?= $r['TanggalPenjualan'] ?></td>
              <td>Rp<?= number_format($r['TotalHarga'],0,',','.') ?></td>
              <td>Rp<?= number_format($r['Bayar'],0,',','.') ?></td>
              <td>Rp<?= number_format($r['Kembalian'],0,',','.') ?></td>
              <td><?= htmlspecialchars($r['NamaPelanggan'] ?? 'Umum') ?></td>
            </tr>
          <?php endwhile; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- CSS Print & Mobile -->
<style>
/* Print Style */
@media print {
  body {
    background: white;
    font-size: 13px;
  }
  button {
    display: none !important;
  }
  h4 {
    text-align: center;
    margin-bottom: 20px;
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

/* Mobile adjustments */
@media (max-width: 576px) {
  .table th, .table td {
    font-size: 12px;
    padding: 3px 5px;
  }
  .table-responsive {
    overflow-x: auto;
  }
}
</style>

<!-- Bootstrap Icons (opsional) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
