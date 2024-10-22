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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate form input
    if (!empty($username) && !empty($password) && !empty($confirmPassword)) {
        // Check if password and confirm password match
        if ($password === $confirmPassword) {
            // Validate password strength
            if (preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Prepare SQL query to prevent SQL injection
                $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                $stmt->bind_param("ss", $username, $hashedPassword);

                // Execute query
                if ($stmt->execute()) {
                    echo "<script>alert('Signup successful! You can now log in.'); window.location.href = 'login.php';</script>";
                } else {
                    echo "<script>alert('Username already taken. Please choose another.');</script>";
                }

                $stmt->close();
            } else {
                echo "<script>alert('Password must contain at least 8 characters, one uppercase letter, one number, and one special character.');</script>";
            }
        } else {
            echo "<script>alert('Passwords do not match.');</script>";
        }
    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flickerazzi Signup Page</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: 'Russo One', sans-serif;
            background: url('black-background.gif') no-repeat center center/cover;
            overflow: hidden;
        }

        .signup-box {
            background: rgba(246, 246, 246, 0.85);
            color: #070707;
            width: 400px;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(204, 203, 203, 0.5);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .signup-box input[type="text"], .signup-box input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            background: #2a2a2a;
            color: #ffffff;
            font-size: 1rem;
        }

        .signup-box button {
            width: 40%;
            padding: 12px;
            background: #0d0d0d;
            border: none;
            border-radius: 5px;
            color: #ffffff;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
            display: block;
            margin: 20px auto;
        }

        .signup-box button:hover {
            background: #343434;
            transform: scale(1.05);
        }

        .login-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #171717;
            font-size: 1rem;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .login-link:hover {
            color: #545353;
        }
    </style>
    <script>
        function validateForm() {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirm_password").value;
            const passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            if (!passwordRegex.test(password)) {
                alert("Password must contain at least 8 characters, one uppercase letter, one number, and one special character.");
                return false;
            }

            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <div class="signup-box">
        <h2>Signup</h2>
        <form action="signup.php" method="post" onsubmit="return validateForm()">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Signup</button>
        </form>
        <a href="login.php" class="login-link">Already have an account? Login here</a>
    </div>
</body>
</html>
