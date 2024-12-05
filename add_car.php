<?php
session_start();
include 'config.php';

// Ensure only admin can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $make = trim($_POST['make']);
    $model = trim($_POST['model']);
    $year = (int) $_POST['year'];
    $price = (float) $_POST['price'];
    $image_path = trim($_POST['image_path']);
    $description = trim($_POST['description']);

    // Insert the new car into the database
    $stmt = $pdo->prepare("INSERT INTO cars (make, model, year, price, image_path, description, availability) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$make, $model, $year, $price, $image_path, $description, 1]);

    // Redirect back to the dashboard
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Car</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header><h1>Add New Car</h1></header>
    <main>
        <form method="POST" action="add_car.php" class="form-container">
            <label for="make">Make:</label>
            <input type="text" name="make" id="make" required>

            <label for="model">Model:</label>
            <input type="text" name="model" id="model" required>

            <label for="year">Year:</label>
            <input type="number" name="year" id="year" min="1900" max="2100" required>

            <label for="price">Price:</label>
            <input type="number" name="price" id="price" step="0.01" required>

            <label for="image_path">Image Path:</label>
            <input type="text" name="image_path" id="image_path" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="4" required></textarea>

            <button type="submit">Add Car</button>
        </form>
    </main>
</body>
</html>
