<?php
function greet($name) {
    return "Hello, $name! Welcome to my website.";
}

function userLiked($post_id, $member_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM likes WHERE member_id = ? AND post_id = ?");
    $stmt->bind_param("ii", $member_id, $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

function getLikeCount($conn, $post_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM likes WHERE post_id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'];
}

?>
