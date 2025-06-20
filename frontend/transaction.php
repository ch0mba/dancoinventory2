<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Page</title>
    <!-- Link to your custom CSS for styling -->
    <link rel="stylesheet" href="../styles/transaction.css">
    <!-- Link to Bootstrap Icons for icons like plus and close -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="Transaction container">
        <span>
            <h1>Transactions</h1> <!-- Updated title for clarity -->
        </span>
        <div class="header-buttons">
            <!-- Button to open the "Add New Transaction" modal -->
            <button id="addTransactionBtn" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Transaction
            </button>
            <!-- Button to open the "Close Transaction" modal -->
            <button id="closeTransactionBtn" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Close Transaction
            </button>
        </div>
    </div>

  

    <!-- Container for the main transactions table -->
    <div class="table-container">
        <table id="itemTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Ref</th>
                    <th>Warehouse</th>
                    <th>Stock Location</th>
                    <th>Department</th>
                    <th>Truck No</th>
                    <th>Officer</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                <!-- Transaction data will be loaded here dynamically by JavaScript -->
            </tbody>
        </table>
        <!-- Message box for table-related feedback (e.g., no transactions found, error loading) -->
        <div id="tableMessageBox" class="message"></div>
    </div>

      <!-- The Modal/ Popup Form for Adding a New Transaction -->
      <div id="addTransactionModal" class="modal">
        <div class="modal-content">
            <!-- Close button for this modal -->
            <span class="close add-modal-close">&times;</span>
            <h2>Add New Transaction</h2>
            <!-- <div id="poupform" class="popupform"> -->
                <!-- Form to submit new transaction data -->
                <form id="transactionForm" action="../backend/transaction.php" method="POST">
                    <div class="form-group">
                        <label for="trnType">Transaction Type:</label>
                        <!-- Dropdown will be populated dynamically by JavaScript -->
                        <select id="trnType" name="trnType" required>
                            <option value="">Select a Transaction Type</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="trnRef">Transaction Reference:</label>
                        <input type="text" id="trnRef" name="trnRef" required>
                    </div>

                    <div class="form-group">
                        <label for="warehouse">Warehouse:</label>
                        <div class="searchable-container">
                            <input type="text" id="warehouse_search_input" class="searchable-input" placeholder="Type to search warehouse" autocomplete="off" required>
                            <div id="warehouse_dropdown" class="searchable-dropdown">

                            </div>
                            <input type="hidden" id="warehouse" name="warehouse">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="stockLocation">Stock Location:</label>
                        <!-- Dropdown will be populated dynamically by JavaScript -->
                        <select id="stockLocation" name="stockLocation" required>
                            <option value="">Select a Stock Location</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="department">Department:</label>
                        <input type="text" id="department" name="department" required>
                    </div>

                    <div class="form-group">
                        <label for="truckNo">Truck Number:</label>
                        <input type="text" id="truckNo" name="truckNo" maxlength="7">
                    </div>

                    <div class="form-group">
                        <label for="officer">Officer:</label>
                        <input type="text" id="officer" name="officer" required>
                    </div>

                    <button type="reset">Reset</button>
                    <button type="submit">Submit</button>
                </form>
                <!-- Message box for Add Transaction form feedback -->
                <div id="addMessageBox" class="message"></div>
            <!-- </div> -->
        </div>
    </div>

    <!-- The Modal/ Popup Form for Closing a Transaction -->
    <div id="closeTransactionModal" class="modal">
        <div class="modal-content">
            <!-- Close button for this modal -->
            <span class="close close-modal-close">&times;</span>
            <h2>Close Transaction</h2>
            <!-- Form to submit the transaction reference to be closed -->
            <form id="closeTransactionForm">
                <div class="form-group">
                    <label for="trnRefToClose">Transaction ID to Close:</label>
                    <input type="text" id="trnRefToClose" name="trnRefToClose" required placeholder="Enter Transaction ID">
                </div>
                <button type="submit">Close Transaction</button>
                
            </form>
            <!-- Message box for Close Transaction form feedback -->
            <div id="closeMessageBox" class="message"></div>
        </div>
    </div>

    <!-- Link to the JavaScript file that handles interactivity and data fetching -->
    <script src="../scripts/transaction.js"></script>
</body>
</html>
