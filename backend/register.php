<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $password = $_POST['password'];

    if (empty($username) || empty($firstName) || empty($lastName) || empty($password)) {
        die("All fields are required.");
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("Username already taken.");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, firstName, lastName, role, password) VALUES (?, ?, ?, 'user', ?)");
    $stmt->bind_param("ssss", $username, $firstName, $lastName, $hashedPassword);

    if ($stmt->execute()) {
        header("Location: ../frontend/login.php");
        exit;
    } else {
        die("Registration failed.");
    }
}
?>