<?php
session_start(); // Start the session to store user data
require_once 'database.php';
require_once 'classes.php';

// Function to authenticate user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Create a UserDAO instance
    $userDAO = new UserDAO();

    // Perform authentication
    $authenticatedUser = $userDAO->authenticateUser($username, $password);

    if ($authenticatedUser) {
        // Redirect to the product page after successful login
        header("Location: products.php");
        exit();
    } else {
        // Authentication failed, show an error message
        $error_message = 'Invalid username or password';
    }
}

// Debugging: Print any error message
if (isset($error_message)) {
    echo '<p style="color: red;">' . $error_message . '</p>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h2 class="text-2xl font-semibold mb-4">
            Login
        </h2>
        <form method="post" action="" class="space-y-4">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-600">Username:</label>
                <input type="text" name="username" required class="mt-1 p-2 w-full border rounded">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-600">Password:</label>
                <input type="password" name="password" required class="mt-1 p-2 w-full border rounded">
            </div>

            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">Login</button>
        </form>

        <p class="mt-4 text-sm text-gray-600">Don't have an account? <a href="register.php" class="text-blue-500">Register here</a></p>
    </div>
    <script src="https://cdn.tailwindcss.com"></script>
</body>

</html>