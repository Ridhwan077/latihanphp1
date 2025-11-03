<?php
session_start();
include 'config.php'; // koneksi database
include_once 'includes/functions.php';

if (!isset($_SESSION['member_id'])) {
    header("Location: index.php");
    exit;
}

// Pastikan ada parameter id di URL
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$post_id = intval($_GET['id']); // hindari SQL injection

// Query untuk mengambil data postingan beserta nama pembuatnya
$sql = "SELECT posts.*, members.username 
        FROM posts 
        JOIN members ON posts.member_id = members.member_id
        WHERE posts.post_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

// Jika postingan tidak ditemukan
if ($result->num_rows === 0) {
    echo "<p>Postingan tidak ditemukan</p>";
    exit();
}

$post = $result->fetch_assoc();
$imagePath = htmlspecialchars($post['image_path']);
$orientation = 'landscape';

if (file_exists($imagePath)) {
    $size = @getimagesize($imagePath);
    if ($size && isset($size[0], $size[1])) {
        $orientation = ($size[0] < $size[1]) ? 'portrait' : 'landscape';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include 'includes/head.php'; ?>
  <link rel="stylesheet" href="assets/css/style_viewpost.css">
</head>

<body>
  <?php include 'includes/menu.php'; ?>
  <a href="dashboard.php" class="back-button">← Kembali ke Homepage</a>

  <div class="post">
    <div class="post-header">
    <p class="post-date"><?php echo htmlspecialchars($post['created_at']); ?></p>
    <h2 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h2>
    <p class="post-author">by <?php echo htmlspecialchars($post['username']); ?></p>
    </div>


    <?php if (!empty($post['image_path'])): ?>
      <img src="<?php echo $imagePath; ?>" alt="Post Image" class="post-image <?php echo $orientation; ?>">
    <?php endif; ?>

    <div class="post-content-wrapper">
      <p class="post-content">
        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
      </p>
    </div>

    <!-- Placeholder tombol Like -->
    <button id="like-btn" class="like-btn <?php echo userLiked($conn, $post['post_id'], $_SESSION['member_id']) ? 'liked' : ''; ?>"data-post-id="<?php echo $post['post_id']; ?>">
❤️ <span id="like-count"><?php echo getLikeCount($conn, $post['post_id']); ?></span>
</button>
<script>
document.getElementById('like-btn').addEventListener('click', function() {
    const btn = this;
    const postId = this.dataset.postId;

    fetch('like_action.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'post_id=' + postId
    })
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            alert('Anda harus login untuk memberi like.');
            return;
        }

        const count = document.getElementById('like-count');
        count.textContent = data.total;
        btn.classList.toggle('liked', data.liked);
    })
    .catch(err => console.error(err));
});
</script>

    <!-- Placeholder komentar -->
    <div class="comments-section">
      <h3>Komentar</h3>
      <p>Belum ada komentar untuk postingan ini.</p>
    </div>
  </div>

  <?php include 'includes/footer.php'; ?>
</body>
</html>
