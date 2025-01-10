<?php
session_start();
require_once '../config/config.php';

// Ensure the user is logged in
if (!isset($_SESSION['UserID'])) {
    header("Location: ../login/login.php");
    exit();
}

// Fetch current user details from the database
$userID = $_SESSION['UserID'];
try {
    $stmt = $conn->prepare("SELECT Username, Password FROM users WHERE UserID = :userID");
    $stmt->execute([':userID' => $userID]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error fetching user details: " . $e->getMessage();
}

// Process the form if it was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updatePassword'])) {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if the current password matches
    if (!password_verify($currentPassword, $user['Password'])) {
        $_SESSION['error_message'] = "Current password is incorrect.";
    } elseif ($newPassword !== $confirmPassword) {
        $_SESSION['error_message'] = "New passwords do not match.";
    } else {
        // Update password
        try {
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateStmt = $conn->prepare("UPDATE users SET Password = :password WHERE UserID = :userID");
            $updateStmt->execute([
                ':password' => $newPasswordHash,
                ':userID' => $userID
            ]);
            $_SESSION['success_message'] = "Password updated successfully!";
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error updating password: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .main-content {
            margin: 2rem auto;
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .alert {
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <!-- Include Sidebar -->
    <?php include(__DIR__ . '/include/sidebar.php'); ?>

    <div class="content">
        <?php include(__DIR__ . '/include/header.php'); ?>

        <div class="main-content">
            <h1 class="text-center mb-4">Update Password</h1>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success text-center"><?= $_SESSION['success_message'] ?></div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger text-center"><?= $_SESSION['error_message'] ?></div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="currentPassword" class="form-label">Current Password</label>
                    <input type="password" id="currentPassword" name="currentPassword" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="newPassword" class="form-label">New Password</label>
                    <input type="password" id="newPassword" name="newPassword" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Confirm New Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" required>
                </div>

                <button type="submit" name="updatePassword" class="btn btn-primary w-100">Update Password</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
