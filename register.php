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

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } else {
        // Check if email already exists
        $stmt_email = $conn->prepare("SELECT id FROM admins WHERE email = ?");
        $stmt_email->bind_param("s", $email);
        $stmt_email->execute();
        $stmt_email->store_result();
    
        if ($stmt_email->num_rows > 0) {
            $message = "Email already registered.";
        } else {
            // Check if username already exists
            $stmt_username = $conn->prepare("SELECT id FROM admins WHERE username = ?");
            $stmt_username->bind_param("s", $username);
            $stmt_username->execute();
            $stmt_username->store_result();
            
            if ($stmt_username->num_rows > 0) {
                $message = "Username already taken.";
            } else {
                // Validate password
                if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>])[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,}$/', $password)) {
                    $message = "Password must be at least 8 characters long, include at least 1 number, 1 uppercase letter, and 1 special character.";
                } else {
                    // Proceed with registration
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt_insert = $conn->prepare("INSERT INTO admins (firstname, lastname, email, username, password) VALUES (?, ?, ?, ?, ?)");
                    $stmt_insert->bind_param("sssss", $firstname, $lastname, $email, $username, $hashed_password);
    
                    if ($stmt_insert->execute()) {
                        // Fetch the admin's id
                        $admin_id = $conn->insert_id; // This returns the ID of the last inserted record
    
                        // Store the username and admin_id in the session
                        session_regenerate_id(true);
                        $_SESSION['username'] = $username;
                        $_SESSION['admin_id'] = $admin_id;  // Store admin ID in session
                        setcookie("username", $username, time() + (86400 * 30), "/");
                        
                        header("Location: admin/admin_dashboard.php"); // Redirect to admin dashboard after successful registration
                        exit();
                    } else {
                        $message = "Error registering user: " . $stmt_insert->error;
                    }
                    $stmt_insert->close();
                }
            }
            $stmt_username->close();
        }
        $stmt_email->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register - MySoccer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<main>
    <div class="container">
        <section class="account-section">
            <h2>Your Account</h2>
            <div class="login-register-container">
                <!-- Register Form -->
                <div class="register-prompt">
                    <h3>Register</h3>
                    <?php if ($message): ?>
                        <p class="error-message"><?php echo htmlspecialchars($message); ?></p>
                    <?php endif; ?>
                    <form method="POST" action="register.php">
                        <label for="firstname">First Name:</label>
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" id="firstname" name="firstname" required>
                        </div>

                        <label for="lastname">Last Name:</label>
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" id="lastname" name="lastname" required>
                        </div>

                        <label for="email">Email:</label>
                        <div class="input-group">
                            <i class="fas fa-envelope"></i>
                            <input type="email" id="email" name="email" required>
                        </div>

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

                        <button type="submit">Register</button>
                    </form>
                </div>

                <!-- Login Prompt -->
                <div class="register-prompt">
                    <h3>Already Have an Account?</h3>
                    <p>Welcome back! Log in to access your dashboard, manage your teams, and stay updated with the latest soccer events.</p>
                    <a href="login.php" class="register-button">Login Now</a>
                </div>
            </div>
        </section>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>
