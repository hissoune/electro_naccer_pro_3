<div class="container mt-5">
    <form class="form-section" method="post" enctype="multipart/form-data">
        <div id="categories-container" class="mb-3 w-50">
            <div class="category-entry">
                <label for="name_category" class="form-label">Category Name</label>
                <input type="text" class="form-control" name="name_cat[]">
                <label for="img_category" class="form-label">Category Photo</label>
                <input type="file" class="form-control" name="file[]">
            </div>
        </div>
        <div class="d-flex justify-content-between mx-auto">
            <div class="w-25">
                <button type="button" onclick="addCategoryField()" class="btn btn-secondary text-light w-100">Add
                    Another</button>
            </div>
            <div class="w-25">
                <button type="submit" class="btn btn-primary text-light w-100 p-2">Add</button>
            </div>
        </div>
    </form>
</div>


<?php
require_once 'connex_db.php';
require_once 'categoryDAO.php';
require_once 'productsDAO.php';
$categoryDAO=new categoryDAO();



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name_cat']) && isset($_FILES['file'])) {
        $categoriesCount = count($_POST['name_cat']);

        for ($i = 0; $i < $categoriesCount; $i++) {
            $cat_name = $_POST['name_cat'][$i];
            $photo = basename($_FILES['file']['name'][$i]);
            $targetPath = './img/' . $photo;
            $tempPath = $_FILES['file']['tmp_name'][$i];

            if (move_uploaded_file($tempPath, $targetPath)) {
              $category=new category('',$cat_name, $photo);
              $categoryDAO-> insert_category($category);
                
            } else {
                echo "Error uploading file.";
            }
        }
    }
}
?>


<div class="container mt-5">

    <div id="categories" class="w-100 mb-5">
        <table class="table table-bordered">
            <thead class="bg-black text-light">
                <tr>
                    <th class="p-3 border-black" scope="col">Category ID</th>
                    <th class="p-3 border-black" scope="col">Category Name</th>
                    <th class="p-3 border-black" scope="col">Category Image</th>
                    <th class="p-3 border-black" scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
             

                if ($result= $categoryDAO->get_categorys()) {
                    foreach ($result as  $row ) {
                        $categoryid = $row->getCategoryId();
                        $categoryName = $row->getCategoryName();
                        $categoryImg = $row->getImageCategory();

                        echo '<tr class="justify-content-center">';
                        echo '<td class="border-black p-3">' . $categoryid . '</td>';
                        echo '<td class="border-black p-3">' . $categoryName . '</td>';
                        echo '<td class="border-black p-3"><img src="img/' . $categoryImg . '" class="w-25 h-25" alt="Product Image"></td>';
                        echo '<td class="border-black p-2 d-flex">
                                 <form method="POST" class="Categories-action-form mx-3">
                                     <input type="hidden" name="category_id" value="' . $categoryid . '">
                                     <a href="disable_cat.php?id=' . $categoryid . '" type="submit" class="btn btn-danger btn-sm w-4 text-light">Disable</a>
                                 </form>
                                 <form method="POST" class="Categories-action-form mx-3">
                                     <input type="hidden" name="category_id" value="' . $categoryid . '">
                                     <a href="modif_cat.php?id=' . $categoryid . '" type="submit" class="btn btn-primary btn-sm w-4 text-light">Modifier</a>
                                 </form>
                             </td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No categories found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <h2 class="container text-center bg-dark p-1 text-light">Archived Categories</h2>
    <div id="categories" class="w-100">
        <table class="table table-bordered">
            <thead class="bg-black text-light">
                <tr>
                    <th class="p-3 border-black" scope="col">ID</th>
                    <th class="p-3 border-black" scope="col">Category Name</th>
                    <th class="p-3 border-black" scope="col">Category Image</th>
                    <th class="p-3 border-black" scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
               
                if ($result= $categoryDAO->get_categorys_archive()) {
                    foreach ($result as  $row ) {
                        $categoryid = $row->getCategoryId();
                        $categoryName = $row->getCategoryName();
                        $categoryImg = $row->getImageCategory();

                        echo '<tr class="justify-content-center">';
                        echo '<td class="border-black p-3">' . $categoryid . '</td>';
                        echo '<td class="border-black p-3">' . $categoryName . '</td>';
                        echo '<td class="border-black p-3"><img src="img/' . $categoryImg . '" class="w-25 h-25" alt="Product Image"></td>';
                        echo '<td class="border-black p-2">
                                 <form method="POST" class="category-action-form mx-3">
                                     <input type="hidden" name="category_name" value="' . $categoryid . '">
                                     <a href="restore_cat.php?id=' . $categoryid . '" class="btn btn-warning btn-sm w-4 text-light">Restore</a>
                                 </form>
                             </td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No categories found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    function addCategoryField() {
        const categoriesContainer = document.getElementById('categories-container');
        const newCategoryEntry = document.createElement('div');
        newCategoryEntry.classList.add('category-entry');
        newCategoryEntry.innerHTML = `
            <label for="name_category" class="form-label">Category Name</label>
            <input type="text" class="form-control" name="name_cat[]">
            <label for="img_category" class="form-label">Category Photo</label>
            <input type="file" class="form-control" name="file[]">
        `;
        categoriesContainer.appendChild(newCategoryEntry);
    }
</script>