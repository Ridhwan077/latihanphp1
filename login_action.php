<?php
include 'config.php';
// Koneksi ke database
//$conn = new mysqli("localhost", "root", "", "public_blog");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil input dari form
$username = $_POST['username'];
$password = $_POST['password'];

// Query untuk mencari user berdasarkan username dan password
$sql = "SELECT * FROM members WHERE username='$username' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Jika ditemukan, berarti login berhasil
    session_start();
    $row = $result->fetch_assoc();
    $_SESSION['member_id'] = $row['member_id'];
    $_SESSION['username'] = $row['username'];

    echo "Login berhasil! Selamat datang, " . $_SESSION['username'];
    // arahkan ke halaman lain, misalnya dashboard
    header("Location: dashboard.php");
} else {
    echo "<script>
        alert('Username atau password salah!');
        window.location.href = 'index.php';
    </script>";
}

$conn->close();
?>
