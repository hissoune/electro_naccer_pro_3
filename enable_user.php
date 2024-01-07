<?php
// Include the database and BaseDAO classes
require_once 'database.php';
require_once 'classes.php';


if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Assuming you have a UserDAO class with a method to enable a user
    $userDAO = new UserDAO();
    $userDAO->enableUser($userId);

    // Redirect back to the user management page after enabling the user
    header("Location: dashboard.php?page=user-management");
    exit();
} else {
    // Handle invalid requests
    header("Location: error.php");
    exit();
}
