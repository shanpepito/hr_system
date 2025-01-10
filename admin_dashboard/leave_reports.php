<?php
session_start();
require_once '../config/config.php';  // Include the database configuration file

// Check if the user is an admin (optional)
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Admin') {
    header("Location: ../login/login.php");
    exit();
}

// Fetch leave requests for all users (approved, rejected, and pending)
$leaveReports = []; // Initialize variable to avoid undefined warnings
try {
    $stmt = $conn->prepare("
    SELECT t.TimeOffID, CONCAT(e.FirstName, ' ', e.LastName) AS EmployeeName, t.StartDate, t.EndDate, t.Type, t.ApprovalStatus, t.Description, 
           DATEDIFF(t.EndDate, t.StartDate) + 1 AS TotalDays
    FROM TimeOff t
    JOIN Employees e ON t.EmployeeID = e.EmployeeID
    ORDER BY t.StartDate DESC
");
    $stmt->execute();
    $leaveReports = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error fetching leave reports: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .report-box {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            text-align: center;
        }
        .report-box h4 {
            margin-bottom: 10px;
        }
        .report-box .count {
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
            <h1>Leave Reports</h1>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success_message'] ?></div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error_message'] ?></div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <!-- Table to display all leave requests -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee Name</th>
                        <th>Leave Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Total Days</th>
                        <th>Status</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
    <?php if (!empty($leaveReports)): ?>
        <?php 
        $counter = 1; 
        foreach ($leaveReports as $report): 
        ?>
            <tr>
                <td><?= $counter++ ?></td> 
                <td><?= $report['EmployeeName'] ?></td>
                <td><?= $report['Type'] ?></td>
                <td><?= $report['StartDate'] ?></td>
                <td><?= $report['EndDate'] ?></td>
                <td><?= $report['TotalDays'] ?></td>
                <td><?= $report['ApprovalStatus'] ?></td>
                <td><?= $report['Description'] ?></td>
            </tr>
        <?php endforeach; ?>
            <?php else: ?>
          <tr>
           <td colspan="8" class="text-center">No leave reports available.</td>
        </tr>
        <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
