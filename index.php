<?php      
$dsn = "mysql:host=localhost;dbname=;charset=utf8mb4;allowPublicKeyRetrieval=true";
$pdo = new PDO($dsn, "username", "password");

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
    <nav class="bg-white shadow-lg fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between">
                <!-- Logo and Main Nav -->
                <div class="flex space-x-7">
                    <a href="#" class="flex items-center py-4 px-2">
                        <span class="font-bold text-2xl text-gray-800">BlogPlatform</span>
                    </a>
                    <div class="hidden md:flex items-center space-x-1">
                        <a href="#" class="py-4 px-2 text-green-500 border-b-4 border-green-500">Home</a>
                        <a href="#" class="py-4 px-2 text-gray-500 hover:text-green-500">Categories</a>
                        <a href="#" class="py-4 px-2 text-gray-500 hover:text-green-500">About</a>
                    </div>
                </div>
                <!-- Search Bar -->
                <div class="hidden md:flex items-center space-x-3">
                    <div class="relative">
                        <input type="text" class="bg-gray-100 rounded-full px-4 py-2 w-64" placeholder="Search...">
                        <button class="absolute right-3 top-2">
                            <i class="fas fa-search text-gray-500"></i>
                        </button>
                    </div>
                    <a href="#" class="py-2 px-4 bg-green-500 text-white rounded-full hover:bg-green-600">Login</a>
                </div>
            </div>
        </div>
    </nav>

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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Post Card 1 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <img src="https://via.placeholder.com/400x250" alt="Post image" class="w-full h-48 object-cover">
                <div class="p-6">
                    <span class="text-green-500 text-sm font-semibold">Technology</span>
                    <h3 class="text-xl font-semibold mt-2">The Future of AI</h3>
                    <p class="text-gray-600 mt-2">Exploring the latest developments in artificial intelligence and its impact...</p>
                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <img src="https://via.placeholder.com/40x40" alt="Author" class="w-8 h-8 rounded-full">
                            <span class="ml-2 text-sm text-gray-500">Jane Smith</span>
                        </div>
                        <span class="text-sm text-gray-500">5 min read</span>
                    </div>
                </div>
            </div>

            <!-- Post Card 2 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <img src="https://via.placeholder.com/400x250" alt="Post image" class="w-full h-48 object-cover">
                <div class="p-6">
                    <span class="text-green-500 text-sm font-semibold">Travel</span>
                    <h3 class="text-xl font-semibold mt-2">Hidden Gems in Paris</h3>
                    <p class="text-gray-600 mt-2">Discover the lesser-known attractions in the City of Light...</p>
                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <img src="https://via.placeholder.com/40x40" alt="Author" class="w-8 h-8 rounded-full">
                            <span class="ml-2 text-sm text-gray-500">Mark Johnson</span>
                        </div>
                        <span class="text-sm text-gray-500">8 min read</span>
                    </div>
                </div>
            </div>

            <!-- Post Card 3 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <img src="https://via.placeholder.com/400x250" alt="Post image" class="w-full h-48 object-cover">
                <div class="p-6">
                    <span class="text-green-500 text-sm font-semibold">Food</span>
                    <h3 class="text-xl font-semibold mt-2">Mediterranean Recipes</h3>
                    <p class="text-gray-600 mt-2">Delicious and healthy Mediterranean dishes you can make at home...</p>
                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <img src="https://via.placeholder.com/40x40" alt="Author" class="w-8 h-8 rounded-full">
                            <span class="ml-2 text-sm text-gray-500">Sarah Davis</span>
                        </div>
                        <span class="text-sm text-gray-500">6 min read</span>
                    </div>
                </div>
            </div>
        </div>

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