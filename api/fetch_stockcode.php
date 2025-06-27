<?php
include '../backend/connection.php'; // Make sure this path is correct

header('Content-Type: application/json'); // Indicate that the response is JSON

$search_term = $_GET['search'] ?? ''; // Get search term from GET request, default to empty string

try {
    // Prepare the SQL query
    if (!empty($search_term)) {
        // Use LIKE for searching in both stockcode and description
        $stmt = $conn->prepare("SELECT id, stockcode, description FROM stockcodes WHERE stockcode LIKE ? OR description LIKE ? ORDER BY stockcode ASC");
        $like_term = '%' . $search_term . '%';
        $stmt->bind_param("ss", $like_term, $like_term);
    } else {
        // If no search term, fetch all stockcodes
        $stmt = $conn->prepare("SELECT id, stockcode, description FROM stockcodes ORDER BY stockcode ASC");
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $stockcodes = [];
    while ($row = $result->fetch_assoc()) {
        $stockcodes[] = $row;
    }

    echo json_encode(['success' => true, 'data' => $stockcodes]);

    $stmt->close();
    $conn->close();

} catch (mysqli_sql_exception $e) {
    // Catch database-related errors
    error_log("Database error in fetch_stockcode.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    // For production, you might want a more generic error message for the user.
} catch (Exception $e) {
    // Catch any other general exceptions
    error_log("General error in fetch_stockcode.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An unexpected error occurred.']);
}
?>
