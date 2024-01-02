<?php
require 'connex_db.php';
require 'productsDAO.php';
require 'categoryDAO.php';
$categoryDAO = new CategoryDAO();
$categories= $categoryDAO->get_categorys();
$products = new ProductDAO();

if ($_SERVER['REQUEST_METHOD']=='POST') {
    $loopCount = count($_POST['reference']);

    for ($i = 0; $i < $loopCount; $i++) {
        $reference = $_POST['reference'][$i];
        $label = $_POST['product_name'][$i];
        $description = $_POST['description'][$i];
        $purchase_price = $_POST['purchase_price'][$i];
        $barcode = $_POST['barcode'][$i];
        $price_offer = $_POST['price_offer'][$i];
        $final_price = $_POST['final_price'][$i];
        $min_quantity = $_POST['min_quantity'][$i];
        $stock_quantity = $_POST['stock_quantity'][$i];
        $category_id = $_POST['category'][$i];
    
       
        $checkCategoryResult = $categoryDAO->get_categoryId($category_id);
        // Handle image upload
        $img_name = mysqli_real_escape_string($conn, $_FILES['image']['name'][$i]);
        $img_size = $_FILES['image']['size'][$i];
        $tmp_name = $_FILES['image']['tmp_name'][$i];
        $error = $_FILES['image']['error'][$i];

        // Check if the uploaded image is valid
        $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
        $img_ex_lc = strtolower($img_ex);
        $allowed_exs = array("jpg", "jpeg", "png");

        if (in_array($img_ex_lc, $allowed_exs)) {
            $new_img_name = 'IMG_' . time() . '.' . $img_ex_lc;
            $img_upload_path = './img/' . $new_img_name;
            move_uploaded_file($tmp_name, $img_upload_path);
            if (mysqli_num_rows($checkCategoryResult) > 0) {
                // Insert a new product into the 'Products' table without prepared statements
                // Insert a new product into the 'Products' table
                $producyt = new product($reference,$label,$description,$purchase_price,$barcode,$price_offer,$final_price,$min_quantity,$stock_quantity,$new_img_name,$category_id);
                $products->insertProduct($product);
        
    }
}}}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" integrity="sha512-oAvZuuYVzkcTc2dH5z1ZJup5OmSQ000qlfRvuoTTiyTBjwX1faoyearj8KdMq0LgsBTHMrRuMek7s+CxF8yE+w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div>
    <form method="post" action="" enctype="multipart/form-data" class="container mt-5">
    
        <div id="products-container">
            <div class="product">
                <!-- Product Reference -->
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Reference</span>
                    <input type="text" class="form-control" placeholder="Reference" name="reference[]" aria-label="Reference" aria-describedby="basic-addon1" required>
                </div>
                <!-- Product Label -->
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Label</span>
                    <input type="text" class="form-control" placeholder="Product Name" name="product_name[]" aria-label="Product Name" aria-describedby="basic-addon1" required>
                </div>
                <!-- Product description -->
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Description</span>
                    <input type="text" class="form-control" placeholder="Description" name="description[]" aria-label="Description" aria-describedby="basic-addon1" required>
                </div>
                <!-- Product purchase_price -->
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Purchase Price</span>
                    <input type="number" class="form-control" placeholder="Purchase Price" name="purchase_price[]" aria-label="Purchase Price" aria-describedby="basic-addon1" required>
                </div>
                <!-- Product purchase_price -->
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Barcode</span>
                    <input type="number" class="form-control" placeholder="Barcode" name="barcode[]" aria-label="Barcode" aria-describedby="basic-addon1" required>
                </div>
                <!-- Product price_offer -->
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Price Offer</span>
                    <input type="number" class="form-control" placeholder="Price Offer" name="price_offer[]" aria-label="Price Offer" aria-describedby="basic-addon1" required>
                </div>
                <!-- Product final_price -->
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Final Price</span>
                    <input type="number" class="form-control" placeholder="Final Price" name="final_price[]" aria-label="Final Price" aria-describedby="basic-addon1" required>
                </div>
                <!-- Product min_quantity -->
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Min Quantity</span>
                    <input type="text" class="form-control" placeholder="Min Quantity" name="min_quantity[]" aria-label="Min Quantity" aria-describedby="basic-addon1" required>
                </div>
                <!-- Product stock_quantity -->
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Stock Quantity</span>
                    <input type="number" class="form-control" placeholder="Stock Quantity" name="stock_quantity[]" aria-label="Stock Quantity" aria-describedby="basic-addon1" required>
                </div>
                <!-- Image du produit -->
                <div class="mb-3 mt-3">
                    <label for="product_image" class="form-label">Image du produit</label>
                    <div class="input-group">
                        <input type="file" class="form-control" name="image[]" id="product_image" required>
                    </div>
                    <div class="form-text mt-2">Téléchargez une image du produit.</div>
                </div>

                <!-- Category du produit -->
                <div class="input-group mb-3">
                    <label class="input-group-text" for="category">Category</label>
                    <select class="form-select" id="category" name="category[]" required>
                        <option value="" selected disabled>Select a category</option>
                        <?php
                     foreach( $categories as $cat1){
                        $categoryId = $cat1->getCategoryId();
                        $categoryName =  $cat1->getCategoryName();
                        
                        echo '<option value="' . $categoryId . '" style="color: red; font-size: 18px;">' . $categoryName . '</option>';
                    }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div>
        <button type="button" onclick="addProduct()" style="display: block;">Add Another Product</button>
    <button type="submit" class="btn btn-primary btn-sm w-100" name="submit" style="display: block;">Ajouter un produit</button>
    <a href="admin-dashboard.php?page=product-management" style="display: block;">Display Products</a>
          </div>
    </form>
    </div>
    <script>
        // JavaScript to add more product fields
        function addProduct() {
            const productsContainer = document.getElementById('products-container');
            const newProduct = document.createElement('div');
            newProduct.classList.add('product');
            newProduct.innerHTML = `
        <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Reference</span>
                <input type="text" class="form-control" placeholder="Reference" name="reference[]" aria-label="Reference" aria-describedby="basic-addon1" required>
            </div>
            <!-- Product Label -->
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Label</span>
                <input type="text" class="form-control" placeholder="Product Name" name="product_name[]" aria-label="Product Name" aria-describedby="basic-addon1" required>
            </div>
            <!-- Product description -->
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Description</span>
                <input type="text" class="form-control" placeholder="Description" name="description[]" aria-label="Description" aria-describedby="basic-addon1" required>
            </div>
            <!-- Product purchase_price -->
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Purchase Price</span>
                <input type="text" class="form-control" placeholder="Purchase Price" name="purchase_price[]" aria-label="Purchase Price" aria-describedby="basic-addon1" required>
            </div>
            <!-- Product purchase_price -->
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Barcode</span>
                <input type="text" class="form-control" placeholder="Barcode" name="barcode[]" aria-label="Barcode" aria-describedby="basic-addon1" required>
            </div>
            <!-- Product price_offer -->
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Price Offer</span>
                <input type="text" class="form-control" placeholder="Price Offer" name="price_offer[]" aria-label="Price Offer" aria-describedby="basic-addon1" required>
            </div>
            <!-- Product final_price -->
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Final Price</span>
                <input type="text" class="form-control" placeholder="Final Price" name="final_price[]" aria-label="Final Price" aria-describedby="basic-addon1" required>
            </div>
            <!-- Product min_quantity -->
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Min Quantity</span>
                <input type="text" class="form-control" placeholder="Min Quantity" name="min_quantity[]" aria-label="Min Quantity" aria-describedby="basic-addon1" required>
            </div>
            <!-- Product stock_quantity -->
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Stock Quantity</span>
                <input type="text" class="form-control" placeholder="Stock Quantity" name="stock_quantity[]" aria-label="Stock Quantity" aria-describedby="basic-addon1" required>
            </div>
            <!-- Image du produit -->
            <div class="mb-3 mt-3">
                <label for="product_image" class="form-label">Image du produit</label>
                <div class="input-group">
                    <input type="file" class="form-control" name="image[]" id="product_image" required>
                </div>
                <div class="form-text mt-2">Téléchargez une image du produit.</div>
            </div>
            <div class="input-group mb-3">
                    <label class="input-group-text" for="category">Category</label>
                    <select class="form-select" id="category" name="category[]" required>
                        <option value="" selected disabled>Select a category</option>
                        <?php
                     foreach( $categories as $cat1){
                        $categoryId = $cat1->getCategoryId();
                        $categoryName =  $cat1->getCategoryName();
                        
                        echo '<option value="' . $categoryId . '" style="color: red; font-size: 18px;">' . $categoryName . '</option>';
                    }
                        ?>
                    </select>
                </div>
            `;

            
            productsContainer.appendChild(newProduct);
                } 
            // Fetch categories using AJAX
           
    </script>

</body>

</html>