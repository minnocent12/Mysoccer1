<?php
// edit_team.php
// Enable error reporting (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $team_id = $_POST['team_id'];
    $tournament_id = $_POST['tournament_id'];
    $team_name = $_POST['teamName'];
    $coach_name = $_POST['coachName'];

    // Handle logo upload if a new file is provided
    if (isset($_FILES['teamLogo']) && $_FILES['teamLogo']['error'] === UPLOAD_ERR_OK) {
        $logo = $_FILES['teamLogo']['name'];
        $target_dir = "../uploads/teams/";
        $target_file = $target_dir . basename($logo);
        move_uploaded_file($_FILES['teamLogo']['tmp_name'], $target_file);
        
        // Update with new logo
        $sql = "UPDATE teams SET tournament_id = ?, name = ?, logo = ?, coach_name = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssi", $tournament_id, $team_name, $logo, $coach_name, $team_id);
    } else {
        // Update without changing logo
        $sql = "UPDATE teams SET tournament_id = ?, name = ?, coach_name = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issi", $tournament_id, $team_name, $coach_name, $team_id);
    }

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error updating team: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
