<?php
function isAdmin() {
    return isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1; 
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Redirect if not admin
function requireAdmin() {
    if (!isAdmin()) {
        header("Location: log-in.php");
        exit();
    }
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: log-in.php");
        exit();
    }
}


function getImageUrl($imagePath) {
    if (empty($imagePath)) {
        return 'placeholder.jpg';
    }
    
    // Check if the image path is absolute or relative
    if (strpos($imagePath, 'http') === 0) {
        return $imagePath;
    }
    
    // Convert backslashes to forward slashes
    $imagePath = str_replace('\\', '/', $imagePath);
    
    // Remove any leading slash
    $imagePath = ltrim($imagePath, '/');
    
    // Get the base URL of your website
    $baseUrl = sprintf(
        "%s://%s%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME'],
        dirname($_SERVER['SCRIPT_NAME'])
    );
    
    // Remove any trailing slash from base URL
    $baseUrl = rtrim($baseUrl, '/');
    
    return $baseUrl . '/' . $imagePath;
}
?>