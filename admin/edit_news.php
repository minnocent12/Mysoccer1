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
$news_id = intval($_GET['id']);

// Fetch existing news article
$sql = "SELECT * FROM news WHERE id = $news_id";
$result = $conn->query($sql);
$news = $result->fetch_assoc();

if (!$news) {
    die("News article not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $summary = $conn->real_escape_string($_POST['summary']);
    $content = $conn->real_escape_string($_POST['content']);
    $date_posted = $_POST['date_posted'];

    // Handle image upload
    $image = $news['image']; // Keep the existing image by default
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image = basename($_FILES['image']['name']);
        $target_dir = "../uploads/news/"; 
        $target_file = $target_dir . $image;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $message = "Error uploading image.";
        }
    }

    // Prepare SQL statement to update news article
    $sql = "UPDATE news SET title='$title', summary='$summary', content='$content', image='$image', date_posted='$date_posted' WHERE id=$news_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: manage_news.php");
        exit();
    } else {
        $message = "Error updating news: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit News - MySoccer Admin</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/admin_styles.css">
</head>
<body>
<?php include '../includes/admin_header.php'; ?>

<main>
    <div class="container">
        <section class="edit-news">
            <h2>Edit News Article</h2>
            <?php if ($message): ?>
                <p class="error-message"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <form method="POST" action="edit_news.php?id=<?php echo $news_id; ?>" enctype="multipart/form-data">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($news['title']); ?>" required>

                <label for="summary">Summary:</label>
                <textarea id="summary" name="summary" rows="3" required><?php echo htmlspecialchars($news['summary']); ?></textarea>

                <label for="content">Content:</label>
                <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($news['content']); ?></textarea>

                <label for="image">Upload New Image (optional):</label>
                <input type="file" id="image" name="image" accept="image/*">

                <label for="date_posted">Date Posted:</label>
                <input type="date" id="date_posted" name="date_posted" value="<?php echo htmlspecialchars($news['date_posted']); ?>" required>

                <button type="submit">Update News</button>
                <a href="manage_news.php" class="btn cancel-btn">Cancel</a> <!-- Cancel Button -->
            </form>
        </section>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
