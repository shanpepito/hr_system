<?php
session_start();
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Employee') {
    header("Location: login.php"); 
    exit();
}

// Include database connection
require_once '../config/config.php';

// Initialize variables
$leaveRequestCount = 0;    // To count total leave requests
$approvedCount = 0;        // To count approved leave requests
$rejectedCount = 0;        // To count rejected leave requests

// Get userID from session
$userID = $_SESSION['UserID'];

try {
    // Query to count total leave requests
    $stmt = $conn->prepare("SELECT COUNT(*) AS leaveRequestCount FROM timeoff WHERE UserID = :userID");
    $stmt->execute([':userID' => $userID]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $leaveRequestCount = $result['leaveRequestCount'];

    // Query to count approved leave requests
    $stmt = $conn->prepare("SELECT COUNT(*) AS approvedCount FROM timeoff WHERE UserID = :userID AND ApprovalStatus = 'Approved'");
    $stmt->execute([':userID' => $userID]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $approvedCount = $result['approvedCount'];

    // Query to count rejected leave requests
    $stmt = $conn->prepare("SELECT COUNT(*) AS rejectedCount FROM timeoff WHERE UserID = :userID AND ApprovalStatus = 'Rejected'");
    $stmt->execute([':userID' => $userID]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $rejectedCount = $result['rejectedCount'];

} catch (Exception $e) {
    $_SESSION['error_message'] = "Error fetching leave data: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
        }
        .content {
            margin-left: 270px;
            padding: 20px;
        }
        .card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <!-- Include Sidebar -->
    <?php include(__DIR__ . '/include/sidebar.php'); ?>

    <!-- Main Content -->
    <div class="content">
    <?php include(__DIR__ . '/include/header.php'); ?>

        <!-- Dashboard Boxes -->
        <div class="row">
            <div class="col-md-4">
                <div class="card text-center">
                    <h5>Leave Requests</h5>
                    <p><?= $leaveRequestCount ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <h5>Approved</h5>
                    <p><?= $approvedCount ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <h5>Rejected</h5>
                    <p><?= $rejectedCount ?></p>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS and Popper.js for the collapse functionality -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
