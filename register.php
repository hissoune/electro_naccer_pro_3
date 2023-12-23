<?php
// Include necessary files and classes
require_once 'connex_db.php';
require_once 'users.php';
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $fullName = $_POST['full_name'];
    $phoneNumber = $_POST['phone_number'];
    $address = $_POST['address'];
    $city = $_POST['city'];

    // Hash the password (use a secure hashing algorithm in production)
    $hashedPassword = sha1($password);

    // Create a new user instance
    $user = new User($username, $email, $hashedPassword, 'user', false, $fullName, $phoneNumber, $address, false, $city);

    // Create a new instance of UserDAO and add the user to the database
    $userDAO = new UserDAO();
    $userDAO->insert_users($user);

    // Redirect to a success page or login page
    header('Location: success.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
</head>

<body>

    <body class="bg-gray-100 flex items-center justify-center h-screen">
        <div class="bg-white p-8 rounded shadow-md w-96">
            <h2 class="text-2xl font-semibold mb-4">User Registration</h2>

            <?php
            // Display error message if any
            if (isset($error_message)) {
                echo '<p style="color: red;">' . $error_message . '</p>';
            }

            // Display success message if any
            if (isset($success_message)) {
                echo '<p style="color: green;">' . $success_message . '</p>';
            }
            ?>

            <form method="post" action="" class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-600">Username:</label>
                    <input type="text" name="username" required class="mt-1 p-2 w-full border rounded">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-600">Email:</label>
                    <input type="email" name="email" required class="mt-1 p-2 w-full border rounded">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-600">Password:</label>
                    <input type="password" name="password" required class="mt-1 p-2 w-full border rounded">
                </div>

                <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-600">Full Name:</label>
                    <input type="text" name="full_name" required class="mt-1 p-2 w-full border rounded">
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-600">Phone Number:</label>
                    <input type="tel" name="phone_number" required class="mt-1 p-2 w-full border rounded">
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-600">Address:</label>
                    <input type="text" name="address" required class="mt-1 p-2 w-full border rounded">
                </div>

                <div>
                    <label for="city" class="block text-sm font-medium text-gray-600">City:</label>
                    <input type="text" name="city" required class="mt-1 p-2 w-full border rounded">
                </div>

                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">Register</button>
            </form>
        </div>
        <script src="https://cdn.tailwindcss.com"></script>
    </body>

</html>