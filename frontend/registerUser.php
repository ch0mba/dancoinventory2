<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="login-container">
        <h2>Register</h2>
        <form id="registerForm">
            <i class="bi bi-person-fill"></i>
            <input type="text" name="username" placeholder="Username" required>
            <i class="bi bi-person-vcard-fill"></i>
            <input type="text" name="firstName" placeholder="First Name" required>
            <i class="bi bi-person-vcard-fill"></i>
            <input type="text" name="lastName" placeholder="Last Name" required  >
            <i class="bi bi-person-fill-lock"></i>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
            <p id="error-message"></p>
        </form>
        <p>Already have an account? <a href="index.php">Login here</a></p>
    </div>

</body>
</html>