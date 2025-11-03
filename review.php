<!DOCTYPE html>
<html lang="en">
<head>
  <?php include 'includes/head.php'; ?>
  <link rel="stylesheet" href="assets/css/review.css">
</head>
<body>
  <?php include 'includes/menu.php'; ?>
  <!--<?php include '.php'; ?>--> <!--koneksi lu wan-->

<main class="review-page">
    <h2>Tulis Ulasan Kamu</h2>
    <p style="margin-bottom: 25px;">Bagikan pendapatmu tentang website atau postingan ini 👇</p>

    <form action="" method="POST">
      <label for="nama">Nama</label>
      <input type="text" name="nama" id="nama" placeholder="Masukkan nama kamu" required>

      <label for="ulasan">Ulasan</label>
      <textarea name="ulasan" id="ulasan" rows="5" placeholder="Tulis ulasan kamu di sini..." required></textarea>

      <button type="submit" name="submit">Kirim</button>
    </form>
    <hr>
    <div class="review-list">
      <h3>Semua Ulasan</h3>
    </div>
  </main>

  <?php include 'includes/footer.php'; ?>
</body>
</html>
