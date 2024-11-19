<?php
session_start();
require '../includes/db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("No user found for username: " . htmlspecialchars($_SESSION['username']));
}

$profilePicturePath = $user['profile_picture'] && file_exists("../uploads/profile_pics/" . $user['profile_picture']) 
    ? "../uploads/profile_pics/" . $user['profile_picture'] 
    : "../uploads/profile_pics/default_profile.png";

// Handle password change request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);

    if (empty($current_password) || empty($new_password)) {
        $password_error = "All fields are required.";
        $show_password_modal = true; // Keep modal open
    } elseif (!password_verify($current_password, $user['password'])) {
        $password_error = "Current password is incorrect.";
        $show_password_modal = true; // Keep modal open
    } else {
        $hashed_new_password = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE admins SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $hashed_new_password, $_SESSION['username']);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Password updated successfully!";
            header("Location: profile.php");
            exit();
        } else {
            $password_error = "Failed to update password. Please try again.";
            $show_password_modal = true; // Keep modal open
        }
    }
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['change_password'])) {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $profile_picture = $user['profile_picture'];

    if (empty($firstname) || empty($lastname) || empty($email)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        if (!empty($_FILES['profile_picture']['name'])) {
            $uploadDir = "../uploads/profile_pics/";
            $uploadedFile = $uploadDir . basename($_FILES['profile_picture']['name']);
            $fileType = strtolower(pathinfo($uploadedFile, PATHINFO_EXTENSION));

            if (!in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                $error = "Only JPG, JPEG, PNG, and GIF files are allowed.";
            } elseif ($_FILES['profile_picture']['size'] > 5000000) {
                $error = "File size should not exceed 5MB.";
            } else {
                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadedFile)) {
                    $profile_picture = basename($_FILES['profile_picture']['name']);
                } else {
                    $error = "Failed to upload the file.";
                }
            }
        }

        if (!isset($error)) {
            $stmt = $conn->prepare("
                UPDATE admins
                SET firstname = ?, lastname = ?, email = ?, profile_picture = ?
                WHERE username = ?
            ");
            $stmt->bind_param("sssss", $firstname, $lastname, $email, $profile_picture, $_SESSION['username']);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Profile updated successfully!";
                header("Location: profile.php"); 
                exit();
            } else {
                $error = "Failed to update profile. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - MySoccer</title>
    <link rel="stylesheet" href="../css/styles.css?v=1.0">
    <link rel="stylesheet" href="../css/admin_styles.css?v=1.0">
    <style>
        .success-message {
            background-color: #074a92;
            color: white;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
            border-radius: 5px;
        }
        .error-message {
            background-color: #f44336;
            color: white;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
            border-radius: 5px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }
        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
            border-radius: 10px;
            text-align: center;
        }
        .modal button {
            margin: 5px;
        }
    </style>
</head>
<body>
    <?php include '../includes/admin_header.php'; ?>

    <main>
        <div class="container">
            <section class="profile-section">
                <h2>Admin Profile</h2>
                <?php if (isset($error)): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div id="successMessage" class="success-message">
                        <?php echo htmlspecialchars($_SESSION['success']); ?>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <div class="profile-container">
                    <div class="profile-form">
                        <form action="profile.php" method="POST" enctype="multipart/form-data">
                            <label for="username">Username</label>
                            <div class="input-group">
                                <input type="text" id="username" name="username" 
                                    value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                            </div>
                            <label for="firstname">First Name</label>
                            <div class="input-group">
                                <input type="text" id="firstname" name="firstname" 
                                    value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
                            </div>
                            <label for="lastname">Last Name</label>
                            <div class="input-group">
                                <input type="text" id="lastname" name="lastname" 
                                    value="<?php echo htmlspecialchars($user['lastname']); ?>" required>
                            </div>
                            <label for="email">Email</label>
                            <div class="input-group">
                                <input type="email" id="email" name="email" 
                                    value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="profile_picture">Profile Picture</label>
                                <div class="profile-picture-preview">
                                    <img src="<?php echo htmlspecialchars($profilePicturePath); ?>" 
                                         alt="Profile Picture" class="profile-img">
                                </div>
                                <input type="file" id="profile_picture" name="profile_picture">
                            </div>

                            <button type="button" id="changePasswordBtn" class="btn-save">Change Password</button>

                            <div class="form-actions">
                                <button type="submit" class="btn-save">Save Changes</button>
                                <button type="reset" class="btn-reset">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Password Change Modal -->
<div id="passwordModal" class="modal" style="<?php echo isset($show_password_modal) && $show_password_modal ? 'display: block;' : 'display: none;'; ?>">
    <div class="modal-content">
        <?php if (isset($password_error)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($password_error); ?>
            </div>
        <?php endif; ?>
        <form action="profile.php" method="POST">
            <h3>Change Password</h3>
            <label for="current_password">Current Password</label>
            <input type="password" id="current_password" name="current_password" required>
            <br>
            <label for="new_password">New Password</label>
            <input type="password" id="new_password" name="new_password" required>
            <br><br>
            <button type="submit" name="change_password" class="btn-save">Change</button>
            <button type="button" class="btn-reset" id="cancelModal">Cancel</button>
        </form>
    </div>
</div>


    <script>
        // Modal logic
        const modal = document.getElementById('passwordModal');
        const btn = document.getElementById('changePasswordBtn');
        const cancelBtn = document.getElementById('cancelModal');
        const currentPasswordField = document.getElementById('current_password');
        const newPasswordField = document.getElementById('new_password');

        // Open the modal
        btn.onclick = function() {
            modal.style.display = 'block';
        };

        // Close and reset modal
        cancelBtn.onclick = function() {
            modal.style.display = 'none';
            currentPasswordField.value = '';
            newPasswordField.value = '';
            const errorMessages = document.querySelectorAll('.error-message');
            errorMessages.forEach(msg => msg.remove());
        };

        // Close modal if clicking outside the content
        window.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
                currentPasswordField.value = '';
                newPasswordField.value = '';
                const errorMessages = document.querySelectorAll('.error-message');
                errorMessages.forEach(msg => msg.remove());
            }
        };


        // Hide the success message after 5 seconds
        window.onload = function() {
            var successMessage = document.getElementById('successMessage');
            if (successMessage) {
                setTimeout(function() {
                    successMessage.style.display = 'none';
                }, 5000);
            }
        };
    </script>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
