<?php
session_start();
require_once 'conecting.php';
require_once 'functions.php';

if (!isset($_GET['id'])) {
    header('Location: my_posts.php');
    exit;
}

$post_id = intval($_GET['id']);

// Handle post deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_post') {
    // Check if user is admin or post owner
    $can_delete = false;
    if (isAdmin()) {
        $can_delete = true;
    } else if (isLoggedIn()) {
        $check_owner = $conn->prepare("SELECT user_id FROM articles WHERE id = ?");
        $check_owner->bind_param("i", $post_id);
        $check_owner->execute();
        $result = $check_owner->get_result();
        if ($row = $result->fetch_assoc()) {
            $can_delete = ($row['user_id'] === $_SESSION['user_id']);
        }
    }

    if ($can_delete) {
        // Delete associated comments
        $delete_comments = $conn->prepare("DELETE FROM comments WHERE article_id = ?");
        $delete_comments->bind_param("i", $post_id);
        $delete_comments->execute();

        // Delete associated likes
        $delete_likes = $conn->prepare("DELETE FROM likes WHERE article_id = ?");
        $delete_likes->bind_param("i", $post_id);
        $delete_likes->execute();

        // Delete the post
        $delete_post = $conn->prepare("DELETE FROM articles WHERE id = ?");
        $delete_post->bind_param("i", $post_id);
        $delete_post->execute();

        // Redirect to posts page
        header('Location: my_posts.php');
        exit;
    }
}

// Fetch article with author and category
$query = "SELECT 
    articles.*, 
    users.username as author_name,
    categories.name as category_name,
    (SELECT COUNT(*) FROM likes WHERE article_id = articles.id) as likes_count,
    (SELECT COUNT(*) FROM likes WHERE article_id = articles.id AND user_id = ?) as user_liked
FROM articles
JOIN users ON articles.user_id = users.id
JOIN categories ON articles.category_id = categories.id
WHERE articles.id = ?";

$stmt = $conn->prepare($query);
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$stmt->bind_param("ii", $user_id, $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    header('Location: my_posts.php');
    exit;
}

// Fetch comments
$comment_query = "SELECT 
    comments.*,
    COALESCE(users.username, comments.visitor_name) as commenter_name
FROM comments
LEFT JOIN users ON comments.user_id = users.id
WHERE article_id = ?
ORDER BY created_at DESC";

$stmt = $conn->prepare($comment_query);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$comments = $stmt->get_result();

// Handle new comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'comment') {
        $content = trim($_POST['content']);
        if (!empty($content)) {
            $comment_insert = "INSERT INTO comments (article_id, user_id, visitor_name, visitor_email, content) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($comment_insert);
            
            if (isset($_SESSION['user_id'])) {
                $visitor_name = null;
                $visitor_email = null;
                $stmt->bind_param("iisss", $post_id, $_SESSION['user_id'], $visitor_name, $visitor_email, $content);
            } else {
                $user_id = null;
                $visitor_name = $_POST['visitor_name'];
                $visitor_email = $_POST['visitor_email'];
                $stmt->bind_param("iisss", $post_id, $user_id, $visitor_name, $visitor_email, $content);
            }
            
            $stmt->execute();
            header("Location: view-post.php?id=" . $post_id);
            exit;
        }
    } elseif ($_POST['action'] === 'toggle_like' && isset($_SESSION['user_id'])) {
        if ($post['user_liked']) {
            $like_query = "DELETE FROM likes WHERE article_id = ? AND user_id = ?";
        } else {
            $like_query = "INSERT INTO likes (article_id, user_id) VALUES (?, ?)";
        }
        $stmt = $conn->prepare($like_query);
        $stmt->bind_param("ii", $post_id, $_SESSION['user_id']);
        $stmt->execute();
        
        // Return JSON response for AJAX request
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'liked' => !$post['user_liked']]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100">
    <?php require('nav-bar.php'); ?>
    
    <div class="container mx-auto px-4 py-8">
        <!-- Back button -->
        <a href="my_posts.php" class="inline-block mb-6 text-blue-500 hover:text-blue-700">
            ← Back to Posts
        </a>

        <!-- Article section -->
        <article class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
            <?php if ($post['image']): ?>
                <img src="<?php echo htmlspecialchars($post['image']); ?>" 
                     alt="<?php echo htmlspecialchars($post['title']); ?>"
                     class="w-full h-64 object-cover">
            <?php endif; ?>
            
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-blue-500 font-semibold">
                            <?php echo htmlspecialchars($post['category_name']); ?>
                        </span>
                        <?php if (isAdmin() || (isLoggedIn() && $post['user_id'] === $_SESSION['user_id'])): ?>
                            <div class="flex space-x-2">
                                <?php if (isAdmin() || $post['user_id'] === $_SESSION['user_id']): ?>
                                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');" class="inline">
                                        <input type="hidden" name="action" value="delete_post">
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <span class="text-sm text-gray-500">
                        <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                    </span>
                </div>
                
                <h1 class="text-3xl font-bold text-gray-800 mb-4">
                    <?php echo htmlspecialchars($post['title']); ?>
                </h1>
                
                <div class="prose max-w-none mb-6">
                    <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                </div>
                
                <div class="flex justify-between items-center border-t pt-4">
                    <span class="text-gray-600">
                        By <?php echo htmlspecialchars($post['author_name']); ?>
                    </span>
                    
                    <!-- Like button -->
                    <?php if (isLoggedIn()): ?>
                        <div class="flex items-center gap-2">
                            <button onclick="toggleLike()" class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" 
                                     class="h-6 w-6 <?php echo $post['user_liked'] ? 'text-red-500' : 'text-gray-400'; ?>" 
                                     fill="<?php echo $post['user_liked'] ? 'currentColor' : 'none'; ?>"
                                     viewBox="0 0 24 24" 
                                     stroke="currentColor">
                                    <path stroke-linecap="round" 
                                          stroke-linejoin="round" 
                                          stroke-width="2" 
                                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <span id="likes-count"><?php echo $post['likes_count']; ?></span>
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="flex items-center gap-2">
                            <span class="text-gray-400"><?php echo $post['likes_count']; ?> likes</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </article>

        <!-- Comments section -->
        <section class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Comments</h2>
            
            <!-- Comment form - show different versions for logged-in users and visitors -->
            <?php if (isLoggedIn()): ?>
                <form method="POST" class="mb-8">
                    <input type="hidden" name="action" value="comment">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Your Comment</label>
                        <textarea name="content" required
                                  class="w-full px-3 py-2 border rounded-lg"
                                  rows="4"></textarea>
                    </div>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Post Comment
                    </button>
                </form>
            <?php else: ?>
                <form method="POST" class="mb-8">
                    <input type="hidden" name="action" value="comment">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="visitor_name" required
                                   class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="visitor_email" required
                                   class="w-full px-3 py-2 border rounded-lg">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Your Comment</label>
                        <textarea name="content" required
                                  class="w-full px-3 py-2 border rounded-lg"
                                  rows="4"></textarea>
                    </div>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Post Comment as Guest
                    </button>
                </form>
            <?php endif; ?>

            <!-- Comments list -->
            <div class="space-y-6">
                <?php while ($comment = $comments->fetch_assoc()): ?>
                    <div class="border-b pb-6 last:border-b-0">
                        <div class="flex justify-between items-start mb-2">
                            <span class="font-semibold">
                                <?php echo htmlspecialchars($comment['commenter_name']); ?>
                                <?php if ($comment['user_id']): ?>
                                    <span class="text-blue-500 text-sm">(User)</span>
                                <?php else: ?>
                                    <span class="text-gray-500 text-sm">(Guest)</span>
                                <?php endif; ?>
                            </span>
                            <span class="text-sm text-gray-500">
                                <?php echo date('M d, Y', strtotime($comment['created_at'])); ?>
                            </span>
                        </div>
                        <p class="text-gray-600">
                            <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                        </p>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    </div>

    <script>
    function toggleLike() {
       

        $.post('view-post.php?id=<?php echo $post_id; ?>', {
            action: 'toggle_like'
        }, function(response) {
            if (response.success) {
                const heartIcon = document.querySelector('button svg');
                const likesCount = document.getElementById('likes-count');
                const currentLikes = parseInt(likesCount.textContent);
                
                if (response.liked) {
                    heartIcon.classList.remove('text-gray-400');
                    heartIcon.classList.add('text-red-500');
                    heartIcon.setAttribute('fill', 'currentColor');
                    likesCount.textContent = currentLikes + 1;
                } else {
                    heartIcon.classList.remove('text-red-500');
                    heartIcon.classList.add('text-gray-400');
                    heartIcon.setAttribute('fill', 'none');
                    likesCount.textContent = currentLikes - 1;
                }
            }
        });
    }
    </script>
</body>
</html>
