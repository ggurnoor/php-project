<?
// Validate CAPTCHA
    if (!isset($_SESSION['captcha']) || $_SESSION['captcha'] !== $captcha) {
        $error = "CAPTCHA validation failed. Please try again.";
    } elseif (empty($comment)) {
        $error = "Comment cannot be empty.";
    } else {
        // Insert the review into the database
        $sql = "INSERT INTO reviews (bike_id, user_id, comment) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$bike_id, $user_id, $comment]);

        // Clear the form and CAPTCHA
        unset($_SESSION['captcha']);
        header('Location: review.php');
        exit();
    }
}

// Generate a new CAPTCHA
$captcha_code = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"), 0, 6);
$_SESSION['captcha'] = $captcha_code;
?>


 <label for="captcha">Enter CAPTCHA: <strong><?= $_SESSION['captcha'] ?></strong></label>
            <input type="text" id="captcha" name="captcha" required>
