<nav class="bg-gray-800 p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="index.php" class="text-white text-2xl font-bold">V-BLOGS</a>
        <div>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="my_posts.php" class="text-white mr-4 hover:text-gray-300">My Posts</a>
                <a href="create_post.php" class="text-white mr-4 hover:text-gray-300">Create Post</a>
                <a href="logout.php" class="text-white hover:text-gray-300">Logout</a>
            <?php else: ?>
                <a href="login.php" class="text-white mr-4 hover:text-gray-300">Login</a>
                <a href="sign-up.php" class="text-white hover:text-gray-300">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>