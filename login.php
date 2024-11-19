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
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if($stmt->num_rows > 0){
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify password
        if(password_verify($password, $hashed_password)){
            session_regenerate_id(true); // Prevent session fixation
            $_SESSION['username'] = $username;
            setcookie("username", $username, time() + (86400 * 30), "/"); // 30 days
            header("Location: admin/admin_dashboard.php"); // Redirect to admin dashboard after successful login
            exit();
        }
         else {
            $message = "Invalid password.";
        }
    } else {
        $message = "No user found.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login - MySoccer</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<main>
    <div class="container">
        <section class="account-section">
            <h2>Your Account</h2>
            <div class="login-register-container">
                <!-- Login Form -->
                <div class="login-form">
                    <h3>Login</h3>
                    <?php if($message): ?>
                        <p class="error-message"><?php echo htmlspecialchars($message); ?></p>
                    <?php endif; ?>
                    <form method="POST" action="login.php" onsubmit="return validateLoginForm()">
                        <label for="username">Username:</label>
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" id="username" name="username" required>
                        </div>

                        <label for="password">Password:</label>
                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="password" required>
                        </div>

                        <button type="submit">Login</button>
                    </form>
                    <p class="forgot-password"><a href="forgot_password.php">Forgot login details?</a></p>
                </div>

                <!-- Register Prompt -->
                <div class="register-prompt">
                    <h3>New to MySoccer?</h3>
                    <p>Create an account to stay updated with the latest tournaments, leagues, and news. Enjoy exclusive features and personalized content tailored just for you.</p>
                    <a href="register.php" class="register-button">Register Now</a>
                </div>
            </div>
        </section>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<!-- JavaScript for Form Validation -->
<script src="js/scripts.js"></script>
</body>
</html>
