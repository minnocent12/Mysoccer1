<?php
// edit_tournament.php
// Enable error reporting (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../includes/db_connect.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo "You must be logged in to edit tournaments.";
    exit();
}

$admin_id = $_SESSION['admin_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tournament_id = $_POST['tournament_id'];
    $name = $_POST['tournamentName'];
    $num_teams = $_POST['numberOfTeams'];
    $start_date = $_POST['startDate'];
    $end_date = $_POST['endDate'];
    $organizer = $_POST['organizer'];
    $location = $_POST['location'];

    // Fetch the tournament from the database to verify that the admin is allowed to edit it
    $sql = "SELECT admin_id FROM tournaments WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tournament_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $tournament = $result->fetch_assoc();

    if ($tournament && $tournament['admin_id'] == $admin_id) {
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
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sissssi", $name, $num_teams, $start_date, $end_date, $organizer, $location, $tournament_id);
        }

        if ($stmt->execute()) {
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "Error updating tournament: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "You are not authorized to edit this tournament.";
    }
}

$conn->close();

?>
