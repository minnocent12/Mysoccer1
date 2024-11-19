<?php
// Enable error reporting for debugging (remove in production)
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

// Handle deletion
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $news_id = intval($_GET['id']);
    $sql = "DELETE FROM news WHERE id = $news_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: manage_news.php");
        exit();
    } else {
        $message = "Error deleting news: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage News - MySoccer Admin</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/admin_styles.css">
</head>
<body>
<?php include '../includes/admin_header.php'; ?>

<main>
    <div class="container">
        <section class="manage-news">
            <h2>Manage News</h2>
            <?php if (isset($message)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <a href="add_news.php" class="btn">Add New News</a> 
            <table cellpadding="10">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Summary</th>
                        <th>Content</th>
                        <th>Image</th>
                        <th>Date Posted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT * FROM news ORDER BY date_posted DESC";
                $result = $conn->query($sql);

                if ($result === FALSE) {
                    echo "<tr><td colspan='7'>Error fetching news: " . $conn->error . "</td></tr>";
                } elseif ($result->num_rows > 0) {
                    while ($news = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $news['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($news['title'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($news['summary'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($news['content'] ?? '') . "</td>";
                        echo "<td>";
                        if ($news['image']) {
                            echo "<img src='../uploads/news/" . htmlspecialchars($news['image'] ?? '') . "' alt='News Image' style='width: 100px; height: auto;'>";
                        } else {
                            echo "No image";
                        }
                        echo "</td>";
                        echo "<td>" . htmlspecialchars($news['date_posted'] ?? '') . "</td>";
                        echo "<td>
                                <a href='edit_news.php?id=" . $news['id'] . "'>Edit</a> | 
                                <a href='manage_news.php?action=delete&id=" . $news['id'] . "' onclick=\"return confirm('Are you sure you want to delete this news article?')\">Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No news articles found.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </section>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
