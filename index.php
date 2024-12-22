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
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Navigation Bar -->
    <?php require('nav-bar.php'); ?>

    <!-- Main Content -->
    <div class="container mx-auto mt-20 px-4 py-8">
        <!-- Featured Post -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
            <div class="md:flex">
                <div class="md:flex-shrink-0">
                    <img class="h-48 w-full object-cover md:w-48" src="https://via.placeholder.com/400x300" alt="Featured post">
                </div>
                <div class="p-8">
                    <div class="uppercase tracking-wide text-sm text-green-500 font-semibold">Featured</div>
                    <h2 class="block mt-1 text-2xl leading-tight font-bold text-gray-900">Getting Started with Web Development</h2>
                    <p class="mt-2 text-gray-600">Learn the fundamentals of web development and start your journey as a developer...</p>
                    <div class="mt-4">
                        <span class="text-gray-500">By John Doe • December 16, 2024</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Filter -->
        <div class="flex overflow-x-auto space-x-4 mb-8">
            <button class="px-4 py-2 bg-green-500 text-white rounded-full">All</button>
            <button class="px-4 py-2 bg-white text-gray-700 rounded-full hover:bg-green-500 hover:text-white">Technology</button>
            <button class="px-4 py-2 bg-white text-gray-700 rounded-full hover:bg-green-500 hover:text-white">Travel</button>
            <button class="px-4 py-2 bg-white text-gray-700 rounded-full hover:bg-green-500 hover:text-white">Food</button>
            <button class="px-4 py-2 bg-white text-gray-700 rounded-full hover:bg-green-500 hover:text-white">Lifestyle</button>
        </div>

        <!-- Posts Grid -->
        <?php if ($result->num_rows > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Post Card 1 -->
           
                <?php while ($post = $result->fetch_assoc()): ?>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <?php if ($post['image']): ?>
                <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Post image" class="w-full h-48 object-cover">
                <?php endif; ?>
                <div class="p-6">
                    <span class="text-green-500 text-sm font-semibold"><?php echo htmlspecialchars($post['category_name']); ?></span>
                    <h3 class="text-xl font-semibold mt-2"> <?php echo htmlspecialchars($post['title']); ?></h3>
                    <p class="text-gray-600 mt-2"><?php 
                                $excerpt = substr(strip_tags($post['content']), 0, 150);
                                echo htmlspecialchars($excerpt) . '...'; 
                                ?></p>
                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <img src="https://via.placeholder.com/40x40" alt="Author" class="w-8 h-8 rounded-full">
                            <span class="ml-2 text-sm text-gray-500">   By <?php echo htmlspecialchars($post['author_name']); ?></span>
                        </div>
                        <span class="text-sm text-gray-500">  <?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
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

        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            <nav class="inline-flex rounded-md shadow">
                <a href="#" class="px-3 py-2 rounded-l-md bg-white text-gray-500 hover:text-green-500">Previous</a>
                <a href="#" class="px-3 py-2 bg-green-500 text-white">1</a>
                <a href="#" class="px-3 py-2 bg-white text-gray-500 hover:text-green-500">2</a>
                <a href="#" class="px-3 py-2 bg-white text-gray-500 hover:text-green-500">3</a>
                <a href="#" class="px-3 py-2 rounded-r-md bg-white text-gray-500 hover:text-green-500">Next</a>
            </nav>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white mt-12">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">About</h3>
                    <p class="text-gray-600">A platform for sharing knowledge and stories.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Categories</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li><a href="#" class="hover:text-green-500">Technology</a></li>
                        <li><a href="#" class="hover:text-green-500">Travel</a></li>
                        <li><a href="#" class="hover:text-green-500">Food</a></li>
                        <li><a href="#" class="hover:text-green-500">Lifestyle</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Links</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li><a href="#" class="hover:text-green-500">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-green-500">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-green-500">Contact Us</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Newsletter</h3>
                    <form class="mt-4">
                        <input type="email" class="w-full px-4 py-2 rounded-lg border" placeholder="Enter your email">
                        <button class="mt-2 w-full bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">Subscribe</button>
                    </form>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-200 text-center">
                <p class="text-gray-600">&copy; 2024 BlogPlatform. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>