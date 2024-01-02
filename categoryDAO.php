<?php
require_once 'connex_db.php';
require_once 'category.php';
class categoryDAO{
  private $db;

  public function __construct(){
    $this->db = Database::getInstance()->gettconnection();
  } 
  public function getCategoryById($category_id){
    $query = "SELECT * FROM categories WHERE category_id ='$category_id' ";
    $stmt = $this->db->query($query);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result;
}

public function getCategoryidById($category_id){
  $query = "SELECT category_id  FROM categories WHERE category_id ='$category_id' ";
  $stmt = $this->db->query($query);
  $stmt->execute();
  $result = $stmt->fetch();
  return $result;
}


    
  public function get_categorys(){
    $query = "SELECT * FROM categories where is_disabled =0";
    $stmt = $this->db->query($query);
    $stmt -> execute();
    $categorysData = $stmt->fetchAll();
    $categorys = array();
    foreach ( $categorysData as $cat) {
        $categorys[] = new Category($cat["category_id"],$cat["category_name"],$cat["imag_category"], $cat["is_disabled"]);
    }
    return $categorys;

}
public function get_categoryId($category_id){
  $query = "SELECT category_id FROM Categories WHERE category_id = $category_id";
  $stmt = $this->db->query($query);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result;
}

public function get_cat_img(){
 $query="SELECT imag_category FROM Categories" ;
 $stmt = $this->db->query($query);
 $stmt->execute();
 $result = $stmt->fetchAll();
 return $result;

}
public function get_categorys_archive(){
  $query = "SELECT * FROM categories where is_disabled =1";
  $stmt = $this->db->query($query);
  $stmt -> execute();
  $categorysData = $stmt->fetchAll();
  $categorys = array();
  foreach ( $categorysData as $cat) {
      $categorys[] = new Category($cat["category_id"],$cat["category_name"],$cat["imag_category"], $cat["is_disabled"]);
  }
  return $categorys;

}
  public function insert_category($category){
    $query="INSERT INTO categories VALUES (0, '".$category->getCategoryName()."','".$category-> getImageCategory()."', '".$category-> isDisabled()."')  ";
    $stmt = $this->db->query($query);
    $stmt->execute();
    
      
}
public function update_fetche_select(){
  $categorySql = "SELECT * FROM Categories";
  $categoryResult = mysqli_query($this->db, $categorySql);

  // Display categories in the dropdown menu
  while ($categoryRow = mysqli_fetch_assoc($categoryResult)) {
      echo "<option value='" . $categoryRow['category_id'] . "'>" . $categoryRow['category_name'] . "</option>";
  }

  mysqli_close($this->db);
}
public function disaplay_category($id){
    $query = "UPDATE categories SET is_desaybelsd = 1 WHERE category_id=" . $id ;
    $stmt = $this->db->query($query);
    $stmt -> execute();
    if ($stmt -> execute() === TRUE) {
      header("Location: admin-dashboard.php?page=category-management");
      exit();
  } else {
      echo "Error deleting category: " ;
  }
}
public function delet_category($id){
    $query = "DELETE FROM categories  WHERE category_id=" . $id ;
    $stmt = $this->db->query($query);
    $stmt -> execute();
}
public function getAllCategories()
{
    $query = "SELECT * FROM Categories"; // Replace 'categories' with your actual table name
    $statement = $this->db->prepare($query);
    $statement->execute();

    // Fetch all categories
    $categories = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Convert the associative array to an array of Category objects
    $categoryObjects = [];
    foreach ($categories as $category) {
        $categoryObjects[] = new Category(
            $category['category_id'],
            $category['category_name'],
            $category['imag_category'],
        );
    }

    return $categoryObjects;
}
}

