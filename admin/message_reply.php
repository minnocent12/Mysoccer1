<?php
// Required PHP configuration for error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../includes/db_connect.php';

// Include PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php'; // Adjust path as needed

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch the original message from the database
$message_id = $_GET['message_id'] ?? null;
$message = null;

if ($message_id) {
    $sql = "SELECT * FROM messages WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = $result->fetch_assoc();
    } else {
        // Log or handle the error as needed
        exit();
    }
}

// Handle reply submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reply = trim($_POST['reply']);

    if ($message && $reply) {
        // Initialize PHPMailer
        $mail = new PHPMailer(true);

        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP host
            $mail->SMTPAuth   = true;
            $mail->Username   = 'mirenge.innocent@gmail.com'; // Replace with your SMTP email
            $mail->Password   = 'dsng tooj bszc niuj'; // Replace with your SMTP email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Set sender and recipient
            $mail->setFrom('mirenge.innocent@gmail.com', 'MySoccer Support');
            $mail->addAddress($message['email']); // User email from the original message

            // Email content
            $mail->isHTML(true);
            $mail->Subject = "Reply to your message";
            $mail->Body    = nl2br(htmlspecialchars($reply)); // Convert reply to HTML

            $footer = '
            <br><br>
            <div style="border-top: 1px solid #ccc; padding-top: 10px; font-size: 12px; color: #666;">
                <p>Thank you for reaching out to us!</p>
                <p>MySoccer Support Team</p>
                <p>Email: support@mysoccer.com</p>
                <p>Phone: +123 456 7890</p>
                <p>Website: <a href="https://www.mysoccer.com" style="color: #0066cc;">www.mysoccer.com</a></p>
                <p>This message was sent to you because you have contacted us. If you did not expect this message, please ignore it.</p>
            </div>
            ';

            // Combine the reply with the footer
            $mail->Body    = nl2br(htmlspecialchars($reply)) . $footer; // Add the footer after the reply
            // Send email
            $mail->send();

            // Save reply in the database
            $sql = "INSERT INTO messages (name, email, message, date_submitted, replied_to, is_admin_reply) 
                    VALUES ('Admin', 'your-email@gmail.com', ?, NOW(), ?, TRUE)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $reply, $message_id);
            $stmt->execute();
        } catch (Exception $e) {
            // Log or handle the error as needed
        }
    } else {
        // Log or handle the case where the reply is empty
    }
}

// Fetch conversation messages
$sql = "SELECT * FROM messages WHERE id = ? OR replied_to = ? ORDER BY date_submitted ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $message_id, $message_id);
$stmt->execute();
$result = $stmt->get_result();
$chatMessages = [];
while ($row = $result->fetch_assoc()) {
    $chatMessages[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply to Message - MySoccer</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/admin_styles.css">
    <style>
        .chat-body {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
            max-height: 400px;
            overflow-y: auto;
            background-color: #f9f9f9;
        }
        .message {
            margin: 10px 0;
        }
        .message.admin {
            text-align: right;
            color: blue;
        }
        .message.user {
            text-align: left;
            color: green;
        }
        .footer {
            margin-top: 20px;
            display: flex;
            align-items: center;
        }
        .footer textarea {
            flex: 1;
            margin-right: 10px;
        }
    </style>
</head>
<body>
<?php include '../includes/admin_header.php'; ?>

<main>
    <div class="container">
        <h2>Conversation with <?php echo htmlspecialchars($message['name']); ?></h2>
        <div class="chat-body">
            <?php foreach ($chatMessages as $chatMessage): ?>
                <div class="message <?php echo ($chatMessage['is_admin_reply']) ? 'admin' : 'user'; ?>">
                    <strong><?php echo htmlspecialchars($chatMessage['name']); ?></strong>: <br>
                    <p><?php echo htmlspecialchars($chatMessage['message']); ?></p>
                    <small><?php echo htmlspecialchars($chatMessage['date_submitted']); ?></small>
                </div>
            <?php endforeach; ?>
        </div>
        
        <form method="POST" action="message_reply.php?message_id=<?php echo $message_id; ?>" class="footer">
            <textarea name="reply" rows="3" required placeholder="Type your reply here..."></textarea>
            <button type="submit">Send Reply</button>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
