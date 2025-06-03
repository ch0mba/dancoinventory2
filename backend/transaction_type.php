<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $transactiontype = trim($_POST['transaction_type']); // Changed to match the input name in the form
    
   
    if (empty($transactiontype)) {
        die("All fields are required.");
    }
    $transactiontype = strtoupper($transactiontype); // Convert to uppercase
    $stmt = $conn->prepare("SELECT id FROM transactiontype WHERE trnType = ?");
    $stmt->bind_param("s", $transactiontype);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("Transaction type already utilized.");
    }
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO transactiontype (trnType) VALUES (?)");
    $stmt->bind_param("s", $transactiontype);

    if ($stmt->execute()) {
        echo "Added successfully.";
        header("Location: ../frontend/transaction_type_setup.php ");
        exit;
    } else {
        die("Registration failed.Please try again later.");
    }
}
?>