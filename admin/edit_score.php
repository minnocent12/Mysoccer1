<?php
// edit_score.php
// Enable error reporting (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $match_id = $_POST['match_id'];
    $home_score = $_POST['home_score'];
    $away_score = $_POST['away_score'];

    // Update the match scores
    $sql = "UPDATE matches SET team1_score = ?, team2_score = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $home_score, $away_score, $match_id);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error updating match score: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
