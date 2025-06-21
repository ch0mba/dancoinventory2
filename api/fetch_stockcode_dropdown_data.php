<?php
header('Content-Type: application/json');
include '../backend/connection.php'; // Adjust path if needed


if (!isset($conn) || $conn->connect_error) {
    echo json_encode(['error' => "Database connection failed: " . $conn->connect_error]);
    exit();
}

$stockcodes = [];
$searchTerm = $_GET['search'] ?? '';

$sql = "SELECT id, stockcode, description FROM stockcodes"; // Adjust table and column names
$conditions = [];
$paramTypes = "";
$paramValues = [];

if (!empty($searchTerm)) {
    $conditions[] = "stockcode LIKE ?"; // Or another relevant column for searching
    $paramTypes .= "s";
    $paramValues[] = '%' . $searchTerm . '%';
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}
$sql .= " ORDER BY stockcode ASC"; // Or by an appropriate order

if ($stmt = $conn->prepare($sql)) {
    if (!empty($paramValues)) {
        $stmt->bind_param($paramTypes, ...$paramValues);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            // Ensure 'id' and 'name' properties are returned for JavaScript
            $stockcodes[] = ['id' => $row['id'], 'name' => $row['stockcode'],'desc'. '-' .$row['description']];
        }
        echo json_encode($stockcodes);
    } else {
        echo json_encode(['error' => "Error fetching stockcodes: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['error' => "Error preparing statement: " . $conn->error]);
}
$conn->close();
?>
