<?php
session_start();
include 'config.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Redirect to login page if customer is not logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    header("Location: customer_login.php?redirect=reviews.php");
    exit();
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $car_id = (int) $_POST['car_id'];
    $comment = trim($_POST['comment']);
    $captcha = trim($_POST['captcha']);

    // Validate CAPTCHA and comment
    if (!isset($_SESSION['captcha']) || $_SESSION['captcha'] !== $captcha) {
        $error_message = "CAPTCHA validation failed. Please try again.";
    } elseif (empty($comment)) {
        $error_message = "Comment cannot be empty.";
    } else {
        // Insert the comment into the database
        try {
            $stmt = $pdo->prepare("INSERT INTO comments (user_id, car_id, comment) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $car_id, $comment]);
            $success_message = "Comment submitted successfully!";
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }

        // Clear the form and regenerate CAPTCHA
        unset($_SESSION['captcha']);
        header('Location: reviews.php');
        exit();
    }
}

// Fetch all comments
$stmt = $pdo->query("SELECT c.comment, u.username, cr.make, cr.model FROM comments c JOIN users u ON c.user_id = u.id JOIN cars cr ON c.car_id = cr.id ORDER BY c.id DESC");
$comments = $stmt->fetchAll();

// Fetch all cars for dropdown
$cars_stmt = $pdo->query("SELECT id, make, model FROM cars");
$cars = $cars_stmt->fetchAll();

// Generate a new CAPTCHA
$captcha_code = "AB1234"; // Fixed CAPTCHA code
$_SESSION['captcha'] = $captcha_code;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Reviews</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Customer Reviews</h1>
    </header>
    <main>
        <!-- Display Comments -->
        <section class="comments-section">
            <h2>What Our Customers Say</h2>
            <ul class="comments-list">
                <?php foreach ($comments as $comment): ?>
                    <li class="comment-item">
                        <p>
                            <strong><?= htmlspecialchars($comment['username']) ?>:</strong>
                            <?= htmlspecialchars($comment['comment']) ?>
                            <br>
                            <em>Car: <?= htmlspecialchars($comment['make'] . " " . $comment['model']) ?></em>
                        </p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <!-- Comment Form -->
        <section class="comment-form-section">
            <h2>Add Your Comment</h2>
            <?php if (isset($error_message)) echo "<p style='color: red;'>$error_message</p>"; ?>
            <?php if (isset($success_message)) echo "<p style='color: green;'>$success_message</p>"; ?>
            <form method="POST" action="reviews.php" class="form-container">
                <label for="car_id">Select a Car:</label>
                <select name="car_id" id="car_id" required>
                    <?php foreach ($cars as $car): ?>
                        <option value="<?= $car['id'] ?>"><?= htmlspecialchars($car['make'] . " " . $car['model']) ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="comment">Your Comment:</label>
                <textarea name="comment" id="comment" rows="4" required></textarea>

                <label for="captcha">Enter CAPTCHA: <strong><?= $_SESSION['captcha'] ?></strong></label>
                <input type="text" id="captcha" name="captcha" required>

                <button type="submit">Submit Comment</button>
            </form>
        </section>
    </main>
</body>
</html>
