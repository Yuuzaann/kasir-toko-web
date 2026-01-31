<?php
// =============================
// Koneksi Database (PROSEDUR)
// =============================
// Variabel koneksi
$db_host = '127.0.0.1';
$db_user = 'root';
$db_pass = '';
$db_name = 'kasir_db'; // <-- sesuai permintaan

$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
// Percabangan: jika gagal koneksi -> hentikan dan tampilkan pesan
if (!$koneksi) {
    die('Koneksi gagal: ' . mysqli_connect_error());
}
mysqli_set_charset($koneksi, 'utf8mb4');
?>
