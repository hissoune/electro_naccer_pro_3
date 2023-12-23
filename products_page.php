<?php
// Include necessary files and classes
include_once 'connex_db.php';
include_once 'productsDAO.php';
include_once 'categoryDAO.php';

// Function to get all products or products by category
function getProducts($categoryId = null)
{
    $productDAO = new ProductDAO();

    try {
        // Call the method based on whether a category is selected
        if ($categoryId !== null) {
            $products = $productDAO->getProductsByCategory($categoryId);
        } else {
            $products = $productDAO->getAllProducts();
        }

        // Display products
        foreach ($products as $product) {
            // Display each product as a card
            echo '<div class="max-w-sm rounded overflow-hidden shadow-lg bg-white">';
            echo '<img class="w-full" src="' . $product->gettimage() . '" alt="' . $product->gettlabel() . '">';
            echo '<div class="px-6 py-4">';
            echo '<div class="font-bold text-xl mb-2">' . $product->gettlabel() . '</div>';
            echo '<p class="text-gray-700 text-base">Description: ' . $product->gettdescription() . '</p>';
            echo '<p class="text-gray-700 text-base">Price: $' . $product->gettfinal_price() . '</p>';
            // Add more details as needed
            echo '</div>';
            echo '</div>';
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

// Function to get all categories
function getAllCategories()
{
    $categoryDAO = new CategoryDAO();

    try {
        $categories = $categoryDAO->getAllCategories();

        // Display categories as options in a select dropdown
        echo '<form method="get">';
        echo '<label for="categoryFilter">Filter by Category:</label>';
        echo '<select name="categoryId" id="categoryFilter" onchange="this.form.submit()">';
        echo '<option value="">All Categories</option>';
        foreach ($categories as $category) {
            $selected = ($_GET['categoryId'] == $category->getCategoryId()) ? 'selected' : '';
            echo '<option value="' . $category->getCategoryId() . '" ' . $selected . '>' . $category->getCategoryName() . '</option>';
        }
        echo '</select>';
        echo '</form>';
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

// Check if a category filter is set in the URL
$categoryIdFilter = isset($_GET['categoryId']) ? $_GET['categoryId'] : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <!-- Add your CSS styles or link to external stylesheets here -->
</head>

<body>

    <h1>Product List</h1>

    <!-- Display category filter -->
    <div>
        <?php getAllCategories(); ?>
    </div>

    <?php
    // Call the function to display products with the category filter
    getProducts($categoryIdFilter);
    ?>

    <!-- Add your HTML structure and styling as needed -->

</body>

</html>