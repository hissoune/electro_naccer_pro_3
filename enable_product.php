<?php
// Include the database and BaseDAO classes
require_once 'database.php';
require_once 'classes.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Assuming you have a UserDAO class with a method to disable a user
    $productrDAO = new ProductDAO();
    $productrDAO->enableProduct($productId);

    // Redirect back to the user management page after disabling the user
    header("Location: dashboard.php?page=product-management");
    exit();
} else {
    // Handle invalid requests
    header("Location: error.php");
    exit();
}
