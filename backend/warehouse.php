<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $warehouse = trim($_POST['warehouse']);
    $description = trim($_POST['warehousedec']); // Added missing variable $description
   
    if (empty($warehouse) || empty($description)) {
        die("All fields are required.");
    }
    $description = ucfirst($description);
    $stmt = $conn->prepare("SELECT id FROM warehouse WHERE warehouse = ?");
    $stmt->bind_param("s", $warehouse);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("Warehouse already exists.");
    }
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO warehouse (warehouse, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $warehouse, $description);

    if ($stmt->execute()) {
        echo "Added successfully.";
        header("Location: ../frontend/warehouse_setup.php ");
        exit;
        echo "Added successfully.";
    } else {
        die("Registration failed.Please try again later.");
    }
}
?>