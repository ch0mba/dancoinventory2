<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>My Web Page</title>
        <link rel="stylesheet" href="styles1.css">
    </head>

    <body>

        <h2> Add stock item</h2>

        <div class="Transaction">
            <label for ="transactionCode">Transaction Code:</label>
            <select id="transactionCode" id="TransactionCode">
            <?php
                // Connect to the database and retrieve active options from the detail table
                include "connection.php";

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT * FROM detail WHERE active = 1";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
                    }
                } else {
                    echo "0 results";
                }

                $conn->close();
            ?>
            
            </select>  

        </div>


        <form id="itemForm">
            <label for="itemName">Item Name:</label>
            <input type="text" id="itemName" name="itemName" required>

            <label for="itemQuantity">Quantity:</label>
            <input type="number" id="itemQuantity" name="itemQuantity" required>

            <label for="itemBrand">Brand:</label>
            <input type="text" id="itemBrand" name="itemBrand" required>

            <label for="itemNote">Notation:</label>
            <input type="text" id="itemNote" name="itemNote" required>

            <button type="button" onclick="addItem()">Add Item</button>
        </form>



        <table id="itemTable">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Brand</th>
                    <th>Notation</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <form id="submitForm" action="stock.php" method="POST" onsubmit = "return preparSubmission()">
            <input type="hidden" id="items" name="itemInput">
            <button type="submit">Submit</button>
        </form>
        <script src="script.js"></script>
    </body>
</html>