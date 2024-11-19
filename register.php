<?php
// Enable error reporting (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'includes/db_connect.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Basic validation
    if (empty($firstname) || empty($lastname) || empty($email) || empty($username) || empty($password)) {
        $message = "All fields are required.";
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Username already taken.";
        } else {
            // Check if email already exists
            $stmt_email = $conn->prepare("SELECT id FROM admins WHERE email = ?");
            $stmt_email->bind_param("s", $email);
            $stmt_email->execute();
            $stmt_email->store_result();

            if ($stmt_email->num_rows > 0) {
                $message = "Email already registered.";
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert new user
                $stmt_insert = $conn->prepare("INSERT INTO admins (firstname, lastname, email, username, password) VALUES (?, ?, ?, ?, ?)");
                $stmt_insert->bind_param("sssss", $firstname, $lastname, $email, $username, $hashed_password);

                if ($stmt_insert->execute()) {
                    session_regenerate_id(true); // Prevent session fixation
                    $_SESSION['username'] = $username;
                    setcookie("username", $username, time() + (86400 * 30), "/"); // 30 days
                    header("Location: admin/admin_dashboard.php");
                    exit();
                } else {
                    $message = "Error registering user: " . $stmt_insert->error;
                }

                $stmt_insert->close();
            }

            $stmt_email->close();
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register - MySoccer</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<main>
    <div class="container">
        <section class="account-section">
            <h2>Your Account</h2>
            <div class="login-register-container">
                <!-- Register Form -->
                <div class="register-form">
                    <h3>Register</h3>
                    <?php if ($message): ?>
                        <p class="error-message"><?php echo htmlspecialchars($message); ?></p>
                    <?php endif; ?>
                    <form method="POST" action="register.php">
                        <label for="firstname">First Name:</label>
                        <input type="text" id="firstname" name="firstname" required>

                        <label for="lastname">Last Name:</label>
                        <input type="text" id="lastname" name="lastname" required>

                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>

                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" required>

                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>

                        <button type="submit">Register</button>
                    </form>
                </div>

                <!-- Login Prompt -->
                <div class="login-prompt">
                    <h3>Already Have an Account?</h3>
                    <p>Welcome back! Log in to access your dashboard, manage your teams, and stay updated with the latest soccer events.</p>
                    <a href="login.php" class="login-button">Login Now</a>
                </div>
            </div>
        </section>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>
