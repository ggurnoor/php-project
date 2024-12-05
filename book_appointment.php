<?php
session_start();
include 'config.php';

// Ensure the customer is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: customer_login.php");
    exit();
}

// Fetch available cars from the database
$stmt = $pdo->query("SELECT * FROM cars WHERE availability = 1");
$cars = $stmt->fetchAll();

// Handle appointment booking
$success_message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_id = $_POST['car_id'];
    $appointment_date = $_POST['appointment_date'];
    $user_id = $_SESSION['user_id'];

    try {
        // Insert the appointment into the appointments table
        $stmt = $pdo->prepare("INSERT INTO appointments (user_id, car_id, appointment_date) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $car_id, $appointment_date]);

        // Generate success message
        $success_message = "Thank you, " . htmlspecialchars($_SESSION['username']) . "! Your appointment for the selected car has been successfully booked.";
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Appointment</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to shared CSS -->
</head>
<body>
    <header>
        <h1>Book an Appointment</h1>
    </header>
    <main>
        <!-- Display success message if the appointment is booked -->
        <?php if (!empty($success_message)): ?>
            <div class="success-message" style="text-align: center; margin: 20px 0;">
                <p style="font-size: 1.2rem; color: green;"><?= $success_message ?></p>
                <a href="index.php" class="btn">Back to Home</a>
            </div>
        <?php else: ?>
            <!-- Appointment Form -->
            <form method="POST" action="book_appointment.php" class="form-container">
                <label for="car_id">Select a Car:</label>
                <select name="car_id" id="car_id" required>
                    <?php foreach ($cars as $car): ?>
                        <option value="<?= $car['id'] ?>">
                            <?= htmlspecialchars($car['make'] . " " . $car['model'] . " - $" . number_format($car['price'], 2)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="appointment_date">Appointment Date:</label>
                <input type="date" name="appointment_date" id="appointment_date" required>

                <button type="submit">Book Appointment</button>
            </form>
        <?php endif; ?>
    </main>
</body>
</html>
