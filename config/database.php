<?php
// Database Configuration
$host = "localhost";      // Change if needed
$dbname = "smart_parent_teacher"; // Database name
$username = "root";       // Database username
$password = "";           // Database password (leave empty for XAMPP)

// Create a PDO database connection
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>
