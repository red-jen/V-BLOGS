<?php
function isAdmin() {
    return isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1; // Assuming 1 is admin role
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
?>