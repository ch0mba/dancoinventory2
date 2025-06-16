<?php
// Include your database connection file
include '../backend/connection.php';

// Set header to return JSON content
header('Content-Type: application/json');

// Check if database connection is properly initialized
if (!isset($conn) || $conn->connect_error) {
    echo json_encode(['error' => "Database connection failed: " . $conn->connect_error]);
    exit();
}

$transactions = [];

// Prepare SQL statement to fetch all transactions from invmovements table
// Ordering by 'time' in descending order to show latest transactions first
$sql = "SELECT id, trnType, trnRef, warehouse, department, truckNo, officer, storeLocation, time, status FROM invmovements ORDER BY time DESC";

$result = $conn->query($sql);

if ($result) {
    // Fetch rows and add to the transactions array
    while ($row = $result->fetch_assoc()) {
        $transactions[] = $row;
    }
    echo json_encode($transactions); // Return the array of transactions as JSON
} else {
    // If query fails, return an error message
    echo json_encode(['error' => "Error fetching transactions: " . $conn->error]);
}

// Close database connection
$conn->close();
?>
