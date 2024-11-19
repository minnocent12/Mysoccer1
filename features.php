<?php
// features.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'includes/db_connect.php';

if (!isset($_GET['tournament_id'])) {
    die('Invalid tournament selected');
}

$tournament_id = (int)$_GET['tournament_id'];

// Fetch the tournament details
$sql = "SELECT * FROM tournaments WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tournament_id);
$stmt->execute();
$tournament = $stmt->get_result()->fetch_assoc();

if (!$tournament) {
    die('Tournament not found.');
}

// Fetch matches for the tournament, grouped by date, sorted by match time
$sql_matches = "
    SELECT matches.match_date, matches.match_time, matches.location, 
           t1.name AS team1_name, t1.logo AS team1_logo, 
           t2.name AS team2_name, t2.logo AS team2_logo 
    FROM matches 
    JOIN teams AS t1 ON matches.team1_id = t1.id 
    JOIN teams AS t2 ON matches.team2_id = t2.id 
    WHERE matches.tournament_id = ? 
    ORDER BY matches.match_date ASC, matches.match_time ASC
";
$stmt_matches = $conn->prepare($sql_matches);
$stmt_matches->bind_param("i", $tournament_id);
$stmt_matches->execute();
$matches_result = $stmt_matches->get_result();

// Group matches by date
$matches_by_date = [];
while ($row = $matches_result->fetch_assoc()) {
    $match_date = date("Y-m-d", strtotime($row['match_date']));
    if (!isset($matches_by_date[$match_date])) {
        $matches_by_date[$match_date] = [];
    }
    $matches_by_date[$match_date][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($tournament['name']); ?> - Features</title>
    <link rel="stylesheet" href="css/styles.css?v=1.0">
   
</head>
<body>
<?php include 'includes/header.php'; ?>

<main>
    <div class="container">
        <h2><?php echo htmlspecialchars($tournament['name']); ?> Features</h2>

        <?php if (empty($matches_by_date)): ?>
            <p>No matches scheduled for this tournament yet.</p>
        <?php else: ?>
            <?php foreach ($matches_by_date as $match_date => $matches): ?>
                <h3><?php echo date("l, j F Y", strtotime($match_date)); ?></h3>
                <table class="match-table">
                    <?php foreach ($matches as $match): ?>
                        <tr>
                            <td><img src="uploads/teams/<?php echo htmlspecialchars($match['team1_logo']); ?>" alt="<?php echo htmlspecialchars($match['team1_name']); ?> logo"></td>
                            <td class="team-name"><?php echo htmlspecialchars($match['team1_name']); ?></td>
                            <td class="match-time"><?php echo date("H:i", strtotime($match['match_time'])); ?></td>
                            <td class="team-name"><?php echo htmlspecialchars($match['team2_name']); ?></td>
                            <td><img src="uploads/teams/<?php echo htmlspecialchars($match['team2_logo']); ?>" alt="<?php echo htmlspecialchars($match['team2_name']); ?> logo"></td>
                            <td class="match-location"><?php echo htmlspecialchars($match['location']); ?></td> 
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>
