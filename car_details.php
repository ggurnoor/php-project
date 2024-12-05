<?php
include 'config.php';

$car_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->execute([$car_id]);
$car = $stmt->fetch();

if (!$car) {
    die("Car not found!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($car['make']) ?> Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header><h1>Car Details</h1></header>
    <div class="car-details">
        <h2><?= htmlspecialchars($car['make']) ?> <?= htmlspecialchars($car['model']) ?></h2>
        <p>Year: <?= $car['year'] ?></p>
        <p>Price: $<?= number_format($car['price'], 2) ?></p>
        <p>Description: <?= htmlspecialchars($car['description']) ?></p>
        <a href="index.php" class="btn">Back to Inventory</a>
    </div>
</body>
</html>
