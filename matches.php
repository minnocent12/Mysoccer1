<?php
session_start();
require 'includes/db_connect.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Matches - MySoccer</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/scripts.js"></script>
</head>
<body>
<?php include 'includes/header.php'; ?>

<h2>All Matches</h2>

<form method="GET" action="matches.php">
    <label for="team">Filter by Team:</label>
    <select name="team" id="team">
        <option value="">All Teams</option>
        <?php
        $sql = "SELECT * FROM teams";
        $result = $conn->query($sql);
        while($team = $result->fetch_assoc()){
            echo "<option value='" . $team['id'] . "'>" . htmlspecialchars($team['team_name']) . "</option>";
        }
        ?>
    </select>
    
    <label for="date">Filter by Date:</label>
    <input type="date" name="date" id="date">
    
    <button type="submit">Filter</button>
</form>

<ul>
    <?php
    $where = "";
    if(isset($_GET['team']) && !empty($_GET['team'])){
        $team_id = intval($_GET['team']);
        $where .= " AND (matches.team1_id = $team_id OR matches.team2_id = $team_id)";
    }
    if(isset($_GET['date']) && !empty($_GET['date'])){
        $date = $conn->real_escape_string($_GET['date']);
        $where .= " AND match_date = '$date'";
    }
    
    $sql = "SELECT matches.*, t1.team_name as team1, t2.team_name as team2 FROM matches 
            JOIN teams t1 ON matches.team1_id = t1.id 
            JOIN teams t2 ON matches.team2_id = t2.id 
            WHERE 1=1 $where ORDER BY match_date ASC";
    $result = $conn->query($sql);
    while($match = $result->fetch_assoc()){
        echo "<li>" . htmlspecialchars($match['match_date']) . ": <a href='team.php?id=" . $match['team1_id'] . "'>" . htmlspecialchars($match['team1']) . "</a> vs <a href='team.php?id=" . $match['team2_id'] . "'>" . htmlspecialchars($match['team2']) . "</a>";
        if($match['team1_score'] !== null && $match['team2_score'] !== null){
            echo " - Score: " . htmlspecialchars($match['team1_score']) . " - " . htmlspecialchars($match['team2_score']);
        }
        echo "</li>";
    }
    ?>
</ul>

<?php include 'includes/footer.php'; ?>
</body>
</html>
