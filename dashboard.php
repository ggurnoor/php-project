<?php
session_start();
include 'config.php';

// Ensure the admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}

// Handle deletion
if (isset($_GET['delete'])) {
    $car_id = (int)$_GET['delete']; // Sanitize input
    $stmt = $pdo->prepare("DELETE FROM cars WHERE id = ?");
    $stmt->execute([$car_id]);
    header("Location: dashboard.php");
    exit();
}

// Fetch all cars
$stmt = $pdo->query("SELECT * FROM cars");
$cars = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to shared CSS -->
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
    </header>
    <main>
        <div class="car-list-container">
            <h2>Cars Inventory</h2>
            <a href="add_car.php" class="btn">Add New Car</a>
            <ul class="car-list">
                <?php foreach ($cars as $car): ?>
                    <li class="car-item">
                        <div class="car-info">
                            <h3><?= htmlspecialchars($car['make']) ?> <?= htmlspecialchars($car['model']) ?></h3>
                            <p>Year: <?= htmlspecialchars($car['year'] ?? 'N/A') ?></p>
                            <p>Price: $<?= number_format($car['price'], 2) ?></p>
                        </div>
                        <div class="car-actions">
                            <a href="edit_car.php?id=<?= $car['id'] ?>" class="btn">Edit</a>
                            <a href="?delete=<?= $car['id'] ?>" class="btn delete-btn" onclick="return confirm('Are you sure?');">Delete</a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <!-- Back to Home Button -->
        <div style="text-align: center; margin-top: 20px;">
            <a href="index.php" class="btn">Back to Home</a>
        </div>
    </main>
</body>
</html>
