<?php
include 'config.php';

$success_message = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$username, $password, $role]);

        // Set success message to true to display it
        $success_message = true;
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to shared CSS file -->
</head>
<body>
    <header>
        <h1>Register</h1>
    </header>
    <main>
        <?php if ($success_message): ?>
            <div class="success-container" style="text-align: center; margin-top: 20px;">
                <p style="color: green; font-size: 1.2rem;">Registration successful!</p>
                <a href="index.php" class="btn" style="display: inline-block; padding: 10px 20px; background-color: #3F402E; color: #E8E9EC; text-decoration: none; border-radius: 4px;">Go to Home</a>
            </div>
        <?php else: ?>
            <form method="POST" action="register.php" class="form-container">
                <label>Username:</label>
                <input type="text" name="username" required>
                <label>Password:</label>
                <input type="password" name="password" required>
                <label>Role:</label>
                <select name="role" required>
                    <option value="admin">Admin</option>
                    <option value="customer">Customer</option>
                </select>
                <button type="submit">Register</button>
            </form>
        <?php endif; ?>
    </main>
</body>
</html>
