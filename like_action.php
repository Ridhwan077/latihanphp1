<?php
session_start();
include 'config.php';
include_once 'includes/functions.php';
//file_put_contents('debug_like.txt', print_r($_POST, true), FILE_APPEND);



if (!isset($_SESSION['member_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$member_id = $_SESSION['member_id'];
$post_id = intval($_POST['post_id']);

// Cek apakah user sudah like postingan ini
$stmt = $conn->prepare("SELECT * FROM likes WHERE member_id = ? AND post_id = ?");
$stmt->bind_param("ii", $member_id, $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Jika sudah like → hapus like
    $stmt = $conn->prepare("DELETE FROM likes WHERE member_id = ? AND post_id = ?");
    $stmt->bind_param("ii", $member_id, $post_id);
    $stmt->execute();
    $liked = false;
} else {
    // Jika belum → tambahkan like
    $stmt = $conn->prepare("INSERT INTO likes (member_id, post_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $member_id, $post_id);
    $stmt->execute();
    $liked = true;
}


// Hitung ulang jumlah like
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM likes WHERE post_id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];

echo json_encode(['liked' => $liked, 'total' => $total]);
?>
