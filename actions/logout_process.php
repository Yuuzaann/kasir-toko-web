<?php
session_start();
session_unset();
session_destroy();
header('Location: /kasir-app/index.php');
exit;
?>
