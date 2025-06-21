<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Stock Item</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="../styles/transactiondetail.css">
    </head>

    <body>

        <h2>Add Stock Item</h2>

        <div class="select_transaction">
            <label for="transaction_search_input">Search for Transaction:</label>
            <div class="searchable-container">
                <input type="text" id="transaction_search_input" class="searchable-input" placeholder="Type to search transaction" autocomplete="off" required>
                <div id="transaction_dropdown" class="searchable-dropdown">
                    </div>
                <input type="hidden" id="transaction" name="transaction">
            </div>
        </div>

       <form id="itemForm">
            <div class="form-group">
                <label for="stockcode_search_input">Stockcode:</label>
                <div class="searchable-container">
                    <input type="text" id="stockcode_search_input" class="searchable-input" placeholder="Type to search stockcode" autocomplete="off" required>
                    <div id="stockcode_dropdown" class="searchable-dropdown">
                        </div>
                    <input type="hidden" id="stockcode" name="stockcode">
                </div>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" min="1" required>
            </div>

            <div class="form-group">
                <label for="brand">Brand:</label>
                <input type="text" id="brand" name="brand" required>
            </div>

            <div class="form-group">
                <label for="note">Notation:</label>
                <input type="text" id="note" name="note" required>
            </div>

            <button id="addItemBtn" class="btn btn-primary" type="button">
                <i class="bi bi-plus-circle"></i>
                Add Item
            </button>
        </form>

        <hr>

        <h3>Added Items</h3>
        <div class="detail_table">
            <table id="itemTable">
                <thead>
                    <tr>
                        <th>Stockcode</th>
                        <th>Quantity</th>
                        <th>Brand</th>
                        <th>Notation</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    </tbody>
            </table>
        </div>

        <hr>

        <form id="submitForm" action="../backend/transactiondetail.php" method="POST">
            <input type="hidden" id="items" name="itemInput">
            <button type="submit" class="btn btn-success">Submit All Items</button>
        </form>

        <script src="../scripts/transactiondetail.js"></script>
    </body>
</html>