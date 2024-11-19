<?php
// news_detail.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'includes/db_connect.php';

$news_id = intval($_GET['id']);
$query = "SELECT title, content, image, date_posted FROM news WHERE id = $news_id";
$result = $conn->query($query);
$news = $result->fetch_assoc();

if (!$news) {
    die("News article not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($news['title']); ?> - MySoccer News</title>
    
</head>
<body>
<?php include 'includes/header.php'; ?>

<main>
    <div class="container">
    <div class=" news-detail">
        <h1 class="news-title"><?php echo htmlspecialchars($news['title']); ?></h1>
        <img src="uploads/news/<?php echo htmlspecialchars($news['image']); ?>" alt="News Image" class="full-image">
        <div class="news-content"><?php echo nl2br(htmlspecialchars($news['content'])); ?></div>
        <p class="news-date">Posted on: <?php echo date("F j, Y", strtotime($news['date_posted'])); ?></p>
    </div>

    
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>
