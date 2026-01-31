<?php
if ($_SESSION['user']['Role'] !== 'Administrator') return;

$id = (int)$_GET['id'];
mysqli_query($koneksi, "DELETE FROM pengguna WHERE UserID=$id");

header("Location: dashboard.php?modul=registrasi&aksi=tampil&msg=success");
exit;
