<?php
include 'db.php'; // Include the database connection

if (isset($_GET['movie_id'])) {
    $movie_id = $_GET['movie_id'];
    $stmt = $pdo->prepare("SELECT c.comment_text, c.created_at, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.movie_id = ? ORDER BY c.created_at DESC");
    $stmt->execute([$movie_id]);
    
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($comments);
}
?>
