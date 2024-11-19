<?php
// get_match.php
header('Content-Type: application/json');

session_start();
require '../includes/db_connect.php';

if (isset($_GET['match_id'])) {
    $match_id = intval($_GET['match_id']);

    // Update the SQL query to join with the teams table
    $sql = "
        SELECT m.*, t1.name AS team1_name, t2.name AS team2_name 
        FROM matches m 
        JOIN teams t1 ON m.team1_id = t1.id 
        JOIN teams t2 ON m.team2_id = t2.id 
        WHERE m.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $match_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($match = $result->fetch_assoc()) {
        echo json_encode(['status' => 'success', 'match' => $match]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Match not found']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'No match ID provided']);
}

$conn->close();
?>
