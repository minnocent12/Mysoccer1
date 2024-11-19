<?php
// delete_match.php
// Enable error reporting (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../includes/db_connect.php';

if (isset($_GET['id'])) {
    $match_id = intval($_GET['id']);

    // Optional: Check if match exists before deletion
    $check_sql = "SELECT * FROM matches WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $match_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Delete the match
        $delete_sql = "DELETE FROM matches WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $match_id);

        if ($delete_stmt->execute()) {
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "Error deleting match: " . $delete_stmt->error;
        }

        $delete_stmt->close();
    } else {
        echo "Match not found.";
    }

    $check_stmt->close();
}

$conn->close();
?>
