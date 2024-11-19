<?php
// index.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'includes/db_connect.php';

// Function to get team name by ID
function getTeamName($conn, $team_id) {
    $query = $conn->prepare("SELECT name FROM teams WHERE id = ?");
    $query->bind_param("i", $team_id);
    $query->execute();
    $result = $query->get_result();
    $team = $result->fetch_assoc();
    return $team ? htmlspecialchars($team['name']) : 'Unknown Team';
}

function formatDate($date) {
    return date("F j, Y", strtotime($date));
}

function formatTime($time) {
    return date("h:i A", strtotime($time));
}
?>

<?php include 'includes/header.php'; ?>
<title>MySoccer</title>
<body>
<main>
    <div class="container">
        <section class="welcome">
            <h2>Welcome to MySoccer!</h2>
            <p>Your go-to platform for all things local soccer. Stay updated with the latest news, match schedules, and results of your favorite teams and players.</p>
        </section>

        
        <section class="latest-news">
    <?php
    $result = $conn->query("SELECT * FROM news ORDER BY date_posted DESC");
    $news_count = $result->num_rows;

    if ($news_count > 0) {
        echo "<h2>Latest News</h2>";
        echo "<hr class='section-divider'>"; // Divider line under the Latest News header
        echo "<div class='news-section'>"; // Outer container for all news articles
        $i = 0;

        while ($news = $result->fetch_assoc()) {
            $title = htmlspecialchars($news['title'] ?? '');
            $date = formatDate($news['date_posted'] ?? '');
            $image = $news['image'] ? 'uploads/news/' . $news['image'] : 'path/to/default_image.jpg';
            ?>
            <a href="news_detail.php?id=<?php echo $news['id']; ?>" class="news-article">
                <img src="<?php echo $image; ?>" alt="News Image" class="news-image">
                <div class="news-content">
                    <h3><?php echo $title; ?></h3>
                    <span class="news-date"><?php echo $date; ?></span>
                </div>
            </a>
            <?php
            $i++;
        }
        echo "</div>"; // Close news-section div
    }
    ?>
</section>




<section class="matches-section">
    <?php
    // Fetch upcoming matches
    $upcoming_matches = $conn->query("SELECT * FROM matches WHERE team1_score IS NULL AND team2_score IS NULL ORDER BY match_date ASC");
    $has_upcoming_matches = $upcoming_matches->num_rows > 0;

    // Fetch match results
    $match_results = $conn->query("SELECT * FROM matches WHERE team1_score IS NOT NULL AND team2_score IS NOT NULL ORDER BY match_date DESC");
    $has_match_results = $match_results->num_rows > 0;

    // Determine if only one of the sections has content
    $single_section_class = (!$has_upcoming_matches || !$has_match_results) ? 'single-section' : '';
    ?>

    <?php if ($has_upcoming_matches || $has_match_results): ?>
        <div class="matches-left <?php echo $single_section_class; ?>" <?php if (!$has_upcoming_matches) echo 'style="display:none;"'; ?>>
    <?php if ($has_upcoming_matches): ?>
        <h2>Upcoming Matches</h2>
        <hr class="section-divider">
        <div class="matches-columns">
            <?php
            $i = 0;
            while ($match = $upcoming_matches->fetch_assoc()) {
                if ($i % 2 === 0) echo "<div class='match-row'>";
                $team1 = getTeamName($conn, $match['team1_id']);
                $team2 = getTeamName($conn, $match['team2_id']);
                $match_date = formatDate($match['match_date']);
                $match_time = formatTime($match['match_time']);
            ?>
                <div class="match-details upcoming-match">
                    <div class="match-info">
                        <div class="teams-upcoming">
                            <span class="team-name"><?php echo $team1; ?></span>
                            <span class="vs-text">vs</span>
                            <span class="team-name"><?php echo $team2; ?></span>
                        </div>
                        <p class="match-location">Location: <?php echo htmlspecialchars($match['location'] ?? ''); ?></p>
                        <p class="match-date-time">Date: <?php echo $match_date; ?> | Time: <?php echo $match_time; ?></p>
                    </div>
                </div>
            <?php
                if ($i % 2 === 1) echo "</div>";
                $i++;
            }
            if ($i % 2 !== 0) echo "</div>";
            ?>
        </div>
    <?php endif; ?>
</div>





<div class="matches-right <?php echo $single_section_class; ?>" <?php if (!$has_match_results) echo 'style="display:none;"'; ?>>
    <?php if ($has_match_results): ?>
        <h2>Match Results</h2>
        <hr class="section-divider">
        <div class="matches-columns">
            <?php
            $i = 0;
            while ($result = $match_results->fetch_assoc()) {
                if ($i % 2 === 0) echo "<div class='match-row'>";
                $team1 = getTeamName($conn, $result['team1_id']);
                $team2 = getTeamName($conn, $result['team2_id']);
                $match_date = formatDate($result['match_date']);
                $match_time = formatTime($result['match_time']);
            ?>
                <div class="match-details result-match">
                    <div class="teams-score">
                        <span class="team-name"><?php echo $team1; ?></span>
                        <span class="score-box"><?php echo htmlspecialchars($result['team1_score']); ?></span>
                        <span class="score-divider">-</span>
                        <span class="score-box"><?php echo htmlspecialchars($result['team2_score']); ?></span>
                        <span class="team-name"><?php echo $team2; ?></span>
                    </div>
                    <p class="match-location">Location: <?php echo htmlspecialchars($result['location'] ?? ''); ?></p>
                    <p class="match-date-time">Date: <?php echo $match_date; ?> | Time: <?php echo $match_time; ?></p>
                </div>
            <?php
                if ($i % 2 === 1) echo "</div>";
                $i++;
            }
            if ($i % 2 !== 0) echo "</div>";
            ?>
        </div>
    <?php endif; ?>
</div>




    <?php endif; ?>
</section>



</div>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>