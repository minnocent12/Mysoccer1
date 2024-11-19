<?php
// add_match.php
// Enable error reporting (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tournament_id = $_POST['tournamentMatch'];
    $homeTeam = $_POST['homeTeam'];
    $awayTeam = $_POST['awayTeam'];
    $matchDate = $_POST['matchDate'];
    $matchTime = $_POST['matchTime'];
    $location = $_POST['location']; // Make sure this is set correctly

    // Prepare and execute the SQL statement
    $sql = "INSERT INTO matches (tournament_id, team1_id, team2_id, match_date, match_time, location) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiisss", $tournament_id, $homeTeam, $awayTeam, $matchDate, $matchTime, $location); // Corrected here
    $stmt->execute();

    // Redirect back to dashboard
    header("Location: admin_dashboard.php");
    exit();
}


?>
