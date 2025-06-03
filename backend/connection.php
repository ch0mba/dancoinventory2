<?php
$servername = "localhost"; // Usually localhost for XAMPP/WAMP
$username = "root";        // Default username for XAMPP/WAMP
$password = "";            // Default password is empty
$database = "dclinventorydb"; // Your actual database name (no backticks!)

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>