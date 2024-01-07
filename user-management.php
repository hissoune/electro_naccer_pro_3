<!-- Content Area -->
<div class="container mx-auto mt-5 p-6 bg-white shadow-md rounded-md">

    <h2 class="text-2xl font-semibold mb-4">User Management</h2>

    <!-- User Table -->

    <div>
        <h3 class="text-lg font-semibold mb-2">User List</h3>
        <table class="w-full border">
            <thead>
                <tr>
                    <th class="border p-2">Username</th>
                    <th class="border p-2">Email</th>
                    <th class="border p-2">Role</th>
                    <th class="border p-2">Verified</th>
                    <th class="border p-2">Status</th>
                    <th class="border p-2">Actions</th> <!-- New column for actions -->
                </tr>
            </thead>
            <tbody>
                <?php
                // Retrieve users from the database and display them in the table
                $userDAO = new UserDAO();
                $users = $userDAO->getAllUsers();

                foreach ($users as $user) {
                    echo "<tr>";
                    echo "<td class='border p-2'>{$user['username']}</td>";
                    echo "<td class='border p-2'>{$user['email']}</td>";
                    echo "<td class='border p-2'>{$user['role']}</td>";
                    $verifiedText = ($user['verified'] == 1) ? 'Yes' : 'No';
                    echo "<td class='border p-2'>{$verifiedText}</td>";
                    $statusText = ($user['disabled'] == 1) ? 'Yes' : 'No';
                    echo "<td class='border p-2'>{$statusText}</td>";
                    echo "<td class='border p-2'>";
                    echo "<a href='edit_user.php?id={$user['user_id']}' class='text-blue-500'>Edit</a>";
                    echo " | ";

                    if ($user['disabled']) {
                        echo "<a href='enable_user.php?id={$user['user_id']}' class='text-green-500 hover:underline'>Enable</a>";
                    } else {
                        echo "<a href='disable_user.php?id={$user['user_id']}' class='text-orange-500 hover:underline'>Disable</a>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</div>