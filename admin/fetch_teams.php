<?php
// fetch_teams.php
session_start();
require '../includes/db_connect.php';

if (isset($_GET['tournament_id'])) {
    $tournament_id = intval($_GET['tournament_id']);
    
    $sql = "SELECT * FROM teams WHERE tournament_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tournament_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $teams = [];
    while ($row = $result->fetch_assoc()) {
        $teams[] = $row;
    }
    
    echo json_encode($teams);
} else {
    echo json_encode([]);
}
?>
