<!-- Content Area -->
<div class="container mx-auto mt-5 p-6 bg-white shadow-md rounded-md">

    <h2 class="text-2xl font-semibold mb-4">Category Management</h2>

    <div>
        <a href="add_category.php" class='text-green-500'>Add</a>
    </div>

    <div>
        <h3 class="text-lg font-semibold mb-2">Category List</h3>
        <table class="w-full border">
            <thead>
                <tr>
                    <th class="border p-2">Category ID</th>
                    <th class="border p-2">Category Name</th>
                    <th class="border p-2">Image</th>
                    <th class="border p-2">Status</th>
                    <th class="border p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Retrieve categories from the database and display them in the table
                $categoryDAO = new CategoryDAO(); // Replace with your actual CategoryDAO class
                $categories = $categoryDAO->getAllCategories();

                foreach ($categories as $category) {
                    echo "<tr>";
                    echo "<td class='border p-2'>{$category->getCategoryId()}</td>";
                    echo "<td class='border p-2'>{$category->getCategoryName()}</td>";
                    echo "<td class='border p-2'><img src='{$category->getImagCategory()}' alt='{$category->getCategoryName()}' class='w-16 h-16'></td>";
                    $disabledText = ($category->isDisabled()) ? '<p class="text-orange-500 hover:underline">Disable</p>' : '<p class="text-green-500 hover:underline">Enabled</p>';
                    echo "<td class='border p-2'>{$disabledText}</td>";
                    echo "<td class='border p-2'>";
                    echo "<a href='edit_category.php?id={$category->getCategoryId()}' class='text-blue-500'>Edit</a>";
                    echo " | ";
                    if ($category->isDisabled()) {
                        echo "<a href='enable_category.php?id={$category->getCategoryId()}' class='text-green-500 hover:underline'>Enable</a>";
                    } else {
                        echo "<a href='disable_category.php?id={$category->getCategoryId()}' class='text-orange-500 hover:underline'>Disable</a>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</div>