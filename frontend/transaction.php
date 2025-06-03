<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Transaction Page</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="scripts.js"></script>
</head>
<body>
           <button class="add-btn" onclick="openform()">Add Transaction</button>

           <!-- popup form -->

           <div id="popupform" class="model">
            <div class="modal-content">
                <span class="close" onclick="closeform()">&times;</span>
                <h3>Add Transaction</h3>
                <form id ="transactionForm" method="post" action="transaction.php">
                    <label>Transaction Type:</label>
                    <select name="transaction_type" id="transaction_type">
                        <?php include 'get_options.php'; loadOptions('transaction_types'); ?>
                    </select>

                    <button type="submit">Submit</button>
            </div>

           </div>

</body>
</html>