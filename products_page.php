<?php

require_once 'connex_db.php'; // Adjust the path accordingly
require_once 'productsDAO.php'; // Adjust the path accordingly
require_once 'categoryDAO.php';


// Fetch all products
$productDAO = new ProductDAO();

// Pagination settings
$productsPerPage = 8;
$totalProducts = count($productDAO->getAllProducts());
$totalPages = ceil($totalProducts / $productsPerPage);

// Determine current page
$currentpage = isset($_GET['page']) ? $_GET['page'] : 1;
$currentpage = max(1, min($currentpage, $totalPages));

// Calculate offset for products array
$offset = ($currentpage - 1) * $productsPerPage;

// Fetch products for the current page
$categoryFilter = isset($_GET['categoryFilter']) ? $_GET['categoryFilter'] : '';
$products = $productDAO->getProductsPaginated($offset, $productsPerPage, $categoryFilter);

include("HEAD.php");
?>


<?php include("nav.php"); ?> 

    <div class="container mx-auto mt-5 p-6 bg-white shadow-md rounded-md">
        <h2 class="text-2xl font-semibold mb-4">Product List</h2>
        <!-- Filter by category -->
         <form id="filterForm" method="get">
         <div class="form-group d-flex mb-3">
        <label for="categoryFilter">Filter by Category:</label>
        <select class="form-control w-25" name="categoryFilter" id="categoryFilter">
            <option value="">All Categories</option>
            <?php
            // Fetch and display product categories
            $categories = $productDAO->getProductCategories();
            foreach ($categories as $category) {
                $selected = ($category == $categoryFilter) ? 'selected' : '';
                echo "<option value=\"$category\" $selected>$category</option>";
            }
            ?>
        </select>
        <button type="submit" class="btn btn-primary">Apply Filter</button>
    </div>
   
        </form>
        <!-- Display products as cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php foreach ($products as $product) : ?>
                <div class=" overflow-hidden shadow-md rounded-md">
                    <!-- Add a link to the product info page with the product_id parameter -->
                    <a href="product_info.php?product_id=<?php echo $product->gettproduct_id(); ?>">
                        <img class="w-full object-cover object-center" src="<?php echo $product->gettimage(); ?>" alt="<?php echo $product->gettlabel(); ?>">
                    </a>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2"><?php echo $product->gettlabel(); ?></h3>
                        <p class="text-gray-600 mb-2">$<?php echo $product->gettfinal_price(); ?></p>
                        <form action="" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $product->gettproduct_id(); ?>">
                            <button type="submit" name="add_to_cart" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring focus:border-blue-300">
                                Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination links -->
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item <?php echo ($currentpage == 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?php echo $_SERVER['PHP_SELF'] . '?page=' . ($currentpage - 1) . '&categoryFilter=' . $categoryFilter; ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <li class="page-item <?php echo ($currentpage == $i) ? 'active' : ''; ?>">
                        <a class="page-link" href="<?php echo $_SERVER['PHP_SELF'] . '?page=' . $i . '&categoryFilter=' . $categoryFilter; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php echo ($currentpage == $totalPages || $totalPages == 0) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?php echo $_SERVER['PHP_SELF'] . '?page=' . ($currentpage + 1) . '&categoryFilter=' . $categoryFilter; ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>
    <script>
        $(document).ready(function() {
            // Function to load content using AJAX
            function loadContent(url) {
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function(data) {
                        $('#products-display-container').html(data);
                    }
                });
            }

            // Submit the filter form using AJAX
            $('#filterForm').submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                // Reset page to 1 when applying a new filter
                var url = '<?php echo $_SERVER['PHP_SELF']; ?>' + '?' + formData + '&page=1';
                loadContent(url);
            });

            // Handle pagination clicks
            $('.pagination a').on('click', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('=')[1];
                var formData = $('#filterForm').serialize();
                var url = '<?php echo $_SERVER['PHP_SELF']; ?>' + '?page=' + page + '&' + formData;
                loadContent(url);
            });
        });
    </script>

</body>

</html>
    <?php include("footer.php"); ?>