<?php
session_start();
require_once 'conecting.php';

// Fetch all posts with user and category information
$query = "SELECT 
    articles.*, 
    users.username as author_name,
    categories.name as category_name
FROM articles
JOIN users ON articles.user_id = users.id
JOIN categories ON articles.category_id = categories.id
ORDER BY articles.created_at DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Posts</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <?php require('nav-bar.php') ?>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Blog Posts</h1>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="create_post.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Create New Post
                </a>
            <?php endif; ?>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while ($post = $result->fetch_assoc()): ?>
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <?php if ($post['image']): ?>
                            <img src="<?php echo htmlspecialchars($post['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($post['title']); ?>"
                                 class="w-full h-48 object-cover">
                        <?php endif; ?>
                        
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-blue-500 font-semibold">
                                    <?php echo htmlspecialchars($post['category_name']); ?>
                                </span>
                                <span class="text-sm text-gray-500">
                                    <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                                </span>
                            </div>
                            
                            <h2 class="text-xl font-bold text-gray-800 mb-2">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </h2>
                            
                            <p class="text-gray-600 mb-4">
                                <?php 
                                $excerpt = substr($post['content'], 0, 150);
                                echo htmlspecialchars($excerpt) . '...'; 
                                ?>
                            </p>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">
                                    By <?php echo htmlspecialchars($post['author_name']); ?>
                                </span>
                                <a href="view-post.php?id=<?php echo $post['id']; ?>" 
                                   class="text-blue-500 hover:text-blue-700 font-semibold">
                                    Read More →
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <p class="text-gray-600 text-lg">No blog posts found.</p>
            </div>
        <?php endif; ?>
    </div>

    <?php if (isset($_SESSION['user_id']) && $_SESSION['role_id'] == 1): ?>
    <div class="fixed bottom-4 right-4">
        <a href="users_mng.php" 
           class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-full shadow-lg">
            Admin Dashboard
        </a>
    </div>
    <?php endif; ?>
    <?php require('footer.php') ?>
</body>
</html>