<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../includes/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM messages WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<p>Message deleted successfully!</p>";
    } else {
        echo "<p>Error deleting message: " . $conn->error . "</p>";
    }
    $stmt->close();
}

// Fetch only user messages (where is_admin_reply is FALSE)
$sql = "SELECT * FROM messages WHERE is_admin_reply = FALSE ORDER BY date_submitted DESC";
$result = $conn->query($sql);
$messages = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Messages - MySoccer</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/admin_styles.css">
    <style>
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
            text-align: center;
        }
        .modal-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .modal-buttons button {
            padding: 8px 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php include '../includes/admin_header.php'; ?>

<main>
    <div class="container">
        <h2>Manage User Messages</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Date Submitted</th>
                <th>Action</th>
            </tr>
            <?php foreach ($messages as $message): ?>
                <tr>
                    <td><?php echo htmlspecialchars($message['name']); ?></td>
                    <td><?php echo htmlspecialchars($message['email']); ?></td>
                    <td><?php echo htmlspecialchars($message['message']); ?></td>
                    <td><?php echo htmlspecialchars($message['date_submitted']); ?></td>
                    <td>
                        <a href="message_reply.php?message_id=<?php echo $message['id']; ?>">Reply</a>
                        <button onclick="confirmDelete(<?php echo $message['id']; ?>)">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</main>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h3>Are you sure you want to delete this message?</h3>
        <form id="deleteForm" method="POST" action="delete_message.php">
            <input type="hidden" name="message_id" id="messageId">
            <div class="modal-buttons">
                <button type="button" onclick="closeModal()">Cancel</button>
                <button type="submit">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
    function confirmDelete(messageId) {
        document.getElementById('messageId').value = messageId;
        document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>
