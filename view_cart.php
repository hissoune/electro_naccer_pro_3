    <?php
    session_start();

    // Check if the cart is set in the session
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
    } else {
        $cart = [];
    }

    // Include necessary files and classes
    require_once 'database.php'; // Adjust the path accordingly
    require_once 'classes.php'; // Adjust the path accordingly

    // Retrieve user information from the session
    if (isset($_SESSION['user'])) {
        $userData = $_SESSION['user'];
        $user = new User(
            $userData['user_id'] ?? null,
            $userData['username'] ?? null,
            $userData['email'] ?? null,
            $userData['password'] ?? null,
            $userData['role'] ?? null,
            $userData['verified'] ?? null,
            $userData['full_name'] ?? null,
            $userData['phone_number'] ?? null,
            $userData['address'] ?? null,
            $userData['disabled'] ?? null,
            $userData['city'] ?? null
        );

        // Retrieve additional user information
        $fullName = $user->getFullName();
        $address = $user->getAddress();
    } else {
        // Redirect to login or set default values
        header("Location: login.php");
        exit();
    }

    // Retrieve shipping and delivery dates (adjust accordingly)
    $shippingDate = date("Y-m-d");
    $deliveryDate = date("Y-m-d", strtotime("+7 days"));

    // Calculate total cart price
    $totalCartPrice = 0;
    foreach ($cart as $cartItem) {
        $totalCartPrice += $cartItem['quantity'] * $cartItem['price'];
    }

    // Handle Checkout
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
        // Create a new order
        $order = new Order(null, $userData['user_id'], date("Y-m-d H:i:s"), null, $deliveryDate, $totalCartPrice, 'Pending');

        // Create an instance of OrderDAO and add the order to the database
        $orderDAO = new OrderDAO();
        $orderDAO->addOrder($order);

        // You can also add order details to the OrderDetails table here
        // Retrieve the order ID of the newly created order
        $orderId = $orderDAO->getLatestOrderId();

        // Loop through cart items and add them as order details
        foreach ($cart as $cartItem) {
            $product = new ProductDAO(); // Assuming you have a ProductDAO
            $productData = $product->getProductById($cartItem['product_id']);
        
            $orderDetail = new OrderDetail(
                $userData['user_id'],
                $orderId,
                $cartItem['product_id'],
                $cartItem['quantity'],
                $productData->getFinalPrice() ?? 0, // Assuming getFinalPrice() is a method of the Product class
                $cartItem['quantity'] * $productData->getFinalPrice()
            );
        
            $orderDetailDAO = new OrderDetailDAO();
            $orderDetailDAO->addOrderDetail($orderDetail);
        }
        

        // Clear the cart after successful checkout
        unset($_SESSION['cart']);

        // Redirect to a success page or order history page
        header("Location: view_cart.php");
        exit();
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
        <title>View Cart</title>
    </head>

    <body class="bg-gray-200">
        <?php include("navbar.php"); ?>

        <div class="container mx-auto mt-5 p-6 bg-white shadow-md rounded-md">
            <h2 class="text-2xl font-semibold mb-4">Shopping Cart</h2>

            <div class="mb-4">
                <p><strong>Name:</strong> <?php echo $fullName; ?></p>
                <p><strong>Address:</strong> <?php echo $address; ?></p>
                <p><strong>Shipping Date:</strong> <?php echo $shippingDate; ?></p>
                <p><strong>Delivery Date:</strong> <?php echo $deliveryDate; ?></p>
            </div>

            <?php if (!empty($cart)) : ?>
            <!-- Cart items table -->
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr>
                        <th class="border border-gray-300 p-2">Product</th>
                        <th class="border border-gray-300 p-2">Quantity</th>
                        <th class="border border-gray-300 p-2">Price</th>
                        <th class="border border-gray-300 p-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $cartItem) : ?>
                    <tr>
                        <td class="border border-gray-300 p-2"><?php echo $cartItem['product_name']; ?></td>
                        <td class="border border-gray-300 p-2"><?php echo $cartItem['quantity']; ?></td>
                        <td class="border border-gray-300 p-2">$<?php echo $cartItem['price']; ?></td>
                        <td class="border border-gray-300 p-2">
                            $<?php echo $cartItem['quantity'] * $cartItem['price']; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Total Cart Price and Clear Cart button -->
            <p class="mt-4">Total Cart Price: $<?php echo $totalCartPrice; ?></p>
            <form action="" method="post">
                <button type="submit" name="checkout"
                    class="bg-green-500 text-white px-4 py-2 mt-4 rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:border-blue-300">Checkout</button>

                <button type="submit" name="clear_cart"
                    class="bg-red-500 text-white px-4 py-2 mt-4 rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:border-blue-300">Clear
                    Cart</button>

            </form>
            <?php else : ?>
            <!-- Display message when cart is empty -->
            <p>Your shopping cart is empty.</p>
            <?php endif; ?>
        </div>
    </body>

    </html>