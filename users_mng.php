<?php
session_start();
require_once 'conecting.php';

// Fetch all users except the current admin
$query = "SELECT 
    users.*, 
    roles.role_name,
    (SELECT COUNT(*) FROM articles WHERE user_id = users.id) as post_count
FROM users 
LEFT JOIN roles ON users.role_id = roles.id 
WHERE users.id != ? 
ORDER BY users.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

// Handle user deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    
    // Start transaction
    $conn->begin_transaction();
    

        // Delete user's posts first (this will cascade to comments due to foreign key)
        $delete_posts = $conn->prepare("DELETE FROM articles WHERE user_id = ?");
        $delete_posts->bind_param("i", $user_id);
        $delete_posts->execute();
        
        // Delete the user
        $delete_user = $conn->prepare("DELETE FROM users WHERE id = ?");
        $delete_user->bind_param("i", $user_id);
        $delete_user->execute();
        
        // Commit transaction
        $conn->commit();
        
        $_SESSION['success_message'] = "User and all their content have been deleted successfully.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
        
  
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function confirmDelete(username) {
            return confirm(`Are you sure you want to delete user "${username}" and all their content? This action cannot be undone.`);
        }
    </script>
</head>
<body class="bg-gray-100">
    <?php require('nav-bar.php'); ?>

    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Manage Users</h1>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php 
                    echo $_SESSION['success_message'];
                    unset($_SESSION['success_message']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php 
                    echo $_SESSION['error_message'];
                    unset($_SESSION['error_message']);
                    ?>
                </div>
            <?php endif; ?>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Username
                            </th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Role
                            </th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Posts
                            </th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Joined
                            </th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php while ($user = $result->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                            <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                                        </div>
                                        <?php echo htmlspecialchars($user['username']); ?>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <?php echo htmlspecialchars($user['email']); ?>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?php echo $user['role_name'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800'; ?>">
                                        <?php echo htmlspecialchars($user['role_name']); ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <?php echo $user['post_count']; ?>
                                </td>
                                <td class="py-4 px-6">
                                    <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                                </td>
                                <td class="py-4 px-6">
                                    <form method="POST" action="" class="inline-block" 
                                          onsubmit="return confirmDelete('<?php echo htmlspecialchars($user['username']); ?>')">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <button type="submit" name="delete_user" 
                                                class="text-red-600 hover:text-red-900 mx-1">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                   
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($result->num_rows === 0): ?>
                <div class="text-center py-4 text-gray-500">
                    No users found.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php require('footer.php'); ?>
</body>
</html>