<?php session_start();
include 'config.php';

// Pastikan user sudah login
if (!isset($_SESSION['member_id'])) {
    echo "<p>⚠️ Anda harus login terlebih dahulu.</p>";
    header("Location: index.php");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <?php include 'includes/head.php'; ?>
</head>
<body>
  <?php include 'includes/menu.php'; ?>

<h2>Buat Post Baru</h2>
<form action="post_process.php" method="POST" enctype="multipart/form-data">
  <label>Judul:</label><br>
  <input type="text" name="title" required><br><br>

  <label>Deskripsi:</label><br>
  <textarea name="content" required></textarea><br><br>

  <label>Upload Gambar:</label><br>
  <input type="file" name="image" accept="image/*" required><br><br>

  <button type="submit">Kirim</button>
</form>

  <?php include 'includes/footer.php'; ?>
</body>
</html>
