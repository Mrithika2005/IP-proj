<?php
session_start();
include 'db.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id']; // Assuming user ID is stored in session
    $movie_id = $_POST['movie_id'];
    $comment_text = $_POST['comment_text'];
    
    $stmt = $pdo->prepare("INSERT INTO comments (user_id, movie_id, comment_text, created_at) VALUES (?, ?, ?, NOW())");
    if ($stmt->execute([$user_id, $movie_id, $comment_text])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
