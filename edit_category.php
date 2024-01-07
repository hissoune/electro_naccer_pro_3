<?php
// Include necessary files and classes
include_once 'database.php';
include_once 'classes.php';

// Check if category ID is provided in the URL
if (isset($_GET['id'])) {
    $categoryId = $_GET['id'];

    // Create an instance of CategoryDAO
    $categoryDAO = new CategoryDAO();
    $category = $categoryDAO->getCategoryById($categoryId);

    // Check if the category exists
    if (!$category) {
        // Handle the case where the category is not found
        header('Location: dashboard.php?page=category-management'); // Redirect to the category management page
        exit();
    }
} else {
    // Redirect to the category management page if no category ID is provided
    header('Location: dashboard.php?page=category-management');
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the form data
    $categoryName = isset($_POST['category_name']) ? $_POST['category_name'] : '';
    $isDisabled = isset($_POST['is_disabled']) ? 1 : 0;

    $targetDirectory = 'imgs'; // Change this to your desired directory
    $inputName = 'category_image';
    $imagePath = ImageUploader::uploadImage($inputName, $targetDirectory);

    // Check if image upload was successful
    if (!$imagePath) {
        $errorMessage = "Failed to upload the image.";
    } else {
        // Create a new Product object
        $editedCategory = new Category(
            $categoryId,
            $categoryName,
            $imagePath,
            $isDisabled
            // Set the image path

        );

        // Use the ProductDAO to edit the product
        $categoryDAO = new CategoryDAO();
        $categoryDAO->updateCategory($editedCategory);
        // Redirect or display an error message
        header("Location: dashboard.php?page=category-management");
        exit();
    }
} else {
    // Retrieve the product ID from the query parameters
    $categoryId = isset($_GET['id']) ? $_GET['id'] : null;

    // Check if the product ID is valid
    if (!$categoryId) {
        // Redirect or display an error message
        header("Location: dashboard.php?page=category-management");
        exit();
    }

    // Retrieve the existing product details
    $categoryDAO = new CategoryDAO();
    $existingCategory = $categoryDAO->getCategoryById($categoryId);

    // Check if the product exists
    if (!$existingCategory) {
        // Redirect or display an error message
        header("Location: dashboard.php?page=category-management");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <title>Edit Category</title>
</head>

<body class="bg-gray-100">

    <!-- Content Area -->
    <div class="container mx-auto mt-5 p-6 bg-white shadow-md rounded-md">

        <h2 class="text-2xl font-semibold mb-4">Edit Category</h2>

        <!-- Display error message if any -->
        <?php if (isset($errorMessage)) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline"><?= $errorMessage ?></span>
            </div>
        <?php endif; ?>

        <!-- Category Form -->
        <form action="" method="post" enctype="multipart/form-data">
            <!-- Added enctype for file upload -->
            <div class="mb-4">
                <label for="category_name" class="block text-gray-700 text-sm font-bold mb-2">Category Name:</label>
                <input type="text" name="category_name" id="category_name" value="<?= htmlspecialchars($category->getCategoryName()) ?>" class="w-full border p-2 rounded-md focus:outline-none focus:ring focus:border-blue-300" required>
            </div>

            <div class="mb-4">
                <label for="is_disabled" class="block text-gray-700 text-sm font-bold mb-2">Is Disabled:</label>
                <input type="checkbox" name="is_disabled" id="is_disabled" <?= $category->IsDisabled() ? 'checked' : '' ?> class="mr-2"> Disabled
            </div>

            <div class="mb-4">
                <label for="category_image" class="block text-gray-700 text-sm font-bold mb-2">Category Image:</label>
                <input type="file" name="category_image" id="category_image" accept="image/*" class="border p-2 rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <div class="mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                    Update Category
                </button>
            </div>
        </form>

    </div>

</body>

</html>