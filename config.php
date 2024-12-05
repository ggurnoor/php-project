<?php
define('DB_DSN', 'mysql:host=localhost;dbname=car_dealership;charset=utf8');
define('DB_USER', 'root'); // Update with your database username
define('DB_PASS', ''); // Update with your database password

try {
    $pdo = new PDO(DB_DSN, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connection successful!";
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
