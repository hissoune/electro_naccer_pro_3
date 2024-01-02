<?php
require_once 'connex_db.php';
require_once 'orderDAO.php';
require_once 'UserDAO.php';
require_once 'orderdetailsDAO.php';

session_start();


// Check if the user is logged in
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
$orderDAO= new OrderDAO();
$users = new UserDAO();    
// Initialize variables

$sendDate = date("Y-m-d", strtotime("+1 days"));  // Initialize $sendDate with the current date
$deliveryDate = date("Y-m-d", strtotime("+7 days"));  // Initialize $deliveryDate with the current date
// Fetch user information from the database
$userId = $_SESSION['user']['user_id'];
$userResult= $users->get_user_by_id($userId);

// Check if the user exists
if ($userResult->num_rows > 0) {
    $userData = $userResult->fetch_assoc();
    $fullName = $userData['full_name'];
    $address = $userData['address'];
} else {
    // Handle the case where user information is not found
    echo "User information not found. Please update your profile. Error: " . mysqli_error($conn);
    exit();
}

// Retrieve cart items from the session
$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$totalPrice = 0;

// Calculate the total price
foreach ($cartItems as $item) {
    $totalPrice += $item['quantity'] * $item['price'];
}

// Check if the checkout form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirmCheckout'])) {
    // Add the order details to the Orders table
    $orderDate = date("Y-m-d H:i:s");
    $orderStatus = 'Pending';

    // Generate send_date (today) and delivery_date (7 days from today)
    $sendDate = date("Y-m-d");
    $deliveryDate = date("Y-m-d", strtotime("+7 days"));
    $order = new Order($userId, $orderDate, $totalPrice, $orderStatus, $sendDate, $deliveryDate);
    $orderDAO->insertOrder($order);  // Get the ID of the inserted order

    // Insert order details into the database
    foreach ($cartItems as $item) {
        $productId = isset($item['product_id']) ? $item['product_id'] : null;
        $quantity = isset($item['quantity']) ? $item['quantity'] : 0;
        $unitPrice = isset($item['price']) ? $item['price'] : 0;
        $totalItemPrice = $quantity * $unitPrice;

        // Proceed with the insertion using INSERT IGNORE
        $orderdetailDAO= new OrderDetailsDAO();
        $orderdetails = new OrderDetail('',$userId,$orderId, $productId, $quantity, $unitPrice, $totalItemPrice);
        $orderdetailDAO->insertOrderDetail($orderDetail);

        // Update stock_quantity in Products table
        $orderdetailDAO->updatepro_quant($productId , $quantity);
       
    }

    // Clear the cart after processing the order
    $_SESSION['cart'] = [];

    // Reset the total price
    $totalPrice = 0;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Checkout</title>
</head>

<body>

    <?php include("nav.php"); ?>

    <div class="container mt-5">
        <h2>Checkout</h2>
        <form method="post" action="">
            <!-- Display user information fetched from the database -->
            <div class="form-group">
                <label for="fullname">Full Name:</label>
                <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo $fullName; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo $address; ?>" readonly>
            </div>

            <!-- Add fields for send_date and delivery_date -->
            <div class="form-group">
                <label for="send_date">Send Date:</label>
                <input type="text" class="form-control" id="send_date" name="send_date" value="<?php echo $sendDate; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="delivery_date">Delivery Date:</label>
                <input type="text" class="form-control" id="delivery_date" name="delivery_date" value="<?php echo $deliveryDate; ?>" readonly>
            </div>

            <!-- Add more fields as needed -->

            <!-- Display a summary of the order -->
            <h4>Order Summary:</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item) : ?>
                        <tr>
                            <td><?php echo $item['name']; ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>$<?php echo $item['price']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Display total price -->
            <p><strong>Total Price:</strong> $<?php echo number_format($totalPrice, 2); ?></p>

            <!-- Add a button to confirm the purchase and print the invoice -->
            <button type="submit" class="btn btn-primary" name="confirmCheckout">Confirm Checkout</button>
            <button type="button" class="btn btn-success" onclick="printInvoice()">Print Invoice</button>
        </form>
    </div>

    <!-- Bootstrap alert for the confirmation message -->
    <div class="alert alert-success alert-dismissible fade" role="alert" id="confirmationAlert">
        <strong>Order Confirmed!</strong> Thank you for your order!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php
    include("footer.php")
    ?>

    <script>
        // JavaScript function to print the invoice
        function printInvoice() {
            window.print();
        }

        // Show the confirmation alert
        $(document).ready(function() {
            if (<?php echo isset($_POST['confirmCheckout']) ? 'true' : 'false'; ?>) {
                $('#confirmationAlert').addClass('show');
            }
        });
    </script>