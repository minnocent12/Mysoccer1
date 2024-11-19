<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../includes/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// Check if message ID is provided
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message_id'])) {
    $message_id = $_POST['message_id'];

    // Delete the message
    $sql = "DELETE FROM messages WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $message_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Message deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Error deleting message: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
header("Location: manage_messages.php");
exit();
?>
