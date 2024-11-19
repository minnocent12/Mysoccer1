<?php
// add_team.php
session_start();
require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tournament_id = $_POST['tournamentSelect'];
    $name = $_POST['teamName'];
    $logo = $_FILES['teamLogo']['name'];
    $coach_name = $_POST['coachName'];

    // Define target directory and file
    $target_dir = "../uploads/teams/";
    $target_file = $target_dir . basename($logo);

    // Debugging output
    if (!is_dir($target_dir)) {
        echo "Directory does not exist: " . $target_dir;
        exit();
    }

    // Check if directory is writable
    if (!is_writable($target_dir)) {
        echo "Directory is not writable: " . $target_dir;
        exit();
    }

    // Attempt to move the uploaded file
    if (move_uploaded_file($_FILES['teamLogo']['tmp_name'], $target_file)) {
        // Logo upload successful
        $sql = "INSERT INTO teams (tournament_id, name, logo, coach_name) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $tournament_id, $name, $logo, $coach_name);
        $stmt->execute();

        // Increment the registered teams count in the tournaments table
        $update_sql = "UPDATE tournaments SET register = register + 1 WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $tournament_id);
        $update_stmt->execute();
        
        // Redirect back to dashboard
        header("Location: admin_dashboard.php");
        exit();
    } else {
        // Error handling
        echo "Error uploading team logo.";
        echo "Error Code: " . $_FILES['teamLogo']['error'] . "<br>";
        echo "Target File: " . $target_file . "<br>";
        echo "Temporary File: " . $_FILES['teamLogo']['tmp_name'] . "<br>";
        exit();
    }
}
?>
