<?php
include 'connection.php'; // Assuming 'connection.php' now establishes a mysqli connection

// Check if $conn is properly initialized for mysqli
if (!isset($conn) || $conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the 'itemInput' is set, as this is how your JavaScript sends the data
    if (isset($_POST['itemInput'])) {
        // Decode the JSON string into a PHP array
        $items = json_decode($_POST['itemInput'], true); // 'true' decodes it into an associative array

        // Check for JSON decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            die("Error: Invalid JSON data received for items. " . json_last_error_msg());
        }

        // Ensure there are items to process
        if (empty($items)) {
            die("Error: No items provided for submission.");
        }

        // Prepare the SQL INSERT statement for each item
        // Ensure column names match your 'invmovdet' table
        $sql = "INSERT INTO invmovdet(`invMovId`, `stockcode`, `brand`, `trnQty`, `notation`) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("SQL prepare failed: " . $conn->error);
        }

        $all_successful = true;
        $inserted_count = 0;
        $failed_count = 0;

        // Loop through each item in the decoded array and insert it
        foreach ($items as $item) {
            // Extract and sanitize data for each item
            // Note: transaction_id comes from the JS item object
            $transactionId = trim($item['transaction_id']);
            // stockcode_id is the actual ID for the database
            $stockcode = trim($item['stockcode_name']);
            $quantity = trim($item['quantity']);
            $brand = trim($item['brand']);
            $note = trim($item['note']);

            // Input validations for each individual item
            if (empty($transactionId) || empty($stockcode) || empty($quantity) || empty($brand) || empty($note)) {
                // Log the error for debugging purposes instead of dying for the entire batch
                error_log("Skipping item due to empty required fields: " . json_encode($item));
                $all_successful = false;
                $failed_count++;
                continue; // Skip this item and move to the next one
            }

            // Convert relevant fields to sentence case
            $sencaseBrand = ucwords(strtolower($brand));
            $sencaseNote = ucwords(strtolower($note));

            // Bind parameters securely
            // 'sisss' -> string (invMovId), string (stockcode), integer (trnQty), string (brand), string (notation)
            // Adjust 'siss' if quantity is not an integer in your DB, e.g., 'sssss' if it's always a string.
            $stmt->bind_param(
                "sisss",
                $transactionId,
                $stockcode,
                $quantity, // Assuming quantity is an integer, so 'i'
                $sencaseBrand,
                $sencaseNote
            );

            // Execute the statement for the current item
            if ($stmt->execute()) {
                $inserted_count++;
            } else {
                error_log("Error inserting item: " . $stmt->error . " for item: " . json_encode($item));
                $all_successful = false;
                $failed_count++;
            }
        }

        // Close the prepared statement after the loop
        $stmt->close();

        // Provide feedback to the user
        if ($all_successful) {
            echo "Successfully recorded " . $inserted_count . " new transaction details.";
        } else if ($inserted_count > 0) {
            echo "Recorded " . $inserted_count . " transaction details, but " . $failed_count . " failed. Check server logs for details.";
        } else {
            echo "No transaction details were recorded. Check server logs for errors.";
        }

    } else {
        // If 'itemInput' is not found, it means the form was not submitted as expected
        echo "Error: 'itemInput' data not received. This script expects a JSON array of items.";
    }

} else {
    // If the script is accessed via GET request
    echo "This script only accepts POST requests.";
}

// Close the database connection
$conn->close();
?>