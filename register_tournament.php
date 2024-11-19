<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'includes/db_connect.php';

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tournament_name = $conn->real_escape_string($_POST['tournament_name']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $organizer_name = $conn->real_escape_string($_POST['organizer_name']);

    $sql = "INSERT INTO tournaments (tournament_name, start_date, end_date, organizer_name, is_active) 
            VALUES ('$tournament_name', '$start_date', '$end_date', '$organizer_name', 1)";

    if ($conn->query($sql) === TRUE) {
        header("Location: tournaments.php");
        exit();
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register Tournament - MySoccer</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<main>
    <div class="container">
        <section class="register-tournament">
            <h2>Register New Tournament/League</h2>
            <?php if($message): ?>
                <p style="color: red;"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <form method="POST" action="register_tournament.php">
                <label>Tournament Name:</label>
                <input type="text" name="tournament_name" required><br>
                <label>Start Date:</label>
                <input type="date" name="start_date" required><br>
                <label>End Date:</label>
                <input type="date" name="end_date" required><br>
                <label>Organizer Name:</label>
                <input type="text" name="organizer_name" required><br>
                <button type="submit">Register</button>
            </form>
        </section>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>
