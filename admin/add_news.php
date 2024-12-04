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

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $title = $conn->real_escape_string($_POST['title']);
    $summary = $conn->real_escape_string($_POST['summary']);
    $content = $conn->real_escape_string($_POST['content']);
    $date_posted = $_POST['date_posted'];
    
    // Handle image upload
    $admin_id = $_SESSION['admin_id'];
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image = basename($_FILES['image']['name']);
        $target_dir = "../uploads/news/"; // Ensure this directory exists and is writable
        $target_file = $target_dir . $image;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $message = "Error uploading image.";
        }
    }

    // Prepare SQL statement to insert news article
    $sql = "INSERT INTO news (title, summary, content, image, date_posted, created_at, admin_id) 
            VALUES ('$title', '$summary', '$content', '$image', '$date_posted', NOW(), '$admin_id')";

    if ($conn->query($sql) === TRUE) {
        header("Location: manage_news.php");
        exit();
    } else {
        $message = "Error adding news: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add News - MySoccer Admin</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/admin_styles.css">
</head>
<body>
<?php include '../includes/admin_header.php'; ?>

<main>
    <div class="container">
        <section class="add-news">
            <h2>Add New News Article</h2>
            <?php if ($message): ?>
                <p class="error-message"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <form method="POST" action="add_news.php" enctype="multipart/form-data">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>

                <label for="summary">Summary:</label>
                <textarea id="summary" name="summary" rows="3" required></textarea>

                <label for="content">Content:</label>
                <textarea id="content" name="content" rows="10" required></textarea>

                <label for="image">Upload Image:</label>
                <input type="file" id="image" name="image" accept="image/*">

                <label for="date_posted">Date Posted:</label>
                <input type="date" id="date_posted" name="date_posted" required>

                <button type="submit">Add News</button>
                <a href="manage_news.php" class="btn cancel-btn">Cancel</a> <!-- Cancel Button -->
            </form>
        </section>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
