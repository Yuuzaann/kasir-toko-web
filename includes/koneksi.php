<?php
$db_host = '127.0.0.1';
$db_user = 'root';
$db_pass = '';
$db_name = 'kasir_db';

$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$koneksi) {
    die('Koneksi gagal: ' . mysqli_connect_error());
}
mysqli_set_charset($koneksi, 'utf8mb4');
?>
