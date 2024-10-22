<?php
$host = 'localhost'; // Your database host
$dbname = 'flickerazzi_db'; // Your database name
$user = 'your_username'; // Your database username
$pass = 'your_password'; // Your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
