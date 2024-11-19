<?php
// get_tournament.php
header('Content-Type: application/json');

session_start();
require '../includes/db_connect.php';

if (isset($_GET['tournament_id'])) {
    $tournament_id = intval($_GET['tournament_id']);

    $sql = "SELECT * FROM tournaments WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tournament_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($tournament = $result->fetch_assoc()) {
        echo json_encode(['status' => 'success', 'tournament' => $tournament]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Tournament not found']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'No tournament ID provided']);
}

$conn->close();
?>
