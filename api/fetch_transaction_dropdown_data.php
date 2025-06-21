<?php
// ... rest of your code
// Include your database connection file
include '../backend/connection.php'; // Ensure this path is correct relative to fetch_transaction.php

// Set header to return JSON content
header('Content-Type: application/json');

// Check if database connection is properly initialized
if (!isset($conn) || $conn->connect_error) {
    echo json_encode(['error' => "Database connection failed: " . $conn->connect_error]);
    exit();
}

$transactions = [];
$searchTerm = $_GET['search'] ?? ''; // Get the search term from the GET request

// Base SQL query to fetch transactions
$sql = "SELECT id, trnType, trnRef, warehouse, department, truckNo, officer, storeLocation, time, status FROM invmovements";

$conditions = []; // Array to hold WHERE clause conditions
$paramTypes = ""; // String for bind_param types
$paramValues = []; // Array for bind_param values

// Condition 1: Filter by status 'ongoing'
$conditions[] = "status = ?";
$paramTypes .= "s"; // 's' for string
$paramValues[] = "ongoing";

// Condition 2: Add search term filter if provided
if (!empty($searchTerm)) {
    $conditions[] = "(id LIKE ?  OR trnRef LIKE ?)";
    $paramTypes .= "ss"; // 'ss' for two string parameters
    $searchParam = '%' . $searchTerm . '%';
    $paramValues[] = $searchParam;
    $paramValues[] = $searchParam;
}

// Combine conditions into the WHERE clause
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY time DESC"; // Append ORDER BY clause

// Prepare the SQL statement
if ($stmt = $conn->prepare($sql)) {
    // Bind parameters if any conditions exist
    if (!empty($paramValues)) {
        // Use call_user_func_array to dynamically bind parameters
        $stmt->bind_param($paramTypes, ...$paramValues);
    }

    // Execute the statement
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();

    if ($result) {
        // Fetch rows and add to the transactions array
        while ($row = $result->fetch_assoc()) {
            // Modify 'name' to be a combination of trnType and trnRef for display
            // This 'name' property is what the JavaScript dropdown expects to display
            $row['name'] = $row['id'] . ' - ' . $row['trnRef'];
            $transactions[] = $row;
        }
        echo json_encode($transactions); // Return the array of transactions as JSON
    } else {
        // If query execution fails, return an error message
        echo json_encode(['error' => "Error fetching transactions: " . $stmt->error]);
    }

    // Close the statement
    $stmt->close();
} else {
    // If statement preparation fails
    echo json_encode(['error' => "Error preparing statement: " . $conn->error]);
}

// Close database connection
$conn->close();
?>