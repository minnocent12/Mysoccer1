<?php
session_start();
require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'];
    $current_password = $_POST['currentPassword'];
    $new_password = $_POST['newPassword'];
    $confirm_password = $_POST['confirmPassword'];

    if ($new_password === $confirm_password) {
        // Verify current password
        $sql = "SELECT password FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if (password_verify($current_password, $result['password'])) {
            // Update the password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password = ? WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $hashed_password, $username);

            if ($stmt->execute()) {
                echo "Password updated successfully.";
                header("Location: admin_dashboard.php");
                exit();
            } else {
                echo "Error updating password.";
            }
        } else {
            echo "Current password is incorrect.";
        }
    } else {
        echo "New password and confirmation do not match.";
    }
}
?>
