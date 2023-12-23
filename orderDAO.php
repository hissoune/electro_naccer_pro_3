<?php

require_once 'connex_db.php';
require_once 'order.php';
require_once 'users.php';

class OrderDAO{
    private $db;
  
    public function __construct(){
      $this->db = Database::getInstance()->gettconnection();
    } 
      
        
    public function getOrders_user_fullname(){
    $query="SELECT Orders.*, Users.full_name
             FROM Orders 
             JOIN Users ON Orders.user_id = Users.user_id";
              $stmt = $this->db->query($query);
              $result = $stmt->fetchAll();
              
              return $result;
    }
    public function Update_order($order , $id){
        $query="UPDATE Orders SET
        order_status = '".$order->getOrderStatus()."',
        total_price = ".$order->getTotalPrice().",
        user_id =".$order->getUserId()."
        WHERE order_id =$id";
        $stmt = $this->db->query($query);
        $stmt->execute();
    

    }
    public function delet_order($id){
        $query= "DELETE FROM Orders WHERE order_id = $id";
        $stmt = $this->db->query($query);

    }
}