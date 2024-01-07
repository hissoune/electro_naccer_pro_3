<?php
session_start();
require_once 'database.php';
require_once 'classes.php';

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

// Fetch user data
$userDAO = new UserDAO();
$user = $userDAO->getUserById($_SESSION['user']['user_id']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <title>User Info</title>
</head>

<body class="bg-gray-100">

    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>

    <!-- Content Area -->
    <div class="container mx-auto mt-5 p-6 shadow-md rounded-md">
        <h2 class="text-2xl font-semibold mb-4">User Information</h2>

        <!-- User Info -->
        <div>
            <p><strong>User ID:</strong> <?= $user->getUserId() ?></p>
            <p><strong>Username:</strong> <?= $user->getUsername() ?></p>
            <p><strong>Email:</strong> <?= $user->getEmail() ?></p>
            <p><strong>Role:</strong> <?= $user->getRole() ?></p>
            <p><strong>Verified:</strong> <?= $user->getVerified() ? 'Yes' : 'No' ?></p>
            <p><strong>Full Name:</strong> <?= $user->getFullName() ?></p>
            <p><strong>Phone Number:</strong> <?= $user->getPhoneNumber() ?></p>
            <p><strong>Address:</strong> <?= $user->getAddress() ?></p>
            <p><strong>City:</strong> <?= $user->getCity() ?></p>
            <p><strong>Disabled:</strong> <?= $user->getDisabled() ? 'Yes' : 'No' ?></p>
        </div>
    </div>
</body>

</html>