<?php


// Handle "Add to Cart" button click
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product = $productDAO->getProductById($product_id);

    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    $found = false;
    foreach ($_SESSION['cart'] as &$cartItem) {
        if ($cartItem['product_id'] == $product->getProductId()) {
            // Increment quantity if the product is already in the cart
            $cartItem['quantity']++;
            $found = true;
            break;
        }
    }

    // Add product to the cart if not found
    if (!$found) {
        $_SESSION['cart'][] = [
            'product_id' => $product->getProductId(),
            'product_name' => $product->getLabel(),
            'quantity' => 1,
            'price' => $product->getFinalPrice(),
        ];
    }
}

// Handle "Clear Cart" button click
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_cart'])) {
    unset($_SESSION['cart']);
}

// Calculate total cart price
$totalCartPrice = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $cartItem) {
        $totalCartPrice += $cartItem['quantity'] * $cartItem['price'];
    }
}
?>
<!-- Navigation Bar -->
<nav class="bg-gray-800 p-4 text-white">
    <div class="container mx-auto flex justify-between items-center">
        <a href="../index.php" class="text-lg font-semibold">ELECTONACER</a>

        <!-- Mini Menu Trigger -->
        <div class="relative group">
            <button type="button" class="group relative text-white p-2 focus:outline-none focus:ring"
                id="mini-menu-trigger">
                <span class="font-bold">Cart</span>
                <span class="ml-1">(<?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>)</span>
            </button>

            <!-- Mini Menu Dropdown -->
            <div id="mini-menu" class="absolute hidden bg-gray-500 text-white p-2 mt-2 rounded-md shadow-md z-10 w-48">
                <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) : ?>
                <?php foreach ($_SESSION['cart'] as $cartItem) : ?>
                <div class="flex justify-between mb-2 border-b pb-2">
                    <span class="font-semibold"><?= $cartItem['product_name'] ?></span>
                    <span><?= $cartItem['quantity'] ?> x $<?= $cartItem['price'] ?></span>
                </div>
                <?php endforeach; ?>
                <div class="flex justify-between mt-2 font-semibold">
                    <span>Total:</span>
                    <span>$<?= $totalCartPrice ?></span>
                </div>
                <hr class="my-2">
                <div class="flex justify-between">
                    <a href="view_cart.php" class="text-white  hover:underline">View Cart</a>
                    <form action="" method="post">
                        <button type="submit" name="clear_cart"
                            class="text-red-500 hover:underline focus:outline-none">Clear Cart</button>
                    </form>
                </div>
                <?php else : ?>
                <p class="text-sm">Your cart is empty.</p>
                <?php endif; ?>
            </div>
        </div>
        <!-- User Mini Menu -->
        <div class="relative group ml-4 bg-gray-500 text-white">
            <button type="button" class="group relative bg-gray-500 text-white p-2  focus:outline-none focus:ring"
                id="user-menu-trigger">
                <?php if (isset($_SESSION['user']['username'])) : ?>
                <span class="font-bold"><?= $_SESSION['user']['username'] ?></span>
                <?php else : ?>
                <span class="font-bold">Guest</span>
                <?php endif; ?>
            </button>

            <!-- User Mini Menu Dropdown -->
            <div id="user-menu"
                class="absolute right-4 hidden bg-gray-500 text-white p-2 mt-2 rounded-md shadow-md z-10 w-48">
                <?php if (isset($_SESSION['user']['username'])) : ?>
                <div class="flex justify-between mb-2">
                    <span class="font-semibold"><?= $_SESSION['user']['username'] ?></span>
                </div>
                <hr class="my-2">
                <?php if ($_SESSION['user']['role'] === 'admin') : ?>
                <!-- Admin Links -->
                <div class="flex justify-between">
                    <a href="dashboard.php" class="text-white  hover:underline">Dashboard</a>
                </div>
                <?php else : ?>
                <!-- User Links -->
                <div class="flex justify-between">
                    <a href="user_info.php" class="text-white  hover:underline">User Info</a>
                </div>
                <?php endif; ?>
                <hr class="my-2">
                <div class="flex justify-between">
                    <a href="logout.php" class="text-white  hover:underline">Logout</a>
                </div>
                <?php else : ?>
                <p class="text-sm"><a href="login.php" class="text-white  hover:underline">Login</a></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<script>
document.getElementById('mini-menu-trigger').addEventListener('click', function() {
    document.getElementById('mini-menu').classList.toggle('hidden');
    document.getElementById('user-menu').classList.add('hidden');
});

document.getElementById('user-menu-trigger').addEventListener('click', function() {
    document.getElementById('user-menu').classList.toggle('hidden');
    document.getElementById('mini-menu').classList.add('hidden');
});
</script>