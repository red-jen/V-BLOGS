<?php
$host = 'localhost';
$user = 'root';
$password = 'Ren-ji24';
$database = 'blog_systeme';

// Connexion
$conn = new mysqli($host, $user, $password, $database);

// Vérification
if ($conn->connect_error) {
    die("Échec de connexion : " . $conn->connect_error);
}

?>
