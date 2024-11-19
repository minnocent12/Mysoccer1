<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'includes/db_connect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>News - MySoccer</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<main>
    <div class="container">
        <section class="news-list">
            <h2>Latest News</h2>
            <?php
            $sql = "SELECT * FROM news ORDER BY date_posted DESC";
            $result = $conn->query($sql);

            if ($result === FALSE) {
                echo "<p>Error fetching news: " . $conn->error . "</p>";
            } elseif ($result->num_rows > 0) {
                while($news = $result->fetch_assoc()){
                    echo "<article class='news-item'>";
                    echo "<h3><a href='news_detail.php?id=" . $news['id'] . "'>" . htmlspecialchars($news['title']) . "</a></h3>";
                    echo "<p>" . nl2br(htmlspecialchars(substr($news['content'], 0, 200))) . "...</p>";
                    echo "<span class='date'>" . date("F j, Y", strtotime($news['date_posted'])) . "</span>";
                    echo "</article>";
                }
            } else {
                echo "<p>No news available at the moment.</p>";
            }
            ?>
        </section>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>
