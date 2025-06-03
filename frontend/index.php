<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danco Inventory</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>

<body>
    <div class="login-container">
        <h2>Login</h2>
        <form id= "loginForm" action="../backend/login.php" method="POST">
            <i class="bi bi-person-fill"></i>
            <input type="text" name="username" placeholder="Username" required>
            <i class="bi bi-lock"></i>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <span class="link">
                <a href="registerUser.php">Don't have an account? Register here</a>
                <a href="forgot_password.php">Forgot Password?</a>
            </span>
            <p id="error-message"></p>
            
        </form>
    </div>
</body>