<?php
session_start(); // Start the session to store user data
// Include the database and BaseDAO classes
require_once 'database.php';
require_once 'classes.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans">

    <!-- Navigation -->
    <nav class="bg-gray-800 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-2xl font-semibold">Dashboard</div>
            <ul class="flex space-x-4">
                <li>
                    <a href="?page=user-management" class="hover:text-blue-500">User Section</a>
                </li>
                <li>
                    <a href="?page=category-management" class="hover:text-blue-500">Category Section</a>
                </li>
                <li>
                    <a href="?page=product-management" class="hover:text-blue-500">Product Section</a>
                </li>
                <li>
                    <a href="?page=order-management" class="hover:text-blue-500">Order Section</a>
                </li>
                <li>
                    <a href="products.php" hover:text-blue-500">Products</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Content Area -->
    <div class="container mx-auto mt-5 p-6 bg-white shadow-md rounded-md">

        <h2 class="text-2xl font-semibold mb-4">Dashboard Overview</h2>

        <?php
        // Check if a page parameter is set in the URL
        if (isset($_GET['page'])) {
            $page = $_GET['page'];

            // Validate and include the selected page
            if (in_array($page, ['user-management', 'category-management', 'product-management', 'order-management']) && file_exists($page . '.php')) {
                include($page . '.php');
            } else {
                echo '<p class="text-red-500">Invalid page selected.</p>';
            }
        } else {
            // Default page to include when no specific page is selected
            include('user-management.php');
        }
        ?>

    </div>

    <!-- Include Tailwind CSS (you might want to remove this in production) -->
    <script src="https://cdn.tailwindcss.com"></script>


</body>

</html>