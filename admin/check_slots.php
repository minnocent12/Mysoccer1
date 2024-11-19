<?php
// check_slots.php
header('Content-Type: application/json');

require '../includes/db_connect.php';

if (isset($_GET['tournament_id'])) {
    $tournament_id = intval($_GET['tournament_id']);

    // Fetch the number of teams already registered and the max number of teams allowed
    $sql = "SELECT num_teams, register FROM tournaments WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tournament_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($tournament = $result->fetch_assoc()) {
        $slots_left = $tournament['num_teams'] - $tournament['register'];
        
        // Return success with the number of slots left
        echo json_encode(['status' => 'success', 'slots_left' => $slots_left]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Tournament not found']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'No tournament ID provided']);
}

$conn->close();
?>
