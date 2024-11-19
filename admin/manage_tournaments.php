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
    $tournament_id = intval($_GET['id']);
    $sql = "DELETE FROM tournaments WHERE id = $tournament_id";

    if($conn->query($sql) === TRUE){
        header("Location: manage_tournaments.php");
        exit();
    } else {
        $message = "Error deleting tournament: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Manage Tournaments - MySoccer Admin</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/scripts.js"></script>
</head>
<body>
<?php include '../includes/header.php'; ?>

<main>
    <div class="container">
        <section class="manage-tournaments">
            <h2>Manage Tournaments</h2>
            <?php if(isset($message)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <a href="../register_tournament.php">Add New Tournament</a>
            <table border="1" cellpadding="10">
                <tr>
                    <th>ID</th>
                    <th>Tournament Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Organizer</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
                <?php
                $sql = "SELECT * FROM tournaments ORDER BY start_date DESC";
                $result = $conn->query($sql);

                if ($result === FALSE) {
                    echo "<tr><td colspan='7'>Error fetching tournaments: " . $conn->error . "</td></tr>";
                } elseif ($result->num_rows > 0) {
                    while($tournament = $result->fetch_assoc()){
                        echo "<tr>";
                        echo "<td>" . $tournament['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($tournament['tournament_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($tournament['start_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($tournament['end_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($tournament['organizer_name']) . "</td>";
                        echo "<td>" . ($tournament['is_active'] ? 'Yes' : 'No') . "</td>";
                        echo "<td>
                                <a href='edit_tournament.php?id=" . $tournament['id'] . "'>Edit</a> | 
                                <a href='manage_tournaments.php?action=delete&id=" . $tournament['id'] . "' onclick=\"return confirmDelete()\">Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No tournaments found.</td></tr>";
                }
                ?>
            </table>
        </section>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
