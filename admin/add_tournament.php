<?php
// add_tournament.php
session_start();
require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['tournamentName'];
    $num_teams = $_POST['numberOfTeams'];
    $start_date = $_POST['startDate'];
    $end_date = $_POST['endDate'];
    $organizer = $_POST['organizer'];
    $location = $_POST['location'];

    // Get the logged-in admin's ID
    $admin_id = $_SESSION['admin_id']; // Ensure admin_id is stored in the session upon login

    // Handle logo upload
    if (isset($_FILES['tournamentLogo']) && $_FILES['tournamentLogo']['error'] === UPLOAD_ERR_OK) {
        $logo = $_FILES['tournamentLogo']['name'];
        $upload_dir = "../uploads/tournaments/";

        // Ensure upload directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Define the target file path
        $target_file = $upload_dir . basename($logo);

        // Move the uploaded file to the desired directory
        if (move_uploaded_file($_FILES['tournamentLogo']['tmp_name'], $target_file)) {
            // File upload successful, now insert into database
            $sql = "INSERT INTO tournaments (name, logo, num_teams, start_date, end_date, organizer, location, admin_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssissssi", $name, $logo, $num_teams, $start_date, $end_date, $organizer, $location, $admin_id);
            $stmt->execute();
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "Error moving the uploaded file.";
        }
    } else {
        echo "Error uploading file: " . $_FILES['tournamentLogo']['error'];
    }
}

?>
