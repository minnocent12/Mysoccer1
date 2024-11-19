<?php
// Start the session only if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../includes/db_connect.php'; // Include database connection

// Ensure the logged-in user is authenticated
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch the profile picture if not already set
if (!isset($profilePicture)) {
    $stmt = $conn->prepare("SELECT profile_picture FROM admins WHERE username = ?");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    $headerUser = $result->fetch_assoc();

    // Check if profile picture exists; otherwise, use default
    $profilePicture = ($headerUser && !empty($headerUser['profile_picture']) 
        && file_exists("../uploads/profile_pics/" . $headerUser['profile_picture']))
        ? "../uploads/profile_pics/" . htmlspecialchars($headerUser['profile_picture'])
        : "../uploads/profile_pics/default_profile.png";
}
?>
<header class="admin-header">
    <div class="header-container">
        <div class="logo">
            <img src="../assets/images/Logo1.png" alt="Logo" />
        </div>
        <nav class="menu">
            <ul class="admin-nav-menu">
                <li><a href="admin_dashboard.php">Tournament</a></li>
                <li><a href="manage_news.php">News</a></li>
                <li><a href="manage_messages.php">Messages</a></li>
            </ul>
        </nav>
        <div class="admin-profile-dropdown">
            <span class="profile-icon">
                <!-- Display the profile picture -->
                <img src="<?php echo $profilePicture; ?>" alt="Profile" class="profile-img">
                Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>
            </span>
            <div class="dropdown-content">
                <a href="../admin/profile.php">Profile</a>
                <a href="../logout.php">Logout</a>
            </div>
        </div>
    </div>
</header>
