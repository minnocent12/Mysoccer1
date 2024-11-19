<?php
// edit_match.php
// Enable error reporting (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $match_id = $_POST['match_id'];
    $tournament_id = $_POST['tournament_id'];
    $home_team_id = $_POST['home_team_id'];
    $away_team_id = $_POST['away_team_id'];
    $match_date = $_POST['match_date'];
    $match_time = $_POST['match_time']; // Add this line
    $location = $_POST['location'];

    // Update the match
    $sql = "UPDATE matches SET tournament_id = ?, team1_id = ?, team2_id = ?, match_date = ?, match_time = ?, location = ? WHERE id = ?"; // Modify this line
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiisssi", $tournament_id, $home_team_id, $away_team_id, $match_date, $match_time, $location, $match_id); // Modify this line

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error updating match: " . $stmt->error;
    }

    $stmt->close();
}

?>
