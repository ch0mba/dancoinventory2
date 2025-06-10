<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Transaction Page</title>
    <link rel="stylesheet" href="transaction.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="Transaction container">
        <span class ="">
            <h1>Transaction</h1>
        </span>
        <button  id="addTransactionBtn" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Transaction
        </button>
    </div>

    <!-- The Modal/ Popup Form -->
    <div id="transactionModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add Transaction</h2>
            <div id="poupform" class="poupform">

            
                <form id="transactionForm" action= ../backend/transaction.php method="POST">
                    <div class ="form-group">
                        <label for="trnType">Transaction Type:</label>
                        <input type="text" id="trnType" name="trnType" required>
                    </div>
                        <div class="form-group">
                        <label for="trnRef">Transaction Refrence:</label>
                        <input type="text" id="trnRef" name="trnRef" required>
                    </div>

                    <div class="form-group">
                        <label for="warehouse">Warehouse:</label>
                        <input type="text" id="warehouse" name="warehouse" required>
                    </div>

                    <div class="form-group">
                        <label for="stockLocation">Stock Location:</label>

                        <select id ="stockLocation" name="stockLocation" action =../api/get_stocklocation method="GET" required >
                            <option value="">Select a Stock Location </option>
                            <option value="1">Select a Stock Location </option>
                        <?php
                            // Loop through the fetched stock locations and create options
                            // Assuming each fetched row has a 'stockLocation' key
                            foreach ($stockLocationsData as $location) {
                                // htmlspecialchars() is used to prevent XSS attacks
                                echo "<option value=\"" . htmlspecialchars($location['stockLocation']) . "\">" . htmlspecialchars($location['stockLocation']) . "</option>";
                            }
                            ?>
                        </select>
                        
                    </div>
                    
                    <div class ="form-group">
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

                    <button type="reset"> Reset </button>
                    <button type="submit">Submit</button>
                
                  </form>


                  <table id="itemTable">
            <thead>
                <tr>
                    <th>Trandaction No</th>
                    <th>Transaction Type</th>
                    <th>Transaction Reference</th>
                    <th>Warehouse</th>
                    <th>Stock Location</th>
                    <th>Depratment</th>
                    <th>Truck Number</th>
                    <th>Offier</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
                <div id="messageBox" class="message"></div>
            </div>
        </div>
      <script src="../scripts/transaction.js"></script>  
</body>
</html>