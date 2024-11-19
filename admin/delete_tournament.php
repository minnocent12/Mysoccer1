<?php
// delete_tournament.php
// Enable error reporting (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../includes/db_connect.php';

if (isset($_GET['id'])) {
    $tournament_id = intval($_GET['id']);

    // Optional: Check if tournament exists before deletion
    $check_sql = "SELECT * FROM tournaments WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $tournament_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // First, delete any standings related to this tournament in standings_history
        $delete_standings_sql = "DELETE FROM standings_history WHERE tournament_id = ?";
        $delete_standings_stmt = $conn->prepare($delete_standings_sql);
        $delete_standings_stmt->bind_param("i", $tournament_id);

        if ($delete_standings_stmt->execute()) {
            // Now delete the tournament
            $delete_tournament_sql = "DELETE FROM tournaments WHERE id = ?";
            $delete_tournament_stmt = $conn->prepare($delete_tournament_sql);
            $delete_tournament_stmt->bind_param("i", $tournament_id);

            if ($delete_tournament_stmt->execute()) {
                header("Location: admin_dashboard.php");
                exit();
            } else {
                echo "Error deleting tournament: " . $delete_tournament_stmt->error;
            }

            $delete_tournament_stmt->close();
        } else {
            echo "Error deleting standings: " . $delete_standings_stmt->error;
        }

        $delete_standings_stmt->close();
    } else {
        echo "Tournament not found.";
    }

    $check_stmt->close();
}

$conn->close();
?>
