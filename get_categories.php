<?php
// Connect to your database
require_once 'connex_db.php';
require_once 'categoryDAO.php';
$category = new CategoryDAO();
$categories= $category->get_categorys();
// Fetch categories from your database
foreach( $categories as $cat1){
    $categoryId = $cat1->getCategoryId();
    $categoryName =  $cat1->getCategoryName();
    
    echo '<option value="' . $categoryId . '" style="color: red; font-size: 18px;">' . $categoryName . '</option>';
}
?>
