<?php
session_start();
require_once 'connex_db.php';
require_once 'productsDAO.php';

$products = new productDAO();
// Check if the product ID is provided in the URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch the product details based on the provided product ID
    $products->getProductById($product_id);
}

// Check if the form is submitted for updating the product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Sanitize and validate form data
    $reference = $_POST['reference'];
    $label = $_POST['product_name'];
    $description = $_POST['description'];
    $purchase_price = $_POST['purchase_price'];
    $price_offer = $_POST['price_offer'];
    $final_price = $_POST['final_price'];
    $min_quantity = $_POST['min_quantity'];
    $stock_quantity = $_POST['stock_quantity'];
    $barcode = $_POST['barcode'];

    // Update the product details in the 'Products' table
    $product = new product('',$reference,$label,$description,$purchase_price,$price_offer,$final_price,$final_price,$min_quantity,$stock_quantity,$barcode);
    $products->updat_product( $product , $product_id);
    
   

}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Edit Product</title>
    <style>
    body {
        background-color: #f8f9fa;
    }

    .container {
        max-width: 600px;
        margin-top: 50px;
    }

    .mb-3 {
        margin-bottom: 1.5rem !important;
    }

    .mt-3 {
        margin-top: 1.5rem !important;
    }

    .mt-5 {
        margin-top: 3rem !important;
    }

    .form-label {
        font-weight: bold;
    }

    .form-control {
        border-radius: 0;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }
    </style>
</head>

<body>
    <form method="post" action="" enctype="multipart/form-data" class="container">
        <!-- Product Reference -->
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Reference</span>
            <input type="text" class="form-control" placeholder="Reference" name="reference" aria-label="Reference"
                aria-describedby="basic-addon1" value="<?php echo $product->gettreference(); ?>" required>
        </div>
        <!-- Product Label -->
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Label</span>
            <input type="text" class="form-control" placeholder="Product Name" name="product_name"
                aria-label="Product Name" aria-describedby="basic-addon1" value="<?php echo $product->gettlabel(); ?>"
                required>
        </div>
        <!-- Product description -->
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Description</span>
            <input type="text" class="form-control" placeholder="Description" name="description"
                aria-label="Description" aria-describedby="basic-addon1" value="<?php echo $product->gettdescription(); ?>"
                required>
        </div>
        <!-- Product purchase_price -->
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Purchase Price</span>
            <input type="text" class="form-control" placeholder="Purchase Price" name="purchase_price"
                aria-label="Purchase Price" aria-describedby="basic-addon1"
                value="<?php echo $product->gettpurchase_price(); ?>" required>
        </div>
        <!-- Product price_offer -->
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Price Offer</span>
            <input type="text" class="form-control" placeholder="Price Offer" name="price_offer"
                aria-label="Price Offer" aria-describedby="basic-addon1" value="<?php echo $product->gettprice_offer(); ?>"
                required>
        </div>
        <!-- Product final_price -->
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Final Price</span>
            <input type="text" class="form-control" placeholder="Final Price" name="final_price"
                aria-label="Final Price" aria-describedby="basic-addon1" value="<?php echo $product->gettfinal_price(); ?>"
                required>
        </div>
        <!-- Product min_quantity -->
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Min Quantity</span>
            <input type="text" class="form-control" placeholder="Min Quantity" name="min_quantity"
                aria-label="Min Quantity" aria-describedby="basic-addon1"
                value="<?php echo $product->gettfinal_price(); ?>" required>
        </div>
        <!-- Product stock_quantity -->
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Stock Quantity</span>
            <input type="text" class="form-control" placeholder="Stock Quantity" name="stock_quantity"
                aria-label="Stock Quantity" aria-describedby="basic-addon1"
                value="<?php echo $product->gettstock_quantity(); ?>" required>
        </div>
        <!-- Product Barcode -->
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Barcode</span>
            <input type="text" class="form-control" placeholder="Barcode" name="barcode" aria-label="Barcode"
                aria-describedby="basic-addon1" value="<?php echo $product->gettbarcode(); ?>" required>
        </div>

        <!-- Bouton pour soumettre le formulaire -->
        <div class="d-grid mt-3">
            <button type="submit" class="btn btn-primary btn-sm w-100" name="submit">Update Product</button>
            <a href="admin-dashboard.php?page=product-management" class="btn btn-secondary btn-sm w-100 mt-2">Display
                Products</a>
        </div>
    </form>
    <?php include("footer.php"); ?>

    <!-- if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $fields = ['reference', 'product_name', 'description', 'purchase_price', 'price_offer', 'final_price', 'min_quantity', 'stock_quantity', 'barcode'];
    $sanitized = [];

    foreach ($fields as $field) {
        $sanitized[$field] = mysqli_real_escape_string($conn, htmlspecialchars($_POST[$field]));
    }

    $update_query = "UPDATE Products SET 
                     reference = '{$sanitized['reference']}', 
                     label = '{$sanitized['product_name']}', 
                     description = '{$sanitized['description']}', 
                     purchase_price = {$sanitized['purchase_price']}, 
                     price_offer = {$sanitized['price_offer']}, 
                     final_price = {$sanitized['final_price']}, 
                     min_quantity = {$sanitized['min_quantity']}, 
                     stock_quantity = {$sanitized['stock_quantity']}, 
                     barcode = '{$sanitized['barcode']}' 
                     WHERE product_id = '$product_id'"; -->