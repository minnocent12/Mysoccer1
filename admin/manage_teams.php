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
    $team_id = intval($_GET['id']);
    $sql = "DELETE FROM teams WHERE id = $team_id";

    if($conn->query($sql) === TRUE){
        header("Location: manage_teams.php");
        exit();
    } else {
        $message = "Error deleting team: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Manage Teams - MySoccer Admin</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/scripts.js"></script>
</head>
<body>
<?php include '../includes/header.php'; ?>

<main>
    <div class="container">
        <section class="manage-teams">
            <h2>Manage Teams</h2>
            <?php if(isset($message)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <a href="add_team.php">Add New Team</a>
            <table border="1" cellpadding="10">
                <tr>
                    <th>ID</th>
                    <th>Tournament</th>
                    <th>Team Name</th>
                    <th>Coach Name</th>
                    <th>Founded Year</th>
                    <th>Home Stadium</th>
                    <th>Actions</th>
                </tr>
                <?php
                $sql = "SELECT teams.*, tournaments.tournament_name FROM teams 
                        JOIN tournaments ON teams.tournament_id = tournaments.id 
                        ORDER BY tournaments.start_date DESC";
                $result = $conn->query($sql);

                if ($result === FALSE) {
                    echo "<tr><td colspan='7'>Error fetching teams: " . $conn->error . "</td></tr>";
                } elseif ($result->num_rows > 0) {
                    while($team = $result->fetch_assoc()){
                        echo "<tr>";
                        echo "<td>" . $team['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($team['tournament_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($team['team_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($team['coach_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($team['founded_year']) . "</td>";
                        echo "<td>" . htmlspecialchars($team['home_stadium']) . "</td>";
                        echo "<td>
                                <a href='edit_team.php?id=" . $team['id'] . "'>Edit</a> | 
                                <a href='manage_teams.php?action=delete&id=" . $team['id'] . "' onclick=\"return confirmDelete()\">Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No teams found.</td></tr>";
                }
                ?>
            </table>
        </section>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
