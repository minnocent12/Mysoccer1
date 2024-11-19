<?php
// edit_tournament.php
// Enable error reporting (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tournament_id = $_POST['tournament_id'];
    $name = $_POST['tournamentName'];
    $num_teams = $_POST['numberOfTeams'];
    $start_date = $_POST['startDate'];
    $end_date = $_POST['endDate'];
    $organizer = $_POST['organizer'];
    $location = $_POST['location'];

    // Handle logo upload if a new one is provided
    if (isset($_FILES['tournamentLogo']) && $_FILES['tournamentLogo']['error'] === UPLOAD_ERR_OK) {
        $logo = $_FILES['tournamentLogo']['name'];
        $upload_dir = "../uploads/tournaments/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $target_file = $upload_dir . basename($logo);
        move_uploaded_file($_FILES['tournamentLogo']['tmp_name'], $target_file);

        // Update query with logo
        $sql = "UPDATE tournaments SET name = ?, logo = ?, num_teams = ?, start_date = ?, end_date = ?, organizer = ?, location = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssissssi", $name, $logo, $num_teams, $start_date, $end_date, $organizer, $location, $tournament_id);
    } else {
        // Update query without logo
        $sql = "UPDATE tournaments SET name = ?, num_teams = ?, start_date = ?, end_date = ?, organizer = ?, location = ? WHERE id = ?";
        $stmt = $conn->prepare($sql); // Add this line here
        $stmt->bind_param("sissssi", $name, $num_teams, $start_date, $end_date, $organizer, $location, $tournament_id);
    }

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error updating tournament: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
