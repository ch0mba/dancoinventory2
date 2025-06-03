<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stockcode = trim($_POST['stockcode']);
    $description = trim($_POST['stockcodeDec']); // Added missing variable $description
   
    if (empty($stockcode) || empty($description)) {
        die("All fields are required.");
    }

    $stmt = $conn->prepare("SELECT id FROM stockcodes WHERE stockcode = ?");
    $stmt->bind_param("s", $stockcode);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("Stockcode already utilized.");
    }
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO stockcodes (stockcode, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $stockcode, $description);

    if ($stmt->execute()) {
        header("Location: ../frontend/stock_setup.php ");
        exit;
        echo "Added successfully.";
    } else {
        die("Registration failed.Please try again later.");
    }
}
?>