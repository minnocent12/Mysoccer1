<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'includes/db_connect.php';

// Initialize the message variable
$message = '';

$admin_email = "admin@example.com"; // Replace with actual admin email

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $msg = trim($_POST['message']);

    // Basic validation
    if (empty($name) || empty($email) || empty($msg)) {
        $message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } else {
        // Sanitize inputs
        $name = $conn->real_escape_string($name);
        $email = $conn->real_escape_string($email);
        $msg = $conn->real_escape_string($msg);

        // Insert the message into the database
        $sql = "INSERT INTO messages (name, email, message) VALUES ('$name', '$email', '$msg')";

        if ($conn->query($sql) === TRUE) {
            // Send email notification to admin
            $subject = "New Contact Message from $name";
            $body = "You have received a new message from $name ($email):\n\n$msg";
            $headers = "From: $email";

            if (mail($admin_email, $subject, $body, $headers)) {
                $message = "Thank you for contacting us! Your message has been sent.";
                // Redirect to the same page to prevent form resubmission
                header("Location: contact.php?success=1");
                exit;
            } else {
                $message = "Message saved, but failed to send email notification.";
            }
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}

// Check for success message in the query string
if (isset($_GET['success']) && $_GET['success'] == '1') {
    $message = "Thank you for contacting us! Your message has been sent.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Contact Us - MySoccer</title>
    <link rel="stylesheet" href="css/styles.css?v=1.0">
    <style>
        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px; 
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
            text-align: center;
            color: blue;
        }
        .modal.show {
            display: block;
        }
        .modal button {
            background-color: blue; /* Blue button */
            color: white; /* Text color */
            border: none; /* No border */
            padding: 10px 20px; /* Button padding */
            cursor: pointer; /* Pointer cursor */
            margin-top: 10px; /* Space above the button */
        }
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<main>
    <div class="container">
        <section class="account-section">
            <h2>About Us</h2>
            <div class="login-register-container">
                <div class="login-form">
                    <h3>Send us Message</h3>
                   
                    <form id="contactForm" method="POST" action="contact.php">
                        <label for="name">Name:</label>
                        <div class="input-group">
                            <input type="text" name="name" required>
                        </div>

                        <label for="email">Email:</label>
                        <div class="input-group">
                            <input type="email" name="email" required><br>
                        </div>

                        <label for="message">Message:</label><br>
                        <div class="input-group">
                            <textarea name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit">Send</button>
                    </form>
                </div>
                <div class="register-prompt">
                    <h3>Information about MySoccer</h3>
                    <p>MySoccer is dedicated to bringing soccer enthusiasts together through an innovative platform.</p>
                    <p>We provide real-time match updates, player statistics, and tournament information to keep fans informed and engaged.</p>
                    <p>Our mission is to create a community where players, coaches, and fans can connect and share their passion for the sport.</p>
                    <p>Join us in celebrating the beautiful game and enhancing the soccer experience for everyone!</p>
                </div>
            </div>

        </section>
    </div>
</main>

<div id="thankYouModal" class="modal">
    <div class="modal-content">
        <p>Thank you for contacting us! Your message has been sent.</p>
        <button id="okButton">OK</button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('thankYouModal');
        const okButton = document.getElementById('okButton');

        <?php if (isset($message) && strpos($message, 'Thank you') !== false): ?>
            modal.classList.add('show');
            document.getElementById('contactForm').reset(); // Clear the form
        <?php endif; ?>

        // Close the modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.classList.remove('show');
            }
        }

        // Close the modal when the OK button is clicked
        okButton.addEventListener('click', function() {
            modal.classList.remove('show');
        });
    });
</script>

<?php include 'includes/footer.php'; ?>
</body>
</html>
