<?php
// header.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'includes/db_connect.php';

// Fetch all tournaments for dropdowns
$tournament_sql = "SELECT id, name FROM tournaments";
$tournament_result = $conn->query($tournament_sql);
$tournaments = [];
if ($tournament_result->num_rows > 0) {
    while ($row = $tournament_result->fetch_assoc()) {
        $tournaments[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css?v=1.0">
    
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <img src="assets/images/Logo1.png" alt="Logo" />
            </div>
            <div class="menu-toggle">&#9776;</div> <!-- Hamburger icon for mobile -->
            <nav class="menu">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    
                    <li>
                        Features
                        <ul class="dropdown">
                            <?php foreach ($tournaments as $tournament): ?>
                                <li><a href="features.php?tournament_id=<?php echo $tournament['id']; ?>"><?php echo htmlspecialchars($tournament['name']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    
                    <li>
                        Results
                        <ul class="dropdown">
                            <?php foreach ($tournaments as $tournament): ?>
                                <li><a href="results.php?tournament_id=<?php echo $tournament['id']; ?>"><?php echo htmlspecialchars($tournament['name']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    
                    <li>
                        Table
                        <ul class="dropdown">
                            <?php foreach ($tournaments as $tournament): ?>
                                <li><a href="table.php?tournament_id=<?php echo $tournament['id']; ?>"><?php echo htmlspecialchars($tournament['name']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    
                    <li>
                        Teams
                        <ul class="dropdown">
                            <?php foreach ($tournaments as $tournament): ?>
                                <li><a href="teams.php?tournament_id=<?php echo $tournament['id']; ?>"><?php echo htmlspecialchars($tournament['name']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </nav>
            <div class="sign-in">
                <a href="login.php">Sign In</a>
            </div>
        </div>
    </header>

    <script>
        // Toggle mobile menu
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('nav.menu').classList.toggle('active');
        });
    </script>
</body>
</html>
