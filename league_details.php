<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'includes/db_connect.php';

// Get league ID from URL
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $league_id = intval($_GET['id']);
} else {
    // Redirect to league page if ID is invalid
    header("Location: league.php");
    exit();
}

// Fetch league details
$sql = "SELECT * FROM leagues WHERE id = $league_id";
$result = $conn->query($sql);

if($result === FALSE || $result->num_rows == 0){
    echo "League not found.";
    exit();
}

$league = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($league['league_name']); ?> - MySoccer</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<main class="py-4">
    <div class="container">
        <h2 class="text-center mb-4"><?php echo htmlspecialchars($league['league_name']); ?></h2>
        
        <!-- League Information -->
        <p><strong>Founded:</strong> <?php echo htmlspecialchars($league['founded_year']); ?></p>
        <p><strong>Number of Teams:</strong> <?php echo htmlspecialchars($league['number_of_teams']); ?></p>
        <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($league['description'])); ?></p>
        
        <!-- Additional League Details (e.g., Standings, Fixtures) -->
        <!-- Add more sections as needed -->
    </div>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>
