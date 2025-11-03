<?php
session_start(); // aktifkan session
session_destroy(); // hapus semua data session

// arahkan kembali ke halaman login
header("Location: index.php");
exit();
?>
