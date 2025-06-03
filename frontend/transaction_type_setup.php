<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Code Setup</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../styles/stockcode.css">
</head>

<body>
    <div class="container">
        <h1>Stock Location Setup</h1>
        <form id="stocklocationForm" action="../backend/transaction_type.php" method="POST">
            <div class="mb-3">
                <label for="stocklocation" >Enter Stock Location:</label>
                <input type="text"  id="stocklocation" name="stocklocation" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <div id="result" class="mt-3"></div>
    </div>

    <script src="../scripts/stockcode.js"></script>
</html>