<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danco Inventory</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../styles/dashboard.css">
</head>

<body>
    <nav>
    <div class="sidebar">
       
        <div class="menu-bar">
            <li class="search-box">
                <a href="#">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Search...">
                </a>
            </li>
            <ul class="menu-links">
                <li class="nav-link">
                    <a href="dashboard.php">
                        <i class="bi bi-house"></i>
                        <span class="text nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="transaction.php">
                        <i class="bi bi-house"></i>
                        <span class="text nav-text">Transaction</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="transactiondetail.php">
                        <i class="bi bi-house"></i>
                        <span class="text nav-text">Transaction Detail</span>
                    </a>
                </li>

                <li class="nav-link">
                     <a href="">
                         <i class="bi bi-house"></i>
                         <span class="text nav-text">Setup</span>
                    </a>

                        <ul class="sub-menu">
                                    <li class="nav-sub-links">
                                        <a href="stockcode_setup.php">
                                        <i class="bi bi-house"></i>
                                        <span class="text nav-sub-text">Stock Code</span>
                                    </a>
                                </li>
                                <li class="nav-sub-links">
                                        <a href="warehouse_setup.php">
                                        <i class="bi bi-house"></i>
                                        <span class="text nav-sub-text">Warehouse</span>
                                    </a>
                                </li>
                                <li class="nav-sub-links">
                                        <a href="stock_location_setup.php">
                                        <i class="bi bi-house"></i>
                                        <span class="text nav-sub-text">Stock Location</span>
                                    </a>
                                </li>
                                <li class="nav-sub-links">
                                        <a href="transaction_type_setup.php">
                                        <i class="bi bi-house"></i>
                                        <span class="text nav-sub-text">Transaction Type</span>
                                    </a>
                                </li>
                                
                            </li>
                       </ul>

            
                <li class="nav-sub-links">
                    <a href="stocks.php">
                       <i class="bi bi-house"></i>
                       <span class="text nav-sub-text">Logout</span>
                    </a>
                </li>
                            
    
            </ul>
        </div>

        

    </div>
    </nav>

<div class="container">
    <div class="content">
        <h1>Welcome to Danco Inventory Dashboard</h1>
        <p>This is the main dashboard where you can manage your inventory.</p>
        <div class="cards">
            <div class="card">
                <h2>Total Stocks</h2>
    
            </div>
            <div class="card">
                <h2>Total Transactions</h2>
            
            </div>
            <div class="card">
                <h2>Total Warehouses</h2>
        
            </div>
        </div>
    </div>

</div>

</body