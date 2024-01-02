<?php

require_once 'connex_db.php';
require_once 'order.php';
require_once 'users.php';
require_once 'OrderDetail.php';
require_once 'productsDAO.php';

class OrderDetailsDAO {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->gettconnection();
    }

    public function getOrderDetailsById($order_detail_id) {
        $query = "SELECT * FROM OrderDetails WHERE order_detail_id = $order_detail_id";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch();

        return new OrderDetail(
            $result['order_detail_id'],
            $result['user_id'],
            $result['order_id'],
            $result['product_id'],
            $result['quantity'],
            $result['unit_price'],
            $result['total_price']
        );
    }

    public function updateOrderDetail($orderDetail, $id) {
        $query = "UPDATE OrderDetails SET
                  user_id = '" . $orderDetail->getUserId() . "',
                  order_id = '" . $orderDetail->getOrderId() . "',
                  product_id = '" . $orderDetail->getProductId() . "',
                  quantity = " . $orderDetail->getQuantity() . ",
                  unit_price = " . $orderDetail->getUnitPrice() . ",
                  total_price = " . $orderDetail->getTotalPrice() . "
                  WHERE order_detail_id = $id";
        
        $stmt = $this->db->query($query);
        $stmt->execute();
    }

    public function insertOrderDetail($orderDetail) {
        $query = "INSERT INTO OrderDetails (user_id, order_id, product_id, quantity, unit_price, total_price)
                  VALUES (
                    '" . $orderDetail->getUserId() . "',
                    '" . $orderDetail->getOrderId() . "',
                    '" . $orderDetail->getProductId() . "',
                    " . $orderDetail->getQuantity() . ",
                    " . $orderDetail->getUnitPrice() . ",
                    " . $orderDetail->getTotalPrice() . "
                  )";
        
        $stmt = $this->db->query($query);
        $stmt->execute();
    }

    public function deleteOrderDetail($id) {
        $query = "DELETE FROM OrderDetails WHERE order_detail_id = $id";
        $stmt = $this->db->query($query);
    }

    public function updatepro_quant($id , $quantt) {
        
        $query = "UPDATE Products SET stock_quantity = stock_quantity - $quantt WHERE product_id = '$id'";
        $stmt = $this->db->query($query);
        $stmt->execute();
    }
}
