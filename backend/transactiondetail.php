<?php
include 'connection.php'; // Assuming 'conection.php' now establishes a mysqli connection

// chech if $conn is properly initialized for mysqli

if (!isset($conn) || $conn->connect_error) {
    die("Database connection failed:" . $conn ->connect_error);


}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Sanitize and trim input dta
            $transactionId = trim($_POST(['transaction']));
            $stockcode = trim($_POST['stockcode']);
            $quantity= trim($_POST['quantity']);
            $brand= trim($_POST['brand']);
            $note= trim($_POST['note']);

        // Input validations
        if (empty($transactionId) || empty($stockcode) || empty($quantity)  || empty($brand) || empty($note)) {
            die("Error: Requred field (Transaction code, stockcode, quantity, brand , note) cannot be empty.");


        }
        // Convert relevant fields to setence case
        $sencaseBrand = ucwords(strtolower($brand));
        $sencaseNote = ucwords(strtolower($note));

        //Prepare the SQL INSERT statement with question mard (?) placeholders for mysqli
        //Assuming 'id' is auto-incrementing and thus ommitted form the INSERT list

        $sql= "INSERT INTO invmovdet(
            `invMovId`, `stockcode`, `brand`, `trnQty`, `notation`) VALUES (?, ?, ?, ? ,?)";

        //Prepare the statement for executiion (using mysqli)
        $stmt = $conn->prepare($sql);

        //Check if prepare() was sucessful

        if ($stmt === false){
            die("SQL prepare failed: ". $conn->error); // use $conn->error for mysqli prepare errors

        }

        // Bind parameters securely using bind_param
        // 'sssss' indicates 9 string parameters (s for string, i for integer, d for double, b for blob)
        $stmt->bind_param(
            "sssss",
            $transactionId, $stockcode, $quantity, $sencaseBrand, $sencaseNote
        );

        if ($stmt->execute()) {
            echo "New transaction recorded successfully!";
        } else {
            // Use $stmt->error for execution errors
            echo "Error: Could not record transaction. " . $stmt->error;
        }

        // Close statement
        $stmt->close();

} 

else {
    // If the script is accessed via GET request
    echo "This script only accepts POST requests.";
}

// Close the database connection
$conn->close(); // Use $conn->close() for mysqli connection
?>

