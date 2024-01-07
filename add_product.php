<?php
session_start(); // Start the session to store user data
// Include the database and BaseDAO classes
require_once 'database.php';
require_once 'classes.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $reference = isset($_POST['reference']) ? $_POST['reference'] : '';
    $barcode = isset($_POST['barcode']) ? $_POST['barcode'] : '';
    $label = isset($_POST['label']) ? $_POST['label'] : '';
    $purchase_price = isset($_POST['purchase_price']) ? $_POST['purchase_price'] : '';
    $final_price = isset($_POST['final_price']) ? $_POST['final_price'] : '';
    $price_offer = isset($_POST['price_offer']) ? $_POST['price_offer'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $min_quantity = isset($_POST['min_quantity']) ? $_POST['min_quantity'] : '';
    $stock_quantity = isset($_POST['stock_quantity']) ? $_POST['stock_quantity'] : '';
    $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : '';
    $disabled = isset($_POST['disabled']) ? 1 : 0; // Check if disabled checkbox is checked

    // Handle image upload using the ImageUploader class
    $targetDirectory = 'imgs'; // Change this to your desired directory
    $inputName = 'image';
    $imagePath = ImageUploader::uploadImage($inputName, $targetDirectory);

    // Check if image upload was successful
    if (!$imagePath) {
        $errorMessage = "Failed to upload the image.";
    } else {
        // Create a new Product object
        $newProduct = new Product(
            0, // The product ID will be set automatically by MySQL
            $reference,
            $imagePath, // Set the image path
            $barcode,
            $label,
            $purchase_price,
            $final_price,
            $price_offer,
            $description,
            $min_quantity,
            $stock_quantity,
            $category_id,
            $disabled
        );

        // Use the ProductDAO to add the new product
        $productDAO = new ProductDAO(); // 
        $success = $productDAO->addProduct($newProduct);

        // Check if the product was successfully added
        if ($success) {
            // Redirect to the product management page or display a success message
            header("Location: dashboard.php?page=product-management");
            exit();
        } else {
            // Display an error message
            $errorMessage = "Failed to add the product.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>

<body>

    <div class="container mx-auto mt-5 p-6 bg-white shadow-md rounded-md">
        <h2 class="text-2xl font-semibold mb-4">Add Product</h2>

        <?php
        // Display error message if there is one
        if (isset($errorMessage)) {
            echo "<div class='text-red-500'>$errorMessage</div>";
        }
        ?>

        <form method="post" action="add_product.php" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="reference" class="block text-sm font-medium text-gray-600">Reference:</label>
                <input type="text" id="reference" name="reference" required>
            </div>

            <div class="mb-4">
                <label for="barcode" class="block text-sm font-medium text-gray-600">Barcode:</label>
                <input type="text" id="barcode" name="barcode" required>
            </div>

            <div class="mb-4">
                <label for="label" class="block text-sm font-medium text-gray-600">Label:</label>
                <input type="text" id="label" name="label" required>
            </div>

            <div class="mb-4">
                <label for="purchase_price" class="block text-sm font-medium text-gray-600">Purchase Price:</label>
                <input type="number" id="purchase_price" name="purchase_price" required>
            </div>

            <div class="mb-4">
                <label for="final_price" class="block text-sm font-medium text-gray-600">Final Price:</label>
                <input type="number" id="final_price" name="final_price" required>
            </div>

            <div class="mb-4">
                <label for="price_offer" class="block text-sm font-medium text-gray-600">Price Offer:</label>
                <input type="number" id="price_offer" name="price_offer" required>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-600">Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>

            <div class="mb-4">
                <label for="min_quantity" class="block text-sm font-medium text-gray-600">Min Quantity:</label>
                <input type="number" id="min_quantity" name="min_quantity" required>
            </div>

            <div class="mb-4">
                <label for="stock_quantity" class="block text-sm font-medium text-gray-600">Stock Quantity:</label>
                <input type="number" id="stock_quantity" name="stock_quantity" required>
            </div>

            <div class="mb-4">
                <label for="category_id" class="block text-sm font-medium text-gray-600">Category:</label>
                <select id="category_id" name="category_id" required>


                    <?php
                    $categoryDAO = new CategoryDAO();
                    $categories = $categoryDAO->getAllCategories();
                    foreach ($categories as $category) {
                        echo "<option value='{$category->getCategoryId()}'>{$category->getCategoryName()}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="disabled" class="block text-sm font-medium text-gray-600">Disabled:</label>
                <input type="checkbox" id="disabled" name="disabled">
            </div>

            <!-- Add the image upload field -->
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-600">Product Image:</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>


            <!-- Add other form fields as needed -->

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Add Product</button>
        </form>
    </div>
    <script src="https://cdn.tailwindcss.com"></script>
</body>

</html>