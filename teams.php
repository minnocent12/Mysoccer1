<?php
// teams.php
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
$tournament_sql = "SELECT * FROM tournaments WHERE id = ?";
$stmt = $conn->prepare($tournament_sql);
$stmt->bind_param("i", $tournament_id);
$stmt->execute();
$tournament1 = $stmt->get_result()->fetch_assoc();

if (!$tournament1) {
    die('Tournament not found.');
}

// Fetch teams for the selected tournament directly from the teams table
$teams_sql = "SELECT id, name, logo, coach_name 
              FROM teams 
              WHERE tournament_id = ?";
$stmt_teams = $conn->prepare($teams_sql);
$stmt_teams->bind_param("i", $tournament_id);
$stmt_teams->execute();
$teams_result = $stmt_teams->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css?v=1.0">
    <link rel="stylesheet" href="css/table.css?v=1.0"> <!-- Use same styles for consistency -->
    <title><?php echo htmlspecialchars($tournament1['name']); ?> - Teams</title>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
        <div class="container">
            <section class="welcome">
                <h2><?php echo htmlspecialchars($tournament1['name']); ?> Teams</h2>
            </section>

            <?php if ($teams_result->num_rows > 0): ?>
                <table class="standing-table">
                    <thead>
                        <tr>
                            <th>Team Name</th>
                            <th>Logo</th>
                            <th>Coach Name</th> <!-- Added Coach Name column -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($team = $teams_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($team['name']); ?></td>
                                <td>
                                    <?php if (!empty($team['logo'])): ?>
                                        <img src="uploads/teams/<?php echo htmlspecialchars($team['logo']); ?>" alt="<?php echo htmlspecialchars($team['name']); ?> logo" class="team-logo">
                                    <?php else: ?>
                                        <span>No Logo Available</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($team['coach_name']); ?></td> <!-- Displaying Coach Name -->
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No teams available for this tournament yet.</p>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
