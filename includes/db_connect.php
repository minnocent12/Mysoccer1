<?php
$servername = "localhost";      
$username = "root"; 
$password = "Milenge.ines2010";
$dbname = "mysoccer";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
