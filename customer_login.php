<?php
session_start();
include 'config.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare and execute the query
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists and verify password
        if ($user && password_verify($password, $user['password']) && $user['role'] === 'customer') {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect to the book appointment page
            header("Location: book_appointment.php");
            exit();
        } else {
            // Invalid credentials
            $error_message = "Invalid username or password!";
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Login</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to shared CSS file -->
</head>
<body>
    <header>
        <h1>Customer Login</h1>
    </header>
    <main>
        <form method="POST" action="customer_login.php" class="form-container">
            <?php if (isset($error_message)) echo "<p style='color: red;'>$error_message</p>"; ?>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            
            <button type="submit">Login</button>
        </form>
    </main>
</body>
</html>
