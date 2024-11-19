<?php
// results.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'includes/db_connect.php';

if (!isset($_GET['tournament_id'])) {
    die('Invalid tournament selected');
}

$tournament_id = (int)$_GET['tournament_id'];

// Fetch match results with team names and scores, but only where scores are available
$sql = "
    SELECT matches.match_date, matches.team1_score, matches.team2_score, matches.location, 
           t1.name AS team1_name, t1.logo AS team1_logo, 
           t2.name AS team2_name, t2.logo AS team2_logo
    FROM matches 
    JOIN teams AS t1 ON matches.team1_id = t1.id 
    JOIN teams AS t2 ON matches.team2_id = t2.id 
    WHERE matches.tournament_id = ? 
      AND matches.team1_score IS NOT NULL 
      AND matches.team2_score IS NOT NULL
    ORDER BY matches.match_date ASC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tournament_id);
$stmt->execute();
$matches = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Results - <?php echo htmlspecialchars($tournament_id); ?></title>
    <link rel="stylesheet" href="css/styles.css?v=1.0">
    
</head>
<body>
<?php include 'includes/header.php'; ?>

<main>
    <div class="container">
        <h2>Tournament Results</h2>

        <?php if ($matches->num_rows > 0): ?>
            <?php while ($match = $matches->fetch_assoc()): ?>
                <h3><?php echo date("l, j F Y", strtotime($match['match_date'])); ?></h3>
                <table class="match-table">
                    <tr>
                        <td><img src="uploads/teams/<?php echo htmlspecialchars($match['team1_logo']); ?>" alt="<?php echo htmlspecialchars($match['team1_name']); ?> logo"></td>
                        <td class="team-name"><?php echo htmlspecialchars($match['team1_name']); ?></td>
                        <td class="match-score">
                            <?php echo htmlspecialchars($match['team1_score'] . ' - ' . $match['team2_score']); ?>
                        </td>
                        <td class="team-name"><?php echo htmlspecialchars($match['team2_name']); ?></td>
                        <td><img src="uploads/teams/<?php echo htmlspecialchars($match['team2_logo']); ?>" alt="<?php echo htmlspecialchars($match['team2_name']); ?> logo"></td>
                        <td class="match-location"><?php echo htmlspecialchars($match['location']); ?></td>
                    </tr>
                </table>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No results available for this tournament.</p>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>
