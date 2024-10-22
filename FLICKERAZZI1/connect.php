<?php
// Database connection settings
$servername = "localhost";
$dbusername = "root";  // Change this if necessary
$dbpassword = "";      // Change this if necessary
$dbname = "flickerazzi_db";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
