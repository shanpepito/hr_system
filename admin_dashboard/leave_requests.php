<?php
session_start();
require_once '../config/config.php';  // Include the database configuration file

// Check if the user is an admin (optional)
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Admin') {
    header("Location: ../login/login.php");
    exit();
}

// Handle the approve/reject functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approveLeave'])) {
        $timeOffID = $_POST['approveLeave'];

        try {
            $stmt = $conn->prepare("UPDATE TimeOff SET ApprovalStatus = 'Approved' WHERE TimeOffID = :timeOffID");
            $stmt->execute([':timeOffID' => $timeOffID]);
            $_SESSION['success_message'] = "Leave request approved successfully!";
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error approving leave request: " . $e->getMessage();
        }
    } elseif (isset($_POST['rejectLeave'])) {
        $timeOffID = $_POST['rejectLeave'];

        try {
            $stmt = $conn->prepare("UPDATE TimeOff SET ApprovalStatus = 'Rejected' WHERE TimeOffID = :timeOffID");
            $stmt->execute([':timeOffID' => $timeOffID]);
            $_SESSION['success_message'] = "Leave request rejected successfully!";
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error rejecting leave request: " . $e->getMessage();
        }
    }
}

// Fetch the leave requests for pending status, join with Employees table to get FirstName and LastName
$leaveRequests = []; // Initialize the variable to avoid undefined warning
try {
    $stmt = $conn->prepare("
        SELECT t.TimeOffID, e.FirstName, e.LastName, t.StartDate, t.EndDate, t.Type, t.ApprovalStatus, t.Description
        FROM TimeOff t
        JOIN Employees e ON t.EmployeeID = e.EmployeeID
        WHERE t.ApprovalStatus = 'Pending'
        ORDER BY t.StartDate DESC
    ");
    $stmt->execute();
    $leaveRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error fetching leave requests: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Add custom styles for the dashboard box */
        .dashboard-box {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            text-align: center;
        }
        .dashboard-box h4 {
            margin-bottom: 10px;
        }
        .dashboard-box .count {
            font-size: 2em;
            font-weight: bold;
            color: #ff5733; /* Color for highlighting the number */
        }
    </style>
</head>
<body>
    <?php include(__DIR__ . '/include/sidebar.php'); ?>  <!-- Sidebar for navigation -->
    <div class="content">
        <?php include(__DIR__ . '/include/header.php'); ?>  <!-- Header -->

        <div class="main-content">
            <h1>Leave Requests</h1>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success_message'] ?></div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error_message'] ?></div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <!-- Table to display pending leave requests -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee Name</th>
                        <th>Leave Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($leaveRequests)): ?>
                        <?php $counter = 1; ?>
                        <?php foreach ($leaveRequests as $request): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td><?= $request['FirstName'] ?> <?= $request['LastName'] ?></td>
                                <td><?= $request['Type'] ?></td>
                                <td><?= $request['StartDate'] ?></td>
                                <td><?= $request['EndDate'] ?></td>
                                <td><?= $request['ApprovalStatus'] ?></td>
                                <td><?= $request['Description'] ?></td>
                                <td>
                                    <!-- Approve/Reject buttons for pending requests -->
                                    <form method="POST" style="display:inline;">
                                        <button type="submit" name="approveLeave" value="<?= $request['TimeOffID'] ?>" class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                    <form method="POST" style="display:inline;">
                                        <button type="submit" name="rejectLeave" value="<?= $request['TimeOffID'] ?>" class="btn btn-danger btn-sm">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No pending leave requests found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
