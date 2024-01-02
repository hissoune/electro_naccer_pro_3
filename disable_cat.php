<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
require_once 'connex_db.php';
require_once 'categoryDAO.php';
if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $catdao = new categoryDAO();
        $catdao-> disaplay_category($id);
       
    }

    ?>
</body>

</html>