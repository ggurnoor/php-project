<?php
session_start();
include 'config.php';

// Ensure the admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}

// Fetch car details for editing
if (isset($_GET['id'])) {
    $car_id = (int)$_GET['id']; // Sanitize input
    $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
    $stmt->execute([$car_id]);
    $car = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$car) {
        die("Car not found!");
    }
}

// Handle car update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_id = (int)$_POST['id']; // Hidden input for car ID
    $make = trim($_POST['make']);
    $model = trim($_POST['model']);
    $year = (int)$_POST['year'];
    $price = (float)$_POST['price'];
    $description = trim($_POST['description']);

    try {
        // Update car details in the database
        $stmt = $pdo->prepare("UPDATE cars SET make = ?, model = ?, year = ?, price = ?, description = ? WHERE id = ?");
        $stmt->execute([$make, $model, $year, $price, $description, $car_id]);

        // Redirect back to the dashboard
        header("Location: dashboard.php");
        exit();
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Car</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to shared CSS -->
</head>
<body>
    <header>
        <h1>Edit Car</h1>
    </header>
    <main>
        <form method="POST" action="edit_car.php" class="form-container">
            <input type="hidden" name="id" value="<?= htmlspecialchars($car['id']) ?>">

            <label for="make">Make:</label>
            <input type="text" name="make" id="make" value="<?= htmlspecialchars($car['make']) ?>" required>

            <label for="model">Model:</label>
            <input type="text" name="model" id="model" value="<?= htmlspecialchars($car['model']) ?>" required>

            <label for="year">Year:</label>
            <input type="number" name="year" id="year" value="<?= htmlspecialchars($car['year'] ?? '') ?>" required>

            <label for="price">Price:</label>
            <input type="number" name="price" id="price" value="<?= htmlspecialchars($car['price']) ?>" step="0.01" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="4" required><?= htmlspecialchars($car['description'] ?? '') ?></textarea>

            <button type="submit">Update Car</button>
        </form>

        <!-- Back to Dashboard Button -->
        <div class="back-to-dashboard">
            <a href="dashboard.php" class="btn">Back to Dashboard</a>
        </div>
    </main>
</body>
</html>
