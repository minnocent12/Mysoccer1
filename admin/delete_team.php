<?php
// delete_team.php
// Enable error reporting (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../includes/db_connect.php';

if (isset($_GET['id'])) {
    $team_id = intval($_GET['id']);

    // Check if the team exists and fetch its tournament_id
    $check_sql = "SELECT tournament_id FROM teams WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $team_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $team = $check_result->fetch_assoc();
        $tournament_id = $team['tournament_id'];

        // Delete the team
        $delete_sql = "DELETE FROM teams WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $team_id);

        if ($delete_stmt->execute()) {
            // Decrement the register field in the tournaments table
            $update_sql = "UPDATE tournaments SET register = register - 1 WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $tournament_id);
            $update_stmt->execute();
            $update_stmt->close();

            // Redirect to dashboard or show success message
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "Error deleting team: " . $delete_stmt->error;
        }

        $delete_stmt->close();
    } else {
        echo "Team not found.";
    }

    $check_stmt->close();
}

$conn->close();
?>
