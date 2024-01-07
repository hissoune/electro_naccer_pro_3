    <?php
    // Include the database and BaseDAO classes
    require_once 'database.php';
    require_once 'classes.php';
    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get user ID from the form
        $userId = isset($_POST['user_id']) ? $_POST['user_id'] : null;
        // Fetch the user from the database based on the user ID
        $userDao = new UserDAO();  // Assuming you've defined UserDAO class
        $user = $userDao->getUserById($userId);

        if ($user) {
            // Update user information based on the form data
            $user->setUsername($_POST['username']);
            $user->setEmail($_POST['email']);
            $user->setPassword($_POST['password']);
            $user->setRole($_POST['role']);

            // Check if the keys exist before using them
            $user->setVerified(isset($_POST['verified']) ? $_POST['verified'] : null);
            $user->setFullName(isset($_POST['fullName']) ? $_POST['fullName'] : null);
            $user->setPhoneNumber(isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : null);
            $user->setAddress($_POST['address']);
            $user->setDisabled(isset($_POST['disabled']) ? $_POST['disabled'] : null);
            $user->setCity(isset($_POST['city']) ? $_POST['city'] : null);

            // Save the updated user information
            $success = $userDao->updateUser($user);

            if ($success) {
                header('Location: dashboard.php?page=user-management');
                exit();
            } else {
                echo "Failed to update user information.";
            }
        } else {
            echo "User not found.";
        }
    } else {
        // Display the form to edit user information
        $userId = isset($_GET['id']) ? $_GET['id'] : null;

        if ($userId) {
            $userDao = new UserDAO();  // Assuming you've defined UserDAO class
            $user = $userDao->getUserById($userId);

            if ($user) {
                // Display the form with existing user information
    ?>
                <!DOCTYPE html>
                <html lang="en">

                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
                    <title>Edit User</title>
                </head>

                <body class="bg-gray-100">
                    <div class="container mx-auto mt-8 p-8 bg-white shadow-md max-w-md">
                        <h2 class="text-2xl font-bold mb-4">Edit User Information</h2>
                        <form method="post" action="edit_user.php">
                            <input type="hidden" name="user_id" value="<?php echo $user->getUserId(); ?>">

                            <div class="mb-4">
                                <label for="username" class="block text-sm font-semibold text-gray-600">Username:</label>
                                <input type="text" name="username" value="<?php echo $user->getUsername(); ?>" required class="mt-1 p-2 w-full border rounded-md">
                            </div>

                            <div class="mb-4">
                                <label for="email" class="block text-sm font-semibold text-gray-600">Email:</label>
                                <input type="email" name="email" value="<?php echo $user->getEmail(); ?>" required class="mt-1 p-2 w-full border rounded-md">
                            </div>

                            <div class="mb-4">
                                <label for="password" class="block text-sm font-semibold text-gray-600">Password:</label>
                                <input type="password" name="password" value="<?php echo $user->getPassword(); ?>" required class="mt-1 p-2 w-full border rounded-md">
                            </div>
                            <div class="mb-4">
                                <label for="verified" class="block text-sm font-medium text-gray-600">Verified:</label>
                                <select name="verified" required class="mt-1 p-2 border rounded-md w-full" value="<?php echo $user->getVerified(); ?>">
                                    <option value="TRUE">YES</option>
                                    <option value="FALSE">NO</option>
                                </select>
                            </div>
                            <label for="role" class="block text-sm font-medium text-gray-600">Role:</label>
                            <select name="role" required class="mt-1 p-2 border rounded-md w-full">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                            <div class="mb-4">
                                <label for="text" class="block text-sm font-semibold text-gray-600">FullName:</label>
                                <input type="text" name="text" value="<?php echo $user->getFullName(); ?>" required class="mt-1 p-2 w-full border rounded-md">
                            </div>
                            <div class="mb-4">
                                <label for="tel" class="block text-sm font-semibold text-gray-600">Phone Number:</label>
                                <input type="tel" name="tel" value="<?php echo $user->getphoneNumber(); ?>" required class="mt-1 p-2 w-full border rounded-md">
                            </div>
                            <div class="mb-4">
                                <label for="address" class="block text-sm font-semibold text-gray-600">Address:</label>
                                <input type="address" name="address" value="<?php echo $user->getAddress(); ?>" required class="mt-1 p-2 w-full border rounded-md">
                            </div>
                            <div class="mb-4">
                                <label for="text" class="block text-sm font-semibold text-gray-600">City:</label>
                                <input type="text" name="text" value="<?php echo $user->getCity(); ?>" required class="mt-1 p-2 w-full border rounded-md">
                            </div>
                            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue active:bg-blue-800">
                                Save Changes
                            </button>
                        </form>
                    </div>
                </body>

                </html>

    <?php
            } else {
                echo "User not found. Debug: User ID: " . $userId;
            }
        } else {
            echo "User ID not provided in the URL. Debug: GET Array: " . print_r($_GET, true);
        }
    }
    ?>