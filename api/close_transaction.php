<?php
// Include your database connection file
include '../backend/connection.php';

// Check if database connection is properly initialized
if (!isset($conn) || $conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Process only POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and trim the transaction reference received from the form
    $trnRefToClose = trim($_POST['trnRefToClose']);

    // Input validation: Ensure transaction reference is not empty
    if (empty($trnRefToClose)) {
        echo "Error: Transaction Reference cannot be empty.";
        exit();
    }

    // Prepare the SQL UPDATE statement to change the status to 'Completed'
    // for the transaction matching the provided 'trnRef'.
    $sql = "UPDATE invmovements SET status = 'Completed' WHERE id = ?";

    // Prepare the statement for execution using mysqli's prepared statements for security
    $stmt = $conn->prepare($sql);

    // Check if prepare() was successful (i.e., no syntax errors in SQL)
    if ($stmt === false) {
        echo "SQL prepare failed: " . $conn->error;
        exit();
    }

    // Bind parameters: 's' indicates that '$trnRefToClose' is a string
    $stmt->bind_param("s", $trnRefToClose);

    // Execute the statement
    if ($stmt->execute()) {
        // Check how many rows were affected by the UPDATE operation
        if ($stmt->affected_rows > 0) {
            echo "Transaction '" . htmlspecialchars($trnRefToClose) . "' closed successfully!";
        } else {
            // If 0 rows affected, it means the trnRef was not found or already 'Completed'
            echo "No transaction found with reference '" . htmlspecialchars($trnRefToClose) . "' or it was already completed.";
        }
    } else {
        // If execution failed, display the error
        echo "Error: Could not close transaction. " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();

} else {
    // If the script is accessed via a GET request instead of POST
    echo "This script only accepts POST requests.";
}

// Close the database connection
$conn->close();
?>
