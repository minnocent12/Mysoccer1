<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../includes/db_connect.php';

// Check if user is logged in
if(!isset($_SESSION['username'])){
    header("Location: ../login.php");
    exit();
}

// Handle deletion
if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])){
    $match_id = intval($_GET['id']);
    $sql = "DELETE FROM matches WHERE id = $match_id";

    if($conn->query($sql) === TRUE){
        header("Location: manage_matches.php");
        exit();
    } else {
        $message = "Error deleting match: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Manage Matches - MySoccer Admin</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/scripts.js"></script>
</head>
<body>
<?php include '../includes/header.php'; ?>

<main>
    <div class="container">
        <section class="manage-matches">
            <h2>Manage Matches</h2>
            <?php if(isset($message)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <a href="add_match.php">Add New Match</a>
            <table border="1" cellpadding="10">
                <tr>
                    <th>ID</th>
                    <th>Tournament</th>
                    <th>Team 1</th>
                    <th>Team 2</th>
                    <th>Match Date</th>
                    <th>Team 1 Score</th>
                    <th>Team 2 Score</th>
                    <th>Actions</th>
                </tr>
                <?php
                $sql = "SELECT matches.*, 
                               tournaments.tournament_name, 
                               t1.team_name AS team1, 
                               t2.team_name AS team2 
                        FROM matches 
                        JOIN tournaments ON matches.tournament_id = tournaments.id 
                        JOIN teams t1 ON matches.team1_id = t1.id 
                        JOIN teams t2 ON matches.team2_id = t2.id 
                        ORDER BY matches.match_date DESC";
                $result = $conn->query($sql);

                if ($result === FALSE) {
                    echo "<tr><td colspan='8'>Error fetching matches: " . $conn->error . "</td></tr>";
                } elseif ($result->num_rows > 0) {
                    while($match = $result->fetch_assoc()){
                        echo "<tr>";
                        echo "<td>" . $match['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($match['tournament_name']) . "</td>";
                        echo "<td><a href='../team.php?id=" . $match['team1_id'] . "'>" . htmlspecialchars($match['team1']) . "</a></td>";
                        echo "<td><a href='../team.php?id=" . $match['team2_id'] . "'>" . htmlspecialchars($match['team2']) . "</a></td>";
                        echo "<td>" . date("F j, Y", strtotime($match['match_date'])) . "</td>";
                        echo "<td>" . ($match['team1_score'] !== NULL ? htmlspecialchars($match['team1_score']) : '-') . "</td>";
                        echo "<td>" . ($match['team2_score'] !== NULL ? htmlspecialchars($match['team2_score']) : '-') . "</td>";
                        echo "<td>
                                <a href='edit_match.php?id=" . $match['id'] . "'>Edit</a> | 
                                <a href='manage_matches.php?action=delete&id=" . $match['id'] . "' onclick=\"return confirmDelete()\">Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No matches found.</td></tr>";
                }
                ?>
            </table>
        </section>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
