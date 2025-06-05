<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Transaction Page</title>
    <link rel="stylesheet" href="transaction.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="transaction.js"></script>
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

            
                <form id="transactionForm">
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
                        <label for="stockLocation">Product Name:</label>
                        <input type="text" id="stockLocation" name="stockLocation" required>
                    </div>
                    
                    <div class ="form-group">
                        <label for="department">Department:</label>
                        <input type="text" id="department" name="department" required>
                    </div>

                    <div class="form-group">
                        <label for="truckNo">Truck Number:</label>
                        <input type="text" id="truckNo" name="truckNo" required>
                    </div>

                    <div class="form-group">
                        <label for="officer">Officer:</label>
                        <input type="text" id="officer" name="officer" required>
                    </div>


                    <button type="submit">Submit</button>
                
                  </form>
                <div id="messageBox" class="message"></div>
            </div>
        </div>
      <script src="transaction.js"></script>  
</body>
</html>