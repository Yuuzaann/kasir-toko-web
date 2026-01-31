<?php
$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    $_POST['act'] = 'delete';
    $_POST['id']  = $id;
    require __DIR__ . '/../../actions/pelanggan_process.php';
}
