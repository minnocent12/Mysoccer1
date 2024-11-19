<?php
// Enable error reporting (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'includes/db_connect.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);

    // Basic validation
    if(empty($username)){
        $message = "Please enter your username.";
    } else {
        // Check if user exists
        $stmt = $conn->prepare("SELECT email FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows > 0){
            $stmt->bind_result($email);
            $stmt->fetch();

            // Generate a password reset token (simple example)
            $token = bin2hex(random_bytes(50));
            $expires = date("U") + 1800; // 30 minutes

            // Store token in the database (add 'password_reset_token' and 'password_reset_expires' columns to 'admins' table)
            $stmt_update = $conn->prepare("UPDATE admins SET password_reset_token = ?, password_reset_expires = ? WHERE username = ?");
            $stmt_update->bind_param("sis", $token, $expires, $username);
            $stmt_update->execute();

            // Send password reset email (ensure mail settings are configured)
            $reset_link = "http://localhost/mysoccer/reset_password.php?token=" . $token;
            $subject = "Password Reset Request";
            $body = "Hi $username,\n\nPlease click the link below to reset your password:\n$reset_link\n\nThis link will expire in 30 minutes.\n\nIf you did not request a password reset, please ignore this email.";

            if(mail($email, $subject, $body)){
                $message = "A password reset link has been sent to your email.";
            } else {
                $message = "Failed to send password reset email.";
            }

            $stmt_update->close();
        } else {
            $message = "No user found with that username.";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - MySoccer</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<main>
    <div class="container">
        <section class="forgot-password-section">
            <h2>Forgot Password</h2>
            <?php if($message): ?>
                <p class="<?php echo strpos($message, 'sent') !== false ? 'success-message' : 'error-message'; ?>"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <form method="POST" action="forgot_password.php">
                <label for="username">Enter your username:</label>
                <input type="text" id="username" name="username" required>

                <button type="submit">Send Reset Link</button>
            </form>
        </section>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>
