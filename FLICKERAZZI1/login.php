<?php
session_start();
include 'connect.php'; // Include your DB connection settings

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate form input
    if (!empty($username) && !empty($password)) {
        // Prepare SQL query to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        // Check if user exists
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($userId, $hashedPassword);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashedPassword)) {
                // Successful login, store session data
                $_SESSION['username'] = $username;
                $_SESSION['userId'] = $userId;

                // Redirect to a dashboard page
                header("Location: index1.php");
                exit();
            } else {
                echo "<script>alert('Invalid username or password.');</script>";
            }
        } else {
            echo "<script>alert('User does not exist.');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Please fill in both fields.');</script>";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flickerazzi Login / Signup Page</title>
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

        .login-box {
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

        .login-box input[type="text"], .login-box input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            background: #2a2a2a;
            color: #ffffff;
            font-size: 1rem;
        }

        .login-box button {
            width: 40%;
            padding: 12px;
            background: #0d0d0d;
            border: none;
            border-radius: 5px;
            color: #ffffff;
            font-size: 1.2rem;
            cursor: pointer;
            transition: 0.3s ease, transform 0.2s ease;
            display: block;
            margin: 20px auto;
        }

        .login-box button:hover {
            background: #343434;
            transform: scale(1.05);
        }

        .signup-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #171717;
            font-size: 1rem;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .signup-link:hover {
            color: #545353;
        }
    </style>
    <script>
        function validateForm() {
            const password = document.getElementById("password").value;
            const passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    
            if (!passwordRegex.test(password)) {
                alert("Password must contain at least 8 characters, one uppercase letter, one number, and one special character.");
                return false;
            }
    
            return true;
        }
    </script>
</head>
<body>
    <div class="login-box">
        <h2>Login</h2>
        <form action="login.php" method="post" onsubmit="return validateForm()">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
