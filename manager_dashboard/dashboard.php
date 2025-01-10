<?php
session_start();
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Manager') {
    header("Location: login.php"); 
    exit();
}

// Include database connection
require_once '../config/config.php';

// Count the number of timebook (attendance) entries
try {
    $attendanceCountStmt = $conn->query("SELECT COUNT(*) FROM Attendance");
    $attendanceCount = $attendanceCountStmt->fetchColumn();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Count the number of leave history (time off) entries
try {
    $leaveHistoryCountStmt = $conn->query("SELECT COUNT(*) FROM TimeOff");
    $leaveHistoryCount = $leaveHistoryCountStmt->fetchColumn();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Count the number of performance appraisals entries
try {
    $appraisalCountStmt = $conn->query("SELECT COUNT(*) FROM Appraisal");
    $appraisalCount = $appraisalCountStmt->fetchColumn();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Initialize leave request counts
$totalLeaveRequests = 0;
$approvedLeaveRequests = 0;
$rejectedLeaveRequests = 0;

try {
    // Count total leave requests
    $leaveRequestStmt = $conn->query("SELECT COUNT(*) FROM TimeOff");
    $totalLeaveRequests = $leaveRequestStmt->fetchColumn();

    // Count approved leave requests
    $approvedStmt = $conn->query("SELECT COUNT(*) FROM TimeOff WHERE ApprovalStatus = 'Approved'");
    $approved = $approvedStmt->fetchColumn();

    // Count rejected leave requests
    $rejectedStmt = $conn->query("SELECT COUNT(*) FROM TimeOff WHERE ApprovalStatus = 'Rejected'");
    $rejected = $rejectedStmt->fetchColumn();
} catch (Exception $e) {
    echo "Error fetching leave request data: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
        }
        .content {
            margin-left: 270px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }
        .content.shifted {
            margin-left: 0; /* Shift content when sidebar is toggled */
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

        <h1 class="text-center mb-4">Manager Dashboard</h1>

        <!-- Dashboard Boxes -->
        <div class="row">
            <div class="col-md-4">
                <div class="card text-center">
                    <h5>Timebook Entries</h5>
                    <p><?= $attendanceCount ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <h5>Leave History</h5>
                    <p><?= $leaveHistoryCount ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <h5>Performance Appraisals</h5>
                    <p><?= $appraisalCount ?></p>
                </div>
            </div>
        </div>

        <!-- Leave Requests Boxes -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <h5>Total Leave Requests</h5>
                    <p><?= $totalLeaveRequests ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <h5>Approved</h5>
                    <p><?= $approved ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <h5>Rejected</h5>
                    <p><?= $rejected ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js for the collapse functionality -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
