<?php
include '../backend/connection.php'; // Assuming 'connection.php' establishes a mysqli connection

header('Content-Type: application/json'); // Set header to return JSON

// Check if $conn is properly initialized for mysqli
if (!isset($conn) || $conn->connect_error) {
    echo json_encode(['error' => "Database connection failed: " . $conn->connect_error]);
    exit();
}

$data = [];

// Fetch Warehouses
$sql_warehouses = "SELECT DISTINCT warehouse FROM warehouse ORDER BY warehouse ASC"; // Assuming 'warehouseName' is the column in 'stockcodes'
$result_warehouses = $conn->query($sql_warehouses);
if ($result_warehouses) {
    $data['warehouses'] = [];
    while ($row = $result_warehouses->fetch_assoc()) {
        $data['warehouses'][] = $row['warehouse'];
    }
} else {
    $data['warehouses_error'] = "Error fetching warehouses: " . $conn->error;
}

// Fetch Stock Locations
$sql_stocklocations = "SELECT DISTINCT stockLocation FROM stocklocation ORDER BY stockLocation ASC"; // Assuming 'locationName' is the column in 'stocklocation'
$result_stocklocations = $conn->query($sql_stocklocations);
if ($result_stocklocations) {
    $data['stockLocations'] = [];
    while ($row = $result_stocklocations->fetch_assoc()) {
        $data['stockLocations'][] = $row['stockLocation'];
    }
} else {
    $data['stocklocations_error'] = "Error fetching stock locations: " . $conn->error;
}

// Fetch Transaction Types
$sql_trntypes = "SELECT DISTINCT trnType FROM transactiontype ORDER BY trnType ASC"; // Assuming 'typeName' is the column in 'transactiontype'
$result_trntypes = $conn->query($sql_trntypes);
if ($result_trntypes) {
    $data['transactionTypes'] = [];
    while ($row = $result_trntypes->fetch_assoc()) {
        $data['transactionTypes'][] = $row['trnType'];
    }
} else {
    $data['trntypes_error'] = "Error fetching transaction types: " . $conn->error;
}


// Fetch Transaction form Invmovements database 
$sql_transactions = "SELECT id, trnType,trnRef, warehouse, department, officer, storeLocation  FROM invmovements WHERE status='Ongoing'  ORDER BY id DESC"; // Assuming 'ID' is the column in 'transactioN ID'
$result_transactions = $conn->query($sql_transactions);
if ($result_transactions) {
    $data['transactions'] = [];
    while ($row = $result_transactions->fetch_assoc()) {
        $data['transactions'][] = $row;
    }
} else {
    $data['transactions_error'] = "Error fetching transaction " . $conn->error;
}
echo json_encode($data);

// Close the database connection
$conn->close();
?>