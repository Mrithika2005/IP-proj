<?php
include 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $movie_id = $_POST['movie_id'];
    $comment_text = $_POST['comment_text'];

    $stmt = $pdo->prepare("INSERT INTO comments (user_id, movie_id, comment_text, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$user_id, $movie_id, $comment_text]);

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
?>
