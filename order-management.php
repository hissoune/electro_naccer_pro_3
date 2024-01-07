<?php
// Include necessary files and classes
require_once 'database.php';
require_once 'classes.php';

// Check if the user is logged in and has the necessary permissions
// (You should implement user authentication and authorization)

// Create a new instance of OrderDAO
$orderDAO = new OrderDAO();

// Check if the form is submitted to update order status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'];
    $newStatus = $_POST['new_status'];

    // Update the order status
    $orderDAO->updateOrderStatus($orderId, $newStatus);
}

// Get the list of all orders
$orders = $orderDAO->getAllOrders();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-8">

    <div class="mx-auto">

        <h2 class="text-2xl font-semibold mb-4">Order Management</h2>

        <?php if (!empty($orders)) : ?>
            <table class="min-w-full bg-white border border-gray-300 rounded-md overflow-hidden">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2">Order ID</th>
                        <th class="border px-4 py-2">User ID</th>
                        <th class="border px-4 py-2">Order Date</th>
                        <th class="border px-4 py-2">Total Price</th>
                        <th class="border px-4 py-2">Order Status</th>
                        <th class="border px-4 py-2">Update Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order) : ?>
                        <tr>
                            <td class="border px-4 py-2"><?php echo $order->getOrderId(); ?></td>
                            <td class="border px-4 py-2"><?php echo $order->getUserId(); ?></td>
                            <td class="border px-4 py-2"><?php echo $order->getOrderDate(); ?></td>
                            <td class="border px-4 py-2"><?php echo $order->getTotalPrice(); ?></td>
                            <td class="border px-4 py-2"><?php echo $order->getOrderStatus(); ?></td>
                            <td class="border px-4 py-2">
                                <form method="post" action="">
                                    <input type="hidden" name="order_id" value="<?php echo $order->getOrderId(); ?>">
                                    <select name="new_status" class="border p-2 rounded">
                                        <option value="Pending">Pending</option>
                                        <option value="Validated">Validated</option>
                                        <option value="Cancelled">Cancelled</option>
                                    </select>
                                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No orders available.</p>
        <?php endif; ?>

    </div>

</body>

</html>