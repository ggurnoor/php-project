<?php
include 'config.php';

$search = $_GET['search'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM cars WHERE make LIKE ?");
$stmt->execute(["%$search%"]);
$cars = $stmt->fetchAll();

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Car Dealership</title>
    <link rel='stylesheet' href='styles.css'>
</head>
<body>
    <header>
        <h1>Welcome to Car Dealership</h1>
        <form method='GET' action='index.php'>
            <input type='text' name='search' placeholder='Search by make'>
            <button type='submit'>Search</button>
        </form>
        <nav>
            <a href='register.php'>Register</a>
            <a href='customer_login.php'>Customer Login</a>
            <a href='admin.php'>Admin Login</a>
            <a href='reviews.php'>Customer Reviews</a>
        </nav>
    </header>
    <main>
        <div class='inventory'>";
foreach ($cars as $car) {
    echo "<div class='car'>
        <img src='{$car['image_path']}' alt='{$car['make']}'>
        <h2>{$car['make']} {$car['model']}</h2>
        <p>\${$car['price']}</p>
        <a href='car_details.php?id={$car['id']}'>View More</a>
    </div>";
}
echo "</div>
    </main>
</body>
</html>";
?>

