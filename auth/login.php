<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - InMeeT</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('image.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
        }

        .login-box {
            width: 350px;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 10px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0px 0px 10px rgba(0,0,0,0.2);
        }

        .login-box h2 {
            margin: 0 0 10px;
            font-size: 28px;
        }

        .login-box p {
            margin: 0 0 20px;
            color: #555;
        }

        .login-box input[type="text"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            background: #fffacb;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .login-box button {
            width: 100%;
            padding: 10px;
            background-color: #ffcccc;
            border: none;
            border-radius: 5px;
            color: #999;
            cursor: not-allowed;
        }

        .login-box input:valid + input:valid + button {
            background-color: #ff5e5e;
            color: white;
            cursor: pointer;
        }

        .remember {
            margin-bottom: 15px;
        }

        .remember label {
            margin-left: 5px;
            font-size: 14px;
        }

        .logo {
            display: block;
            margin: 0 auto 10px;
            width: 120px;
        }
    </style>
</head>
<body>
    <form class="login-box" method="POST" action="check-login.php">
        <img src="logo.png" alt="InMeeT Logo" class="logo">
        <h2>Log in</h2>
        <p>Welcome back! Please enter your details.</p>
        <label>Email or Username</label>
        <input type="text" name="username" required>
        <label>Password</label>
        <input type="password" name="password" required>

        <div class="remember">
            <input type="checkbox" id="remember" name="remember">
            <label for="remember">Remember me</label>
        </div>

        <button type="submit">Login</button>
    </form>
</body>
</html>
