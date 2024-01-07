<?php
session_start();
require_once 'database.php'; // Adjust the path accordingly
require_once 'classes.php'; // Adjust the path accordingly

// Check if the product_id is provided in the URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Fetch the product by ID
    $productDAO = new ProductDAO();
    $product = $productDAO->getProductById($product_id);

    if ($product) {
        // Product found, display product information
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
            <title><?php echo $product->getLabel(); ?> - Product Information</title>
        </head>

        <body class="bg-gray-100 font-sans">
            <?php include("navbar.php"); ?>

            <div class="container mx-auto mt-5 flex items-center justify-center">
                <div class="max-w-2xl bg-white p-8 rounded-md shadow-md">
                    <div class="flex flex-col md:flex-row">
                        <!-- Product Image on the Left -->
                        <div class="md:w-1/2 pr-8">
                            <img class="w-full h-64 object-cover object-center mb-4 rounded-md" src="<?php echo $product->getImage(); ?>" alt="<?php echo $product->getLabel(); ?>">
                        </div>

                        <!-- Product Information on the Right -->
                        <div class="md:w-1/2">
                            <h2 class="text-3xl font-semibold mb-4"><?php echo $product->getLabel(); ?></h2>
                            <p class="text-gray-700 mb-2">Price: $<?php echo $product->getFinalPrice(); ?></p>
                            <p class="text-gray-700 mb-2">Description: <?php echo $product->getDescription(); ?></p>
                            <p class="text-gray-700 mb-2">Category: <?php echo $product->getCategoryId(); ?></p>
                            <p class="text-gray-700 mb-2">Stock Quantity: <?php echo $product->getStockQuantity(); ?></p>

                            <!-- Add to Cart button -->
                            <form action="" method="post" class="mt-4">
                                <input type="hidden" name="product_id" value="<?php echo $product->getProductId(); ?>">
                                <button type="submit" name="add_to_cart" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">Add
                                    to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </body>

        </html>
<?php
    } else {
        // Product not found, display an error message or redirect to a 404 page
        echo "Product not found";
    }
} else {
    // If product_id is not provided in the URL, redirect to the product list page or display an error message
    header("Location: product_list.php");
    exit();
}
?>