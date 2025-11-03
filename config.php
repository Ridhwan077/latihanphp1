<?php
$host = "localhost";     // server database
$user = "root";          // username MySQL
$pass = "";              // password MySQL (kosong di XAMPP)
$db   = "public_blog";       // nama database kamu

// Membuat koneksi
$conn = new mysqli($host, $user, $pass, $db);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}
?>
