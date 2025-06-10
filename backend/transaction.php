<?php
include 'connection.php'; // Assuming 'connection.php' now establishes a mysqli connection

// Check if $conn is properly initialized for mysqli
if (!isset($conn) || $conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and trim input data
    $trnType = trim($_POST['trnType']);
    $trnRef = trim($_POST['trnRef']);
    $warehouse = trim($_POST['warehouse']);
    $stocklocation = trim($_POST['stockLocation']); // Corrected $_POST key from 'stockloscation'
    $department = trim($_POST['department']);
    $truckNo = trim($_POST['truckNo']);
    $officer = trim($_POST['officer']);

    // Automatically set 'status' to 'Ongoing' for new transactions
    $status = "Ongoing";

    // Automatically set 'time' to the current timestamp
    $time = date('Y-m-d H:i:s'); // Format: YYYY-MM-DD HH:MM:SS

    // Input validation
    if (empty($trnType) || empty($trnRef) || empty($warehouse) || empty($stocklocation) || empty($department) || empty($officer)) {
        die("Error: Required fields (Transaction Type, Transaction Reference, Warehouse, Stock Location, Department, Officer) cannot be empty.");
    }

    // Convert relevant fields to uppercase or sentence case
    $uppercasetrnType = strtoupper($trnType);
    $uppercaseStockLocation = strtoupper($stocklocation);
    $uppercaseWarehouse = strtoupper($warehouse);
    $sentencecaseDepartment = ucfirst(strtolower($department));
    $sentencecaseOfficer = ucwords(strtolower($officer));

    // Prepare the SQL INSERT statement with question mark (?) placeholders for mysqli
    // Assuming 'id' is auto-incrementing and thus omitted from the INSERT list
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
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement for execution (using mysqli)
    $stmt = $conn->prepare($sql);

    // Check if prepare() was successful
    if ($stmt === false) {
        die("SQL prepare failed: " . $conn->error); // Use $conn->error for mysqli prepare errors
    }

    // Bind parameters securely using bind_param
    // 'sssssssss' indicates 9 string parameters (s for string, i for integer, d for double, b for blob)
    $stmt->bind_param(
        "sssssssss",
        $uppercasetrnType,
        $trnRef,
        $uppercaseWarehouse,
        $sentencecaseDepartment,
        $truckNo,
        $sentencecaseOfficer,
        $uppercaseStockLocation, // Ensure this matches the 'storeLocation' column in your table
        $time,
        $status
    );

    // Execute the statement
    if ($stmt->execute()) {
        echo "New transaction recorded successfully!";
    } else {
        // Use $stmt->error for execution errors
        echo "Error: Could not record transaction. " . $stmt->error;
    }

    // Close statement
    $stmt->close();

} else {
    // If the script is accessed via GET request
    echo "This script only accepts POST requests.";
}

// Close the database connection
$conn->close(); // Use $conn->close() for mysqli connection
?>
