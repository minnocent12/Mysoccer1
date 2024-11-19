<?php
// get_team.php
header('Content-Type: application/json');

session_start();
require '../includes/db_connect.php';

if (isset($_GET['team_id'])) {
    $team_id = intval($_GET['team_id']);

    $sql = "SELECT * FROM teams WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $team_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($team = $result->fetch_assoc()) {
        echo json_encode(['status' => 'success', 'team' => $team]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Team not found']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'No team ID provided']);
}

$conn->close();
?>
