<?php
$pid = (int)$_POST['produk_id'];
$qty = max(1, (int)$_POST['qty']);

$_SESSION['cart'][$pid] = ($_SESSION['cart'][$pid] ?? 0) + $qty;

header('Location: dashboard.php?modul=penjualan&aksi=tampil');
exit;
