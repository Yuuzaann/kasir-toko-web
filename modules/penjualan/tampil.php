<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../includes/koneksi.php';

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
?>

<!-- ================== NOTIFIKASI (FIXED / TOAST) ================== -->
<?php if (isset($_GET['msg'])): ?>
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index:1080">

  <?php if ($_GET['msg'] === 'success'): ?>
    <div class="toast show text-bg-success border-0 mb-2">
      <div class="d-flex">
        <div class="toast-body">
          <i class="bi bi-check-circle-fill me-1"></i>
          <strong>Pembayaran berhasil!</strong><br>
          Kembalian:
          <b>Rp<?= number_format($_GET['kembalian'] ?? 0, 0, ',', '.') ?></b>
        </div>
        <button class="btn-close btn-close-white me-2 m-auto"
                data-bs-dismiss="toast"></button>
      </div>
    </div>

  <?php elseif ($_GET['msg'] === 'bayar_kurang'): ?>
    <div class="toast show text-bg-danger border-0 mb-2">
      <div class="d-flex">
        <div class="toast-body">
          <i class="bi bi-exclamation-triangle-fill me-1"></i>
          Jumlah bayar kurang dari total!
        </div>
        <button class="btn-close btn-close-white me-2 m-auto"
                data-bs-dismiss="toast"></button>
      </div>
    </div>

  <?php elseif ($_GET['msg'] === 'cart_empty'): ?>
    <div class="toast show text-bg-warning border-0 mb-2">
      <div class="d-flex">
        <div class="toast-body">
          <i class="bi bi-cart-x-fill me-1"></i>
          Keranjang masih kosong.
        </div>
        <button class="btn-close me-2 m-auto"
                data-bs-dismiss="toast"></button>
      </div>
    </div>
  <?php endif; ?>

</div>
<?php endif; ?>

<?php
/* ================== PRODUK ================== */
$produk = [];
$q = mysqli_query($koneksi, "SELECT * FROM produk WHERE Stok > 0");
while ($r = mysqli_fetch_assoc($q)) $produk[] = $r;

/* ================== KERANJANG ================== */
$items = [];
$total = 0;

if ($_SESSION['cart']) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $res = mysqli_query($koneksi, "SELECT * FROM produk WHERE ProdukID IN ($ids)");
    while ($p = mysqli_fetch_assoc($res)) {
        $qty = $_SESSION['cart'][$p['ProdukID']];
        $sub = $qty * $p['Harga'];
        $total += $sub;

        $p['qty'] = $qty;
        $p['subtotal'] = $sub;
        $items[] = $p;
    }
}
?>

<h3 class="fw-bold mb-3">💰 Penjualan</h3>

<div class="row">
<!-- ================== FORM TAMBAH ================== -->
<div class="col-md-5">
  <div class="card shadow-sm">
    <div class="card-body">
      <form method="post"
            action="dashboard.php?modul=penjualan&aksi=tambah">

        <select name="produk_id" class="form-select mb-2" required>
          <?php foreach ($produk as $p): ?>
            <option value="<?= $p['ProdukID'] ?>">
              <?= htmlspecialchars($p['NamaProduk']) ?>
              - Rp<?= number_format($p['Harga'],0,',','.') ?>
            </option>
          <?php endforeach; ?>
        </select>

        <input type="number" name="qty" value="1" min="1"
               class="form-control mb-2">

        <button class="btn btn-primary w-100">
          ➕ Tambah ke Keranjang
        </button>
      </form>

      <a href="dashboard.php?modul=penjualan&aksi=hapus"
         class="btn btn-outline-danger w-100 mt-2"
         onclick="return confirm('Kosongkan keranjang?')">
        🗑 Kosongkan Keranjang
      </a>
    </div>
  </div>
</div>

<!-- ================== KERANJANG & BAYAR ================== -->
<div class="col-md-7">
  <div class="card shadow-sm">
    <div class="card-body">

      <table class="table table-sm">
        <thead>
          <tr>
            <th>Produk</th>
            <th width="70">Qty</th>
            <th class="text-end">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!$items): ?>
            <tr>
              <td colspan="3" class="text-center text-muted">
                Keranjang kosong
              </td>
            </tr>
          <?php else: foreach ($items as $i): ?>
            <tr>
              <td><?= htmlspecialchars($i['NamaProduk']) ?></td>
              <td><?= $i['qty'] ?></td>
              <td class="text-end">
                Rp<?= number_format($i['subtotal'],0,',','.') ?>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>

      <div class="text-end fw-bold mb-2">
        Total: Rp<?= number_format($total,0,',','.') ?>
      </div>

      <?php if ($total > 0): ?>
      <form method="post"
            action="dashboard.php?modul=penjualan&aksi=checkout">

        <input type="hidden" name="total" value="<?= $total ?>">

        <select name="pelanggan_id" class="form-select mb-2">
          <option value="">Umum</option>
          <?php
          $pl = mysqli_query($koneksi, "SELECT * FROM pelanggan");
          while ($p = mysqli_fetch_assoc($pl)):
          ?>
            <option value="<?= $p['PelangganID'] ?>">
              <?= htmlspecialchars($p['NamaPelanggan']) ?>
            </option>
          <?php endwhile; ?>
        </select>

        <input type="number" name="bayar" id="bayar"
               class="form-control mb-2"
               placeholder="Jumlah bayar" required>

        <input type="text" id="kembali"
               class="form-control mb-2"
               placeholder="Kembalian" readonly>

        <button class="btn btn-success w-100">
          💸 Bayar
        </button>
      </form>
      <?php endif; ?>

    </div>
  </div>
</div>
</div>

<!-- ================== SCRIPT ================== -->
<script>
const total = <?= $total ?>;
const bayar = document.getElementById('bayar');
const kembali = document.getElementById('kembali');

bayar?.addEventListener('input', () => {
  let k = bayar.value - total;
  kembali.value = k >= 0
    ? 'Rp' + k.toLocaleString('id-ID')
    : 'Rp0';
});

// auto hide toast
setTimeout(() => {
  document.querySelectorAll('.toast').forEach(t => {
    t.classList.remove('show');
  });
}, 3000);
</script>
