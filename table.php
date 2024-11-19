<?php
// table.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'includes/db_connect.php';

if (!isset($_GET['tournament_id'])) {
    die('Invalid tournament selected');
}

$tournament_id = (int)$_GET['tournament_id'];

// Fetch tournament details
$sql = "SELECT * FROM tournaments WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tournament_id);
$stmt->execute();
$tournament = $stmt->get_result()->fetch_assoc();

if (!$tournament) {
    die('Tournament not found.');
}

// Fetch standings from standings_history for the tournament
$sql_standings = "
    SELECT team_name, position, played, won, drawn, lost, gf, ga, gd, points, teams.logo AS next_opponent_logo 
    FROM standings_history 
    LEFT JOIN teams ON standings_history.next_opponent = teams.name 
    WHERE standings_history.tournament_id = ?
    ORDER BY position ASC";  // Sort by position
$stmt_standings = $conn->prepare($sql_standings);
$stmt_standings->bind_param("i", $tournament_id);
$stmt_standings->execute();
$standings_result = $stmt_standings->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($tournament['name']); ?> - Table</title>
    <link rel="stylesheet" href="css/styles.css?v=1.0">
</head>
<body>
<?php include 'includes/header.php'; ?>

<main>
    <div class="container">
        <h2><?php echo htmlspecialchars($tournament['name']); ?> Standings</h2>

        <?php if ($standings_result->num_rows > 0): ?>
            <table class="standing-table">
                <thead>
                    <tr>
                        <th>Position</th>
                        <th>Team</th>
                        <th>Played</th>
                        <th>Won</th>
                        <th>Drawn</th>
                        <th>Lost</th>
                        <th>GF</th>
                        <th>GA</th>
                        <th>GD</th>
                        <th>Points</th>
                        <th>Next Opponent</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($standing = $standings_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($standing['position']); ?></td>
                            <td><?php echo htmlspecialchars($standing['team_name']); ?></td>
                            <td><?php echo htmlspecialchars($standing['played']); ?></td>
                            <td><?php echo htmlspecialchars($standing['won']); ?></td>
                            <td><?php echo htmlspecialchars($standing['drawn']); ?></td>
                            <td><?php echo htmlspecialchars($standing['lost']); ?></td>
                            <td><?php echo htmlspecialchars($standing['gf']); ?></td>
                            <td><?php echo htmlspecialchars($standing['ga']); ?></td>
                            <td><?php echo htmlspecialchars($standing['gd']); ?></td>
                            <td><?php echo htmlspecialchars($standing['points']); ?></td>
                            <td>
                                <?php if (!empty($standing['next_opponent_logo'])): ?>
                                    <img src="uploads/teams/<?php echo htmlspecialchars($standing['next_opponent_logo']); ?>" alt="Next opponent logo" class="team-logo">
                                <?php else: ?>
                                    <span>Match not updated</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No standings available for this tournament yet.</p>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>
