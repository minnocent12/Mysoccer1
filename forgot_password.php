<?php
// Enable error reporting (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'includes/db_connect.php';

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Adjust the path if not using Composer

$message = '';
$show_reset_form = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    if (isset($_POST['send_code'])) {
        // Step 1: Verify username/email and send the code
        $identifier = trim($_POST['identifier']);

        if (empty($identifier)) {
            $message = "Please enter your username or email.";
        } else {
            // Check if user exists
            $stmt = $conn->prepare("SELECT id, email FROM admins WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $identifier, $identifier);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($user_id, $email);
                $stmt->fetch();

                // Generate a unique security code
                $security_code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
                $expires = time() + 900; // 15 minutes

                // Store the code and expiry in the database
                $stmt_update = $conn->prepare("UPDATE admins SET password_reset_code = ?, password_reset_expires = ? WHERE id = ?");
                $stmt_update->bind_param("sii", $security_code, $expires, $user_id);
                $stmt_update->execute();

                // Send the email with the security code using PHPMailer
                $mail = new PHPMailer(true);
                try {
                    // SMTP configuration
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
                    $mail->SMTPAuth = true;
                    $mail->Username = 'mirenge.innocent@gmail.com'; // SMTP username
                    $mail->Password = 'dsng tooj bszc niuj'; // SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Encryption type
                    $mail->Port = 587; // SMTP port

                    // Email content
                    $mail->setFrom('mirenge.innocent@gmail.com', 'MySoccer'); // Replace with your email
                    $mail->addAddress($email); // Add recipient
                    $mail->Subject = 'Your Password Reset Code';
                    $mail->Body = "Hi,\n\nYour password reset code is: $security_code\n\nThis code will expire in 15 minutes.\n\nIf you did not request a password reset, please ignore this email.";

                    $mail->send();
                    $message = "A security code has been sent to your email.";
                    $show_reset_form = true;
                } catch (Exception $e) {
                    $message = "Failed to send the security code. Mailer Error: {$mail->ErrorInfo}";
                }

                $stmt_update->close();
            } else {
                $message = "No user found with that username or email.";
            }
            $stmt->close();
        }
    } elseif (isset($_POST['reset_password'])) {
        // Step 2: Verify the security code and reset the password
        $security_code = trim($_POST['security_code']);
        $new_password = trim($_POST['new_password']);
    
        // Password validation function
        function isValidPassword($password) {
            return strlen($password) >= 8 &&                 // Minimum length
                   preg_match('/[A-Z]/', $password) &&       // At least one uppercase letter
                   preg_match('/[0-9]/', $password) &&       // At least one digit
                   preg_match('/[\W]/', $password);         // At least one special character
        }
    
        if (empty($security_code) || empty($new_password)) {
            $message = "Please fill out all fields.";
        } elseif (!isValidPassword($new_password)) {
            $message = "Password must be at least 8 characters long, include at least 1 number, 1 uppercase letter, and 1 special character.";
        } else {
            // Check the security code
            $stmt = $conn->prepare("SELECT id FROM admins WHERE password_reset_code = ? AND password_reset_expires >= ?");
            $current_time = time();
            $stmt->bind_param("si", $security_code, $current_time);
            $stmt->execute();
            $stmt->store_result();
    
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($user_id);
                $stmt->fetch();
    
                // Update the password
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                $stmt_update = $conn->prepare("UPDATE admins SET password = ?, password_reset_code = NULL, password_reset_expires = NULL WHERE id = ?");
                $stmt_update->bind_param("si", $hashed_password, $user_id);
                $stmt_update->execute();
    
                $message = "Your password has been reset successfully.";
                header("Location: login.php");
                exit;
            } else {
                $message = "Invalid or expired security code.";
            }
            $stmt->close();
        }
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - MySoccer</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<main>
    <div class="container">
        <section class="account-section">

            <h2>Forgot Password</h2>
            <div class="register-prompt">
                <?php if ($message): ?>
                    <p class="<?php echo strpos($message, 'successfully') !== false ? 'success-message' : 'error-message'; ?>"><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>

                <?php if (!$show_reset_form): ?>
                    <!-- Step 1: Request Username or Email -->
                    <form method="POST" action="forgot_password.php">
                        <label for="identifier">Enter your username or email:</label>
                        <input type="text" id="identifier" name="identifier" required>
                        <button type="submit" name="send_code">Send Code</button>
                    </form>
                <?php else: ?>
                    <!-- Step 2: Reset Password -->
                    <form method="POST" action="forgot_password.php">
                        <label for="security_code">Security Code:</label>
                        <input type="text" id="security_code" name="security_code" required>

                        <label for="new_password">New Password:</label>
                        <input type="password" id="new_password" name="new_password" required>

                        <button type="submit" name="reset_password">Reset Password</button>
                    </form>
                <?php endif; ?>  


            </div>
            


            
        </section>
    </div>

</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>
