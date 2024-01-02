<?php


require 'orderDAO.php';

$order = new OrderDAO();
// Fetch orders with user full name from the database
$orderSql=[];
$orderSql = $order->getOrders_user_fullname();

// Handle form submission for order modification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["order_id"])) {
        $orderId = $_POST["order_id"];
        $editStatus = isset($_POST["edit_status"]) ? $_POST["edit_status"] : '';
        $editAmount = isset($_POST["edit_amount"]) ? $_POST["edit_amount"] : '';
        $editCustomer = isset($_POST["edit_customer"]) ? $_POST["edit_customer"] : '';
        // Check if $editCustomer is not empty before using it in the query
        if (!empty($editCustomer)) {
           $updatedata = new order($orderId, $editCustomer, '', '', '', $editAmount, $editStatus);
             

            if ($order->Update_order($updatedata , $orderId)) {
                echo '<div class="alert alert-success" role="alert">Order details updated successfully!</div>';
                // Redirect to the same page to avoid resubmission on refresh
                header("Location: admin-dashboard.php?page=order-management");
                exit();
            } else {
          
                // Redirect to the same page to avoid resubmission on refresh
                header("Location: admin_dashboard.php?page=order_management");
                exit();
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">Invalid customer ID!</div>';
        }
    }
    // Handle order deletion
    if (isset($_POST["delete_order_id"])) {
        $deleteOrderId = $_POST["delete_order_id"];
        

        if ($order-> delet_order($deleteOrderId)) {
            echo '<div class="alert alert-success" role="alert">Order deleted successfully!</div>';
            // Redirect to the same page to avoid resubmission on refresh
            header("Location: admin-dashboard.php?page=order_management");
            exit();
        } else {
            // Redirect to the same page to avoid resubmission on refresh
            header("Location: admin-dashboard.php?page=order_management");
            exit();
        }
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Order List</h2>
    <form method="post">
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Order ID</th>
                    <th>User Name</th>
                    <th>Order Date</th>
                    <th>Send Date</th>
                    <th>Delivery Date</th>
                    <th>Status</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
               foreach($orderSql  as $row) {
                    echo '<tr>';
                    echo '<td>' . $row['order_id'] . '</td>';
                    echo '<td>' . $row['full_name'] . '</td>';
                    echo '<td>' .$row['order_date'] . '</td>';
                    echo '<td>' . $row['send_date'] . '</td>';
                    echo '<td>' . $row['delivery_date'] . '</td>';
                    echo '<td>' . $row['order_status']  . '</td>';
                    echo '<td>' .  $row['total_price'] . '</td>';
                    echo '<td>';
                    echo '<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal' . $row['order_id']  . '">
                                Edit
                              </button>';
                    echo '<button type="button" class="btn btn-danger btn-sm ml-2" data-toggle="modal" data-target="#deleteModal' .$row['order_id']  . '">
                                Delete
                              </button>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </form>
</div>

<?php
// Reset the result pointer to the beginning


// Modal for editing and deleting orders
foreach($orderSql as $row) {
    echo '<div class="modal fade" id="editModal' .$row['order_id'] . '" tabindex="-1" role="dialog" aria-labelledby="editModalLabel' .$row['order_id'] . '" aria-hidden="true">';
    echo '   <div class="modal-dialog">';
    echo '       <div class="modal-content">';
    echo '           <div class="modal-header">';
    echo '               <h5 class="modal-title" id="editModalLabel' .$row['order_id']  . '">Edit Order</h5>';
    echo '               <button type="button" class="close" data-dismiss="modal" aria-label="Close">';
    echo '                  <span aria-hidden="true">&times;</span>';
    echo '               </button>';
    echo '           </div>';
    echo '           <div class="modal-body">';
    echo '               <form method="post" onsubmit="return submitEditForm(' . $row['order_id'] . ');">';
    echo '                   <input type="hidden" name="order_id" value="' . $row['order_id'] . '">';
    echo '                   <div class="form-group">';
    echo '                       <label for="editStatus' . $row['order_id']  . '">Status</label>';
    echo '                       <select class="form-control" id="editStatus' . $row['order_id']  . '" name="edit_status">';
    echo '                           <option value="Pending" ' . ($row['order_status']== 'Pending' ? 'selected' : '') . '>Pending</option>';
    echo '                           <option value="Validated" ' . ($row['order_status'] == 'Validated' ? 'selected' : '') . '>Validated</option>';
    echo '                           <option value="Cancelled" ' . ($row['order_status'] == 'Cancelled' ? 'selected' : '') . '>Cancelled</option>';
    // Add more status options as needed
    echo '                       </select>';
    echo '                   </div>';
    echo '                   <div class="form-group">';
    echo '                       <label for="editAmount' .$row['order_id']. '">Total Price</label>';
    echo '                       <input type="text" class="form-control" id="editAmount' . $row['order_id'] . '" name="edit_amount" value="' .$row['total_price'] . '">';
    echo '                   </div>';
    echo '                   <div class="form-group">';
    echo '                       <label for="editCustomer' .$row['order_id'] . '">User ID</label>';
    echo '                       <input type="text" class="form-control" id="editCustomer' .$row['order_id'] . '" name="edit_customer" value="' . $row['user_id']. '">';
    echo '                   </div>';
    echo '                   <div class="form-group">';
    echo '                       <label for="editSendDate' . $row['order_id'] . '">Send Date</label>';
    echo '                       <input type="date" class="form-control" id="editSendDate' .  $row['order_id'] . '" name="edit_send_date" value="' .$row['send_date'] . '">';
    echo '                   </div>';
    echo '                   <div class="form-group">';
    echo '                       <label for="editDeliveryDate' . $row['order_id'] . '">Delivery Date</label>';
    echo '                       <input type="date" class="form-control" id="editDeliveryDate' . $row['order_id'] . '" name="edit_delivery_date" value="' . $row['delivery_date'] . '">';
    echo '                   </div>';
    echo '                   <button type="submit" class="btn btn-primary">Save Changes</button>';
    echo '               </form>';
    echo '           </div>';
    echo '       </div>';
    echo '   </div>';
    echo '</div>';
    // Modal for deleting orders
    echo '<div class="modal fade" id="deleteModal' . $row['order_id']. '" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel' . $row['order_id'] . '" aria-hidden="true">';
    echo '   <div class="modal-dialog">';
    echo '       <div class="modal-content">';
    echo '           <div class="modal-header">';
    echo '               <h5 class="modal-title" id="deleteModalLabel' . $row['order_id'] . '">Delete Order</h5>';
    echo '               <button type="button" class="close" data-dismiss="modal" aria-label="Close">';
    echo '                  <span aria-hidden="true">&times;</span>';
    echo '               </button>';
    echo '           </div>';
    echo '           <div class="modal-body">';
    echo '               <p>Are you sure you want to delete this order?</p>';
    echo '           </div>';
    echo '           <div class="modal-footer">';
    echo '               <form method="post" onsubmit="return submitDeleteForm(' .$row['order_id'] . ');">';
    echo '                   <input type="hidden" name="delete_order_id" value="' .$row['order_id'] . '">';
    echo '                   <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
    echo '                   <button type="submit" class="btn btn-danger">Delete</button>';
    echo '               </form>';
    echo '           </div>';
    echo '       </div>';
    echo '   </div>';
    echo '</div>';
}
   
?>