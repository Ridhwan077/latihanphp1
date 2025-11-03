<?php
session_start(); 

include 'config.php';

if (!isset($_SESSION['member_id'])) {
    header("Location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<?php include 'includes/head.php'; ?>
<link rel="stylesheet" href="/latihanphp1/assets/css/style_dashboard.css">
</head>
<body>
<?php include 'includes/menu.php'; ?>
<?php include 'includes/functions.php'; ?>

<div class="dashboard-container">
    <h2>Home Page</h2>
    <p>Hello, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
    <p> Selamat datang di halaman dashboard!</datagrid>
    <br>
    <br>

    <div class="blog-container">
        <?php
        // Ambil postingan selain milik user yang sedang login
        $current_user = (int) $_SESSION['member_id'];

        $sql = "
            SELECT posts.post_id, posts.title, posts.image_path, posts.created_at, members.username
            FROM posts
            JOIN members ON posts.member_id = members.member_id
            WHERE posts.member_id != ?
            ORDER BY posts.created_at DESC
        ";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('i', $current_user);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                // Pastikan image_path tidak kosong
                $img = !empty($row['image_path']) ? '/latihanphp1/uploads/' . htmlspecialchars($row['image_path']) : '/latihanphp1/assets/img/default-thumb.jpg';
                ?>
                <a href="view_post.php?id=<?php echo (int)$row['post_id']; ?>" class="blog-card">

                <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Thumbnail" class="blog-thumb">
                    <div class="blog-info">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p class="creator">by <?php echo htmlspecialchars($row['username']); ?></p>
                        <p class="date">Dibuat pada: <?php echo htmlspecialchars($row['created_at']); ?></p>
                        <button class="read-btn">Baca Selengkapnya</button>
                    </div>
                </a>
                <?php
            } // end while
            $stmt->close();
        } else {
            // jika prepare gagal
            echo '<p>Database error: gagal menyiapkan query.</p>';
        }
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
