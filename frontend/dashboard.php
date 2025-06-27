<?php
    // In a real application, you'd fetch this data from your database.
    // For demonstration, let's use placeholder values.
    $total_stocks = 0;
    $total_transactions = 0;
    $total_warehouses =0 ;

    // Optional: Determine active page for sidebar highlighting
    $current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danco Inventory Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../styles/dashboard.css">
</head>

<body>
    <nav>
        <div class="sidebar">
            <div class="menu-bar">
                <li class="search-box">
                    <a href="#">
                        <i class="bi bi-search icon"></i> <input type="text" placeholder="Search...">
                    </a>
                </li>
                <ul class="menu-links">
                    <li class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                        <a href="dashboard.php">
                            <i class="bi bi-house icon"></i> <span class="text nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-link <?php echo ($current_page == 'transaction.php') ? 'active' : ''; ?>">
                        <a href="transaction.php">
                            <i class="bi bi-journal-text icon"></i> <span class="text nav-text">Transaction</span>
                        </a>
                    </li>
                    <li class="nav-link <?php echo ($current_page == 'transactiondetail.php') ? 'active' : ''; ?>">
                        <a href="transactiondetail.php">
                            <i class="bi bi-list-columns icon"></i> <span class="text nav-text">Transaction Detail</span>
                        </a>
                    </li>

                    <li class="nav-link <?php echo (strpos($current_page, '_setup.php') !== false) ? 'active' : ''; ?>">
                        <a href="#">
                            <i class="bi bi-gear icon"></i> <span class="text nav-text">Setup</span>
                        </a>
                        
                            <li class="nav-sub-links <?php echo ($current_page == 'stockcode_setup.php') ? 'active' : ''; ?>">
                                <a href="stockcode_setup.php">
                                    <i class="bi bi-box icon"></i> <span class="text nav-sub-text">Stock Code</span>
                                </a>
                            </li>
                        
                            <li class="nav-sub-links <?php echo ($current_page == 'warehouse_setup.php') ? 'active' : ''; ?>">
                                <a href="warehouse_setup.php">
                                    <i class="bi bi-building icon"></i> <span class="text nav-sub-text">Warehouse</span>
                                </a>
                            </li>
                            <li class="nav-sub-links <?php echo ($current_page == 'stock_location_setup.php') ? 'active' : ''; ?>">
                                <a href="stock_location_setup.php">
                                    <i class="bi bi-geo-alt icon"></i> <span class="text nav-sub-text">Stock Location</span>
                                </a>
                            </li>
                            <li class="nav-sub-links <?php echo ($current_page == 'transaction_type_setup.php') ? 'active' : ''; ?>">
                                <a href="transaction_type_setup.php">
                                    <i class="bi bi-arrow-left-right icon"></i> <span class="text nav-sub-text">Transaction Type</span>
                                </a>
                            </li>
                        
                    </li>

                    <li class="nav-link logout-link"> <a href="index.php"> <i class="bi bi-box-arrow-right icon"></i> <span class="text nav-sub-text">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="content">
            <h1>Welcome to Danco Inventory Dashboard</h1>
            <p>This is your central hub for managing all inventory operations efficiently.</p>
            <div class="cards">
                <div class="card">
                    <h2>Total Stocks</h2>
                    <div class="value"><?php echo number_format($total_stocks); ?></div>
                </div>
                <div class="card">
                    <h2>Total Transactions</h2>
                    <div class="value"><?php echo number_format($total_transactions); ?></div>
                </div>
                <div class="card">
                    <h2>Total Warehouses</h2>
                    <div class="value"><?php echo number_format($total_warehouses); ?></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>