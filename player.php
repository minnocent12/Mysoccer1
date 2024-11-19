<?php
session_start();
require 'includes/db_connect.php';

if(!isset($_GET['id'])){
    header("Location: tournaments.php");
}

$player_id = intval($_GET['id']);

$sql = "SELECT players.*, teams.team_name FROM players JOIN teams ON players.team_id = teams.id WHERE players.id = $player_id";
$result = $conn->query($sql);
$player = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($player['player_name']); ?> - MySoccer</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<h2><?php echo htmlspecialchars($player['player_name']); ?></h2>
<p>Team: <a href="team.php?id=<?php echo $player['team_id']; ?>"><?php echo htmlspecialchars($player['team_name']); ?></a></p>
<p>Position: <?php echo htmlspecialchars($player['position']); ?></p>
<p>Age: <?php echo htmlspecialchars($player['age']); ?></p>
<p>Nationality: <?php echo htmlspecialchars($player['nationality']); ?></p>

<h3>Past Performances</h3>
<!-- Implement past performances logic -->
<p>Past performances feature coming soon!</p>

<?php include 'includes/footer.php'; ?>
</body>
</html>
