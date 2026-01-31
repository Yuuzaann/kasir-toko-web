<?php
unset($_SESSION['cart']);
header('Location: dashboard.php?modul=penjualan&aksi=tampil');
exit;
