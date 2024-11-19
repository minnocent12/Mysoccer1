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

    // Begin a transaction to ensure both deletes are done atomically
    $conn->begin_transaction();

    try {
        // Delete the message
        $sql = "DELETE FROM messages WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $message_id);
        $stmt->execute();

        // Now, delete all messages that were replies to the deleted message
        $sql = "DELETE FROM messages WHERE replied_to = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $message_id);
        $stmt->execute();

        // If both deletes succeed, commit the transaction
        $conn->commit();

        $_SESSION['success_message'] = "Message and related replies deleted successfully.";
    } catch (Exception $e) {
        // If there is an error, roll back the transaction
        $conn->rollback();

        $_SESSION['error_message'] = "Error deleting message and replies: " . $e->getMessage();
    }

    // Close the statement
    $stmt->close();
}

$conn->close();
header("Location: manage_messages.php");
exit();
?>
