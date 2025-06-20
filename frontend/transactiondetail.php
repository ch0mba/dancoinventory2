<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>My Web Page</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="../styles/transactiondetail.css">
    </head>

    <body>

        <h2> Add stock item</h2>

        <div class="select_transaction">
            <label for="Transaction">Search for Transaction:</label>
            <div class="searchable-container">
                <input type="text" id="transaction_search_input" class="searchable-input" placeholder="Type to search transction" autocomplete="off" required>
                <div id="transaction_dropdown" class="searchable-dropdown">

                </div>
                <input type="hidden" id="transaction" name="transaction">
            </div>

        </div>


        <form id="itemForm">
            
                    <div class="form-group">
                        <label for="Stockcode">Stockcode:</label>
                        <div class="searchable-container">
                            <input type="text" id="stockcode_search_input" class="searchable-input" placeholder="Type to search stockcode" autocomplete="off" required>
                            <div id="stockcode_dropdown" class="searchable-dropdown">

                            </div>
                            <input type="hidden" id="stockcode" name="stockcode">
                        </div>

                    </div>

                    <div class="forn-group">
                        
                        <label for="itemQuantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" required>
                    </div>

                    <div class="form-group">
                        <label for="itemBrand">Brand:</label>
                        <input type="text" id="brandrand" name="brand" required>
                    </div>

                    <div class="form-group">
                        <label for="itemNote">Notation:</label>
                        <input type="text" id="note" name="note" required>
                    </div>

                        <button id="submitDetailsBtn" class="btn btn-primary" type="button" onclick="addItem()">
                            <i class="bi bi-plus-circle"></>   
                            Add Item
                        </button>
        </form>


        <div class="detal_table"></div>
            <table id="itemTable">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Brand</th>
                        <th>Notation</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>


        <form id="submitForm" action="../backend/transactiondetail.php" method="POST" onsubmit = "return preparSubmission()">
            <input type="hidden" id="items" name="itemInput">
            <button type="submit">Submit</button>
        </form>
        <script src="../scripts/transactiondetail.js"></script>
    </body>
</html>