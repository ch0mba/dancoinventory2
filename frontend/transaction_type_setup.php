<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Code Setup</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../styles/transactiontype.css">
</head>

<body>
    <div class="container">
        <h1>Transaction Type Setup</h1>
        <form id="transcationForm" action="../backend/transaction_type.php" method="POST">
            <div class="mb-3">
                <label for="Transaction _type" >Enter Transaction Type:</label>
                <input type="text"  id="transaction_type" name="transaction_type" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <div id="result" class="mt-3"></div>
    </div>

    <script src="../scripts/transactiontype.js"></script>
</html>