<?php
session_start();
include 'config.php'; // koneksi database

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Pastikan ada parameter id di URL
if (!isset($_GET['post_id'])) {
    header("Location: profile.php");
    exit;
}

$post_id = intval($_GET['post_id']); // hindari SQL injection

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
    echo "<p>Postingan tidak ditemukan.</p>";
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


// Ambil ID post dari URL
$post_id = $_GET['post_id'];

// Ambil username dari session
$username = $_SESSION['username'];

// Ambil data user untuk tahu user_id
$query_user = $conn->prepare("SELECT member_id FROM members WHERE username = ?");
$query_user->bind_param("s", $username);
$query_user->execute();
$result_user = $query_user->get_result();
$user = $result_user->fetch_assoc();
$user_id = $user['member_id'];

// Cek apakah post tersebut memang milik user yang login
$query = $conn->prepare("SELECT * FROM posts WHERE post_id = ? AND member_id = ?");
$query->bind_param("ii", $post_id, $user_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    // Jika bukan milik user, redirect ke profile
    header("Location: profile.php?error=unauthorized");
    exit;
}

$post = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include 'includes/head.php'; ?>
  <link rel="stylesheet" href="assets/css/style_viewpost_profile.css">
</head>
<body>
  <?php include 'includes/menu.php'; ?>
  <a href="profile.php" class="back-button">← Kembali ke Menu Profile</a>

  <div class="post">
    <div class="post-header">
    <p class="post-date"><?php echo htmlspecialchars($post['created_at']); ?></p>
    <h2 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h2>
    <p class="post-author">by <?php echo htmlspecialchars($_SESSION['username']); ?></p>
    </div>


    <?php if (!empty($post['image_path'])): ?>
      <img src="<?php echo $imagePath; ?>" alt="Post Image" class="post-image <?php echo $orientation; ?>">
    <?php endif; ?>

    <div class="post-content-wrapper">
      <p class="post-content">
        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
      </p>
    </div>


    <div class="post-actions">
    <a href="update_post.php?post_id=<?= (int)$post['post_id'] ?>" class="mybtn mybtn-edit">Edit</a>
    <a href="delete_post.php?id=<?= $post['post_id'] ?>" class="mybtn mybtn-delete" onclick="return confirm('Yakin ingin menghapus postingan ini?');">Delete</a>
</div>


    <!-- Placeholder komentar -->
    <div class="comments-section">
      <h3>Komentar</h3>
      <p>Belum ada komentar untuk postingan ini.</p>
    </div>
  </div>

  <?php include 'includes/footer.php'; ?>
</body>
</html>
