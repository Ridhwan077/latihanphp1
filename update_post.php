<?php
session_start();
include 'config.php';

// Pastikan user sudah login
if (!isset($_SESSION['member_id'])) {
    header("Location: index.php");
    exit;
}

// Pastikan ada post_id di URL
if (!isset($_GET['post_id'])) {
    header("Location: view_post_profile.php");
    exit;
}

$post_id = intval($_GET['post_id']);
$member_id = $_SESSION['member_id'];

// Ambil data postingan lama dari database
$stmt = $conn->prepare("SELECT * FROM posts WHERE post_id = ? AND member_id = ?");
$stmt->bind_param("ii", $post_id, $member_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>⚠️ Postingan tidak ditemukan atau bukan milik Anda.</p>";
    exit;
}

$post = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include 'includes/head.php'; ?>
</head>
<body>
  <?php include 'includes/menu.php'; ?>

  <h2>Edit Postingan</h2>

  <form action="update_post_process.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">

    <label>Judul:</label><br>
    <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required><br><br>

    <label>Deskripsi:</label><br>
    <textarea name="content" required><?= htmlspecialchars($post['content']) ?></textarea><br><br>

    <label>Gambar Saat Ini:</label><br>
    <?php if (!empty($post['image_path'])): ?>
        <img src="<?= htmlspecialchars($post['image_path']) ?>" alt="Post Image" width="200"><br><br>
    <?php else: ?>
        <p>Tidak ada gambar.</p>
    <?php endif; ?>

    <label>Ganti Gambar (opsional):</label><br>
    <input type="file" name="image" accept="image/*"><br><br>

    <button type="submit">Simpan Perubahan</button>
  </form>

  <?php include 'includes/footer.php'; ?>
</body>
</html>
