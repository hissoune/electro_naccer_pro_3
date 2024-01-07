<?php
session_start();
require_once 'database.php';
require_once 'classes.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data, including the product_id
    $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : '';
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

    $targetDirectory = 'imgs'; // Change this to your desired directory
    $inputName = 'image';
    $imagePath = ImageUploader::uploadImage($inputName, $targetDirectory);

    // Check if image upload was successful
    if (!$imagePath) {
        $errorMessage = "Failed to upload the image.";
    } else {
        // Create a new Product object
        $editedProduct = new Product(
            $product_id,
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

        // Use the ProductDAO to edit the product
        $productDAO = new ProductDAO();
        $productDAO->updateProduct($editedProduct);
        // Redirect or display an error message
        header("Location: dashboard.php?page=product-management");
        exit();
    }
} else {
    // Retrieve the product ID from the query parameters
    $product_id = isset($_GET['id']) ? $_GET['id'] : null;

    // Check if the product ID is valid
    if (!$product_id) {
        // Redirect or display an error message
        header("Location: dashboard.php?page=product-management");
        exit();
    }

    // Retrieve the existing product details
    $productDAO = new ProductDAO();
    $existingProduct = $productDAO->getProductById($product_id);

    // Check if the product exists
    if (!$existingProduct) {
        // Redirect or display an error message
        header("Location: dashboard.php?page=product-management");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
</head>

<body>

    <div class="container mx-auto mt-5 p-6 bg-white shadow-md rounded-md">
        <h2 class="text-2xl font-semibold mb-4">Edit Product</h2>

        <?php
        // Display error message if there is one
        if (isset($errorMessage)) {
            echo "<div class='text-red-500'>$errorMessage</div>";
        };
        ?>

        <form method="post" action="edit_product.php" enctype="multipart/form-data">
            <!-- Add a hidden input for the product ID -->
            <input type="hidden" name="product_id" value="<?= $existingProduct->getProductId(); ?>">

            <div class="mb-4">
                <label for="reference" class="block text-sm font-medium text-gray-600">Reference:</label>
                <input type="text" id="reference" name="reference" required value="<?= $existingProduct->getReference(); ?>" class="border rounded-md px-3 py-2 w-full">
            </div>

            <div class="mb-4">
                <label for="barcode" class="block text-sm font-medium text-gray-600">Barcode:</label>
                <input type="text" id="barcode" name="barcode" required value="<?= $existingProduct->getBarcode(); ?>" class="border rounded-md px-3 py-2 w-full">
            </div>

            <div class="mb-4">
                <label for="label" class="block text-sm font-medium text-gray-600">Label:</label>
                <input type="text" id="label" name="label" required value="<?= $existingProduct->getLabel(); ?>" class="border rounded-md px-3 py-2 w-full">
            </div>

            <div class="mb-4">
                <label for="purchase_price" class="block text-sm font-medium text-gray-600">Purchase Price:</label>
                <input type="number" id="purchase_price" name="purchase_price" required value="<?= $existingProduct->getPurchasePrice(); ?>" class="border rounded-md px-3 py-2 w-full">
            </div>

            <div class="mb-4">
                <label for="final_price" class="block text-sm font-medium text-gray-600">Final Price:</label>
                <input type="number" id="final_price" name="final_price" required value="<?= $existingProduct->getFinalPrice(); ?>" class="border rounded-md px-3 py-2 w-full">
            </div>

            <div class="mb-4">
                <label for="price_offer" class="block text-sm font-medium text-gray-600">Price Offer:</label>
                <input type="number" id="price_offer" name="price_offer" required value="<?= $existingProduct->getPriceOffer(); ?>" class="border rounded-md px-3 py-2 w-full">
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-600">Description:</label>
                <textarea id="description" name="description" rows="4" required class="border rounded-md px-3 py-2 w-full"><?= $existingProduct->getDescription(); ?></textarea>
            </div>

            <div class="mb-4">
                <label for="min_quantity" class="block text-sm font-medium text-gray-600">Min Quantity:</label>
                <input type="number" id="min_quantity" name="min_quantity" required value="<?= $existingProduct->getMinQuantity(); ?>" class="border rounded-md px-3 py-2 w-full">
            </div>

            <div class="mb-4">
                <label for="stock_quantity" class="block text-sm font-medium text-gray-600">Stock Quantity:</label>
                <input type="number" id="stock_quantity" name="stock_quantity" required value="<?= $existingProduct->getStockQuantity(); ?>" class="border rounded-md px-3 py-2 w-full">
            </div>

            <div class="mb-4">
                <label for="category_id" class="block text-sm font-medium text-gray-600">Category:</label>
                <select id="category_id" name="category_id" required class="border rounded-md px-3 py-2 w-full">
                    <?php
                    // Fetch all categories
                    $categoryDAO = new CategoryDAO();
                    $categories = $categoryDAO->getAllCategories();

                    foreach ($categories as $category) {
                        $selected = ($category->getCategoryId() == $existingProduct->getCategoryId()) ? 'selected' : '';
                        echo "<option value='{$category->getCategoryId()}' $selected>{$category->getCategoryName()}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="disabled" class="block text-sm font-medium text-gray-600">Disabled:</label>
                <input type="checkbox" id="disabled" name="disabled" <?= $existingProduct->isDisabled() ? 'checked' : ''; ?> class="border rounded-md px-3 py-2">
            </div>

            <!-- Display existing product image -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600">Existing Image:</label>
                <img src="<?= $existingProduct->getImage(); ?>" alt="Existing Image" class="h-20 w-auto">
            </div>

            <!-- Upload new image -->
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-600">New Image:</label>
                <input type="file" id="image" name="image" class="border rounded-md px-3 py-2">
            </div>

            <!-- Add a submit button for editing the product -->
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Edit Product</button>
        </form>
    </div>
    <script src="https://cdn.tailwindcss.com"></script>
</body>

</html>