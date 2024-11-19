<?php
session_start();
require 'includes/db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
    $stmt->bind_param("ss", $new_password, $username);
    if ($stmt->execute()) {
        $message = "Settings updated successfully!";
    } else {
        $message = "Failed to update settings.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - MySoccer</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<?php include 'includes/admin_header.php'; ?>

<main>
    <div class="container">
        <h2>Settings</h2>
        <?php if($message): ?>
            <p class="success-message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="POST" action="admin_settings.php">
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>

            <button type="submit">Update Settings</button>
        </form>
    </div>
</main>

</body>
</html>
