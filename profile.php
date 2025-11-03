<?php
session_start();
include 'config.php'; // file koneksi ke database
include 'includes/head.php';
include 'includes/menu.php';

// Pastikan user sudah login
if (!isset($_SESSION['member_id'])) {
    header("Location: index.php");
    exit();
}

$member_id = $_SESSION['member_id'];

// Ambil semua post milik member yang sedang login
$query = "SELECT * FROM posts WHERE member_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profile</title>
    <link rel="stylesheet" href="assets/css/style_profile.css">
</head>
<body>

<h2>Profile</h2>

<div class="blog">
<?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="blog-item">
            <a href="view_post_profile.php?post_id=<?php echo $row['post_id']; ?>" class="blog-link">
                <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Post Image" width="300">
            
            <p class="caption"><?php echo htmlspecialchars($row['title']); ?></p>
            <p class="blog-date"><?php echo date('d F Y', strtotime($row['created_at'])); ?></p>
            </a>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>Belum ada postingan.</p>
<?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
