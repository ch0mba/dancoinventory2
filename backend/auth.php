<?php
session_start();
if (!isset($_SESSION['firstName'])) {
    header("Location: ../frontend/dashboard.php");
    exit;
}
?>