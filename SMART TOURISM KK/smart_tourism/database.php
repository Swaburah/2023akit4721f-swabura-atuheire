<?php
// database.php (or similar)

$host = 'localhost';
$dbname = 'smart_tourism';  // your database name
$username = 'root';         // your MySQL username
$password = '';             // your MySQL password (empty by default in XAMPP)

try {
    // Create PDO instance and set options
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error handling
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
