<?php
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
    <title>League Results - MySoccer</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<main class="py-4">
    <div class="container">
        <h2 class="text-center mb-4">League Results</h2>
        <!-- League Results Content Goes Here -->
        <p>Results for the league will be displayed here.</p>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>
