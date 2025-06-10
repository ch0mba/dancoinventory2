<?php
include 'connection.php'; // Assuming 'connection.php' establishes $conn for a PDO or mysqli connection

// Check if $conn is properly initialized (e.g., for PDO)
if (!isset($conn) || !$conn) {
    die("Database connection failed. Please check connection.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and trim input data to prevent SQL injection and unwanted spaces
    $trnType = trim($_POST['trnType']);
    $trnRef = trim($_POST['trnRef']);
    $warehouse = trim($_POST['warehouse']);
    // Corrected variable name from 'stocklocation' to 'stocklocation'
    $stocklocation = trim($_POST['stockLocation']);
    $department = trim($_POST['department']);
    $truckNo = trim($_POST['truckNo']);
    $officer = trim($_POST['officer']);

    // Automatically set 'status' to 'Ongoing' for new transactions
    $status = "Ongoing";

    // Automatically set 'time' to the current timestamp
    $time = date('Y-m-d H:i:s'); // Format: YYYY-MM-DD HH:MM:SS

    // Input validation
    // Corrected typo from 'stockloaction' to 'stocklocation' in validation
    if (empty($stocklocation) || empty($trnRef) || empty($warehouse) || empty($officer) || empty($department) || empty($trnType)) {
        // More specific error message
        die("Error: Required fields (Transaction Type, Transaction Reference, Warehouse, Stock Location, Department, Officer) cannot be empty.");
    }

    // Convert relevant fields to uppercase or sentence case as per intent
    $uppercasetrnType = strtoupper($trnType);
    $uppercaseStockLocation = strtoupper($stocklocation);
    $uppercaseWarehouse = strtoupper($warehouse); // Assuming warehouse might also need uppercasing
    $sentencecaseDepartment = ucfirst(strtolower($department)); // Ensure consistent casing for department
    $sentencecaseOfficer = ucwords(strtolower($officer)); // Ensure consistent casing for officer

    try {
        // Prepare the SQL INSERT statement with placeholders for security
        // Removed 'id' from the INSERT statement, assuming it's auto-incrementing.
        // If 'id' is not auto-incrementing and you need to provide a value, add 'id,' to the columns
        // and a corresponding ':id' placeholder to the VALUES clause, then bind it.
        $sql = "INSERT INTO invmovements (
                    trnType,
                    trnRef,
                    warehouse,
                    department,
                    truckNo,
                    officer,
                    storeLocation,
                    time,
                    status
                ) VALUES (
                    :trnType,
                    :trnRef,
                    :warehouse,
                    :department,
                    :truckNo,
                    :officer,
                    :storeLocation,
                    :time,
                    :status
                )";

        // Prepare the statement for execution (assuming PDO)
        $stmt = $conn->prepare($sql);

        // Check if prepare() was successful
        if ($stmt === false) {
                die("SQL prepare failed: " . implode(" ", $conn->errorInfo())); // This line is correct if $conn is a PDO instance

            // Bind parameters securely using bindValue instead of bindParam
            $stmt->bindValue(':trnType', $uppercasetrnType);
            $stmt->bindValue(':trnRef', $trnRef);
            $stmt->bindValue(':warehouse', $uppercaseWarehouse);
            $stmt->bindValue(':department', $sentencecaseDepartment);
            $stmt->bindValue(':truckNo', $truckNo);
            $stmt->bindValue(':officer', $sentencecaseOfficer);
            $stmt->bindValue(':storeLocation', $uppercaseStockLocation); // Binding to new column name
            $stmt->bindValue(':time', $time);
            $stmt->bindValue(':status', $status);

            // Execute the statement
            if ($stmt->execute()) {
                echo "New transaction recorded successfully!";
            } else {
                // Use errorInfo() for execution errors as well
                echo "Error: Could not record transaction. " . implode(" ", $stmt->errorInfo()); // This line is correct if $stmt is a PDOStatement
            }

            // Close statement
            $stmt = null;
        }
        // Your code that may throw an exception goes here
    } catch (PDOException $e) {
        // Catch any PDO exceptions (e.g., duplicate entry, syntax errors)
        die("Error: " . $e->getMessage());
    }
} else {
    // If the script is accessed via GET request
    echo "This script only accepts POST requests.";
}

// Close the database connection (if applicable, depending on how connection.php handles it)
$conn = null;
?>
