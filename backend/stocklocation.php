<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stocklocation = trim($_POST['stocklocation']);
    if (empty($stocklocation)) {
        die("Stock location is required.");
    }
    $uppercaseStockLocation = strtoupper($stocklocation);
    $stmt = $conn->prepare("SELECT id FROM stocklocation WHERE stocklocation = ?");
    $stmt->bind_param("s", $uppercaseStockLocation);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("Stock location already utilized.");
    }
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO stocklocation (stocklocation) VALUES (?)");
    $stmt->bind_param("s", $uppercaseStockLocation);

    if ($stmt->execute()) {
        header("Location: ../frontend/stock_location_setup.php ");
        exit;
        echo "Added successfully.";
    } else {
        die("Registration failed.Please try again later.");
    }
}
?>