<?php
session_start();
include 'config.php';

// Pastikan user sudah login
if (!isset($_SESSION['member_id'])) {
    echo "<p>⚠️ Anda harus login terlebih dahulu.</p>";
    head("Location: index.php");
}

$member_id = $_SESSION['member_id'];
$title = $_POST['title'];
$content = $_POST['content'];

// Folder penyimpanan gambar
$targetDir = "uploads/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// Generate nama unik untuk file
$fileName = time() . "_" . basename($_FILES["image"]["name"]);
$targetFilePath = $targetDir . $fileName;

// Upload gambar
if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {

    // Simpan data ke database
    $sql = "INSERT INTO posts (member_id, title, content, image_path)
            VALUES ('$member_id', '$title', '$content', '$targetFilePath')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
        alert('upload berhasil');
        window.location.href = 'upload.php';
    </script>";
    } else {
        echo "<p>❌ Gagal menyimpan ke database: " . $conn->error . "</p>";
    }

} else {
    echo "<p>❌ Upload gambar gagal!</p>";
}
?>
