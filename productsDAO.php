<?php
require_once 'connex_db.php';
require_once 'product.php';
class ProductDAO {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()-> gettconnection();
    }
    public function getProductsByCategory($categoryId)
    {
        try {
            $sql = 'SELECT * FROM Products WHERE category_id = :categoryId AND disabled = FALSE';
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
            $stmt->execute();

            $products = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $product = new Product(
                    $row['reference'],
                    $row['image'],
                    $row['barcode'],
                    $row['label'],
                    $row['purchase_price'],
                    $row['final_price'],
                    $row['price_offer'],
                    $row['description'],
                    $row['min_quantity'],
                    $row['stock_quantity'],
                    $row['category_id'],
                    $row['disabled']
                );

                // Add more attributes if needed

                $products[] = $product;
            }

            return $products;
        } catch (PDOException $e) {
            throw new Exception('Error getting products by category: ' . $e->getMessage());
        }
    }

    public function getAllProducts()
    {
        $query = "SELECT * FROM products"; // Replace 'products' with your actual table name
        $statement = $this->db->prepare($query);
        $statement->execute();

        // Fetch all products
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Convert the associative array to an array of Product objects
        $productObjects = [];
        foreach ($products as $product) {
            $productObjects[] = new Product(
                $product['product_id'],
                $product['reference'],
                $product['image'],
                $product['barcode'],
                $product['label'],
                $product['purchase_price'],
                $product['final_price'],
                $product['price_offer'],
                $product['description'],
                $product['min_quantity'],
                $product['stock_quantity'],
                $product['category_id'],
                $product['disabled']
            );
        }

        return $productObjects;
    }


    public function getProductById($product_id){
        $query = "SELECT * FROM products WHERE product_id ='$product_id' ";
        $stmt = $this->db->query($query);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result;
    }
    public function getProducts(){
        $query = "SELECT * FROM products where disabled =0";
    $stmt = $this->db->query($query);
    $stmt -> execute();
    $productsData = $stmt->fetchAll();
    $products = array();
    foreach ( $productsData as $pro) {
        $products[] = new Product($pro["product_id"],$pro["reference"],$pro["image"],$pro["barcode"], $pro["label"],$pro["purchase_price"],$pro["final_price"],$pro["price_offer"], $pro["description"],$pro["min_quantity"], $pro["stock_quantity"],$pro["category_id"]);
    }
    
        return $products;
    }
    public function disaplay_product($id){
        $query = "UPDATE products SET disabled = 1 WHERE product_id=" . $id ;
        $stmt = $this->db->query($query);
        $stmt -> execute();
    }
    public function updat_product( $product , $id){
        $query="UPDATE products  set reference='".$product->gettreference()."', image='".$product-> gettimage()."',barcode='".$product->gettbarcode()."', label='".$product-> gettlabel()."',       purchase_price='".$product->gettpurchase_price()."', final_price='".$product-> gettfinal_price()."',price_offer='".$product->gettprice_offer()."', description='".$product-> gettdescription()."',      min_quantity='".$product-> gettmin_quantity()."',stock_quantity='".$product->gettstock_quantity()."', category_id='".$product-> gettcategory_id()."' WHERE product_id = '$id'"  ;
        $stmt = $this->db->query($query);
        $stmt -> execute();
 }

    // Add methods for insert, update, delete operations if needed
}

?>
