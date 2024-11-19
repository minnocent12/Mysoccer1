<?php
// get_tournament_dates.php
session_start();
require '../includes/db_connect.php';

if (isset($_GET['tournament_id'])) {
    $tournament_id = intval($_GET['tournament_id']);

    $sql = "SELECT start_date, end_date FROM tournaments WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tournament_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($tournament = $result->fetch_assoc()) {
        echo json_encode(['start_date' => $tournament['start_date'], 'end_date' => $tournament['end_date']]);
    } else {
        echo json_encode(['error' => 'Tournament not found']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'No tournament ID provided']);
}

$conn->close();
?>
