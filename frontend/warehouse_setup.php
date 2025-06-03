<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Code Setup</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../styles/warehouse.css">
</head>

<body>
    <div class="container">
        <h1>Warehouse Setup</h1>
        <form id="WarehouseForm" action="../backend/warehouse.php" method="POST">
            <div class="mb-3">
                <label for="warehouse" >Enter Warehouse:</label>
                <input type="text"  id="warehouse" name="warehouse" required>
                <label for="warehousedec" >Enter Description:</label>
                <input type="text"  id="warehousedec" name="warehousedec" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <div id="result" class="mt-3"></div>
    </div>

    <script src="../scripts/warehouse.js"></script>
</html>