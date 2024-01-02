<?php
session_start();
require_once 'connex_db.php';
require_once 'UserDAO.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? ''; // Using null coalescing operator to handle unset values
    $password = $_POST['password'] ?? '';

    $userDAO = new UserDAO();

    $authenticatedUser = $userDAO->authenticateUser($username, $password);

    if ($authenticatedUser === false) {
        $error_message = 'this user not found ';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h2 class="text-2xl font-semibold mb-4">Login</h2>
        <form method="post"  class="space-y-4">
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

       
    </div>

    <?php include("footer.php"); ?>
</body>

</html>
