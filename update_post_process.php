<?php
session_start();
include 'config.php';

if (!isset($_SESSION['member_id'])) {
    header("Location: index.php");
    exit;
}

$member_id = $_SESSION['member_id'];
$post_id = intval($_POST['post_id']);
$title = $_POST['title'];
$content = $_POST['content'];

// Ambil data lama
$stmt = $conn->prepare("SELECT image_path FROM posts WHERE post_id = ? AND member_id = ?");
$stmt->bind_param("ii", $post_id, $member_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("⚠️ Postingan tidak ditemukan atau bukan milik Anda.");
}

$post = $result->fetch_assoc();
$oldImage = $post['image_path'];

// Cek apakah user upload gambar baru
if (!empty($_FILES['image']['name'])) {
    $targetDir = "uploads/";
    $fileName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . uniqid() . "_" . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            // Hapus gambar lama jika ada
            if (!empty($oldImage) && file_exists($oldImage)) {
                unlink($oldImage);
            }
            $newImage = $targetFilePath;
        } else {
            die("❌ Gagal upload gambar baru.");
        }
    } else {
        die("⚠️ Format file tidak diizinkan.");
    }
} else {
    $newImage = $oldImage; // tetap pakai gambar lama
}

// Update data di database
$stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, image_path = ? WHERE post_id = ? AND member_id = ?");
$stmt->bind_param("sssii", $title, $content, $newImage, $post_id, $member_id);
$stmt->execute();

header("Location: view_post_profile.php?post_id=" . $post_id);
exit;
?>
