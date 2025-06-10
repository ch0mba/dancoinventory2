<?php
// transaction_page.php
// Include your database connection file. Ensure this path is correct relative to this file.
include '../backend/connection.php';

// Ensure $conn is properly initialized (e.g., for PDO)
if (!isset($conn) || !$conn) {
    die("Database connection failed. Please check ../backend/connection.php");
}

$stockLocationsData = []; // Initialize an empty array to store fetched locations

try {
    // Fetch stock locations from the 'stocklocation' table, from the 'stockLocation' column
    // Ensure the column name 'stockLocation' matches exactly in your database table.
    $sql = "SELECT stockLocation FROM stocklocation ORDER BY stockLocation ASC";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        // If prepare fails, it often returns false. Get detailed error info.
        die("Error preparing query for stock locations: " . implode(" ", $conn->errorInfo()));
    }

    $stmt->execute();
    // Fetch all results as associative arrays
    if ($stmt instanceof PDOStatement) {
        $stockLocationsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        die("Statement is not a valid PDOStatement.");
    }

} catch (PDOException $e) {
    // Catch any PDO exceptions (e.g., table not found, column not found, syntax errors)
    die("Error fetching stock locations: " . $e->getMessage());
}

// Close the database connection after fetching data
$conn = null;
?>