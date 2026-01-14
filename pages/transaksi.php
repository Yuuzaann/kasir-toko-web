<?php
require_once __DIR__ . '/../includes/header.php';
require_login();

// Pastikan session aktif
if (session_status() === PHP_SESSION_NONE) session_start();

// ========== TAMBAH KE KERANJANG ==========
if (isset($_POST['add_cart'])) {
    $pid = (int) $_POST['produk_id'];
    $qty = max(1, (int) $_POST['qty']);
    $_SESSION['cart'][$pid] = ($_SESSION['cart'][$pid] ?? 0) + $qty;
    header('Location: /kasir-app/pages/transaksi.php'); 
    exit;
}

// ========== KOSONGKAN KERANJANG ==========
if (isset($_POST['clear_cart'])) {
    unset($_SESSION['cart']);
    header('Location: /kasir-app/pages/transaksi.php?msg=clear');
    exit;
}

// Ambil list produk
$produk_list = [];
$res = mysqli_query($koneksi, 'SELECT * FROM produk WHERE Stok > 0');
while ($r = mysqli_fetch_assoc($res)) $produk_list[] = $r;

// Hitung isi keranjang
$cart_items = [];
$total = 0.0;
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $res2 = mysqli_query($koneksi, "SELECT * FROM produk WHERE ProdukID IN ($ids)");
    while ($r = mysqli_fetch_assoc($res2)) {
        $r['qty'] = $_SESSION['cart'][$r['ProdukID']];
        $r['subtotal'] = $r['qty'] * $r['Harga'];
        $total += $r['subtotal'];
        $cart_items[] = $r;
    }
}
?>

<h3>Transaksi</h3>

<!-- Notifikasi -->
<?php
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'clear') { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> Keranjang berhasil dikosongkan.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } elseif ($_GET['msg'] === 'success') {
        $kembalian = (float) ($_GET['kembalian'] ?? 0); ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> Transaksi berhasil! Kembalian: Rp<?= number_format($kembalian,0,',','.') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } elseif ($_GET['msg'] === 'bayar_kurang') { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> Jumlah bayar kurang dari total!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
<?php } } ?>

<div class="row">
  <!-- FORM TAMBAH PRODUK -->
  <div class="col-md-5">
    <div class="card mb-3">
      <div class="card-body">
        <form method="post">
          <div class="mb-2">
            <select name="produk_id" class="form-select" required>
              <?php foreach($produk_list as $p): ?>
                <option value="<?= $p['ProdukID'] ?>">
                  <?= htmlspecialchars($p['NamaProduk']) ?> (<?= $p['Stok'] ?> pcs) - Rp<?= number_format($p['Harga'],0,',','.') ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-2">
            <input type="number" name="qty" value="1" class="form-control" min="1">
          </div>
          <div class="d-grid gap-2">
            <button name="add_cart" class="btn btn-primary">
              <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
            </button>
            <button type="submit" name="clear_cart" class="btn btn-danger" onclick="return confirm('Yakin ingin mengosongkan keranjang?')">
              <i class="bi bi-trash3"></i> Kosongkan Keranjang
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- KERANJANG & CHECKOUT -->
  <div class="col-md-7">
    <div class="card">
      <div class="card-body">
        <h5>Keranjang</h5>
        <table class="table table-sm">
          <thead><tr><th>Nama</th><th>Qty</th><th>Subtotal</th></tr></thead>
          <tbody>
            <?php if (count($cart_items) === 0): ?>
              <tr><td colspan="3" class="text-center text-muted">Keranjang kosong</td></tr>
            <?php else: foreach($cart_items as $it): ?>
              <tr>
                <td><?= htmlspecialchars($it['NamaProduk']) ?></td>
                <td><?= $it['qty'] ?></td>
                <td>Rp<?= number_format($it['subtotal'],0,',','.') ?></td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>

        <div class="text-end mb-2">
          <strong>Total: Rp<?= number_format($total,0,',','.') ?></strong>
        </div>

        <!-- FORM CHECKOUT -->
        <form method="post" action="/kasir-app/actions/transaksi_process.php" id="checkoutForm">
          <input type="hidden" name="total" value="<?= $total ?>">

          <div class="mt-2">
            <select name="pelanggan_id" class="form-select">
              <option value="">Umum</option>
              <?php 
              $pl = mysqli_query($koneksi, 'SELECT * FROM pelanggan'); 
              while($pp = mysqli_fetch_assoc($pl)): ?>
                <option value="<?= $pp['PelangganID'] ?>"><?= htmlspecialchars($pp['NamaPelanggan']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="mt-2">
            <input type="number" name="bayar" id="bayarInput" class="form-control" placeholder="Jumlah Bayar" min="<?= $total ?>" required>
          </div>

          <div class="mt-2">
            <input type="text" id="kembalianOutput" class="form-control" placeholder="Kembalian" readonly>
          </div>

          <div class="mt-2 d-grid">
            <button name="checkout" class="btn btn-success">
              <i class="bi bi-cash-coin"></i> Proses Bayar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Script Realtime Kembalian -->
<script>
const total = <?= $total ?>;
const bayarInput = document.getElementById('bayarInput');
const kembalianOutput = document.getElementById('kembalianOutput');

bayarInput.addEventListener('input', () => {
    let bayar = parseFloat(bayarInput.value) || 0;
    let kembalian = bayar - total;
    kembalianOutput.value = kembalian >= 0 ? 'Rp' + kembalian.toLocaleString('id-ID') : 'Rp0';
});
</script>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
