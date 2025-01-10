<?php
session_start();
require_once '../config/config.php';

// Ensure user is logged in and is an Employee
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Manager') {
    header("Location: ../login/login.php");
    exit();
}

$attendanceRecords = []; // Initialize the variable
$error = '';

// Use the logged-in user's UserID from the session
$userID = $_SESSION['UserID'] ?? '';

if (!empty($userID)) {
    try {
        // Fetch EmployeeID based on UserID
        $stmt = $conn->prepare("SELECT EmployeeID FROM users WHERE UserID = :UserID");
        $stmt->execute([':UserID' => $userID]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $employeeID = $user['EmployeeID'];

            // Fetch attendance records for the EmployeeID
            $stmt = $conn->prepare("SELECT * FROM attendance WHERE EmployeeID = :EmployeeID ORDER BY Date DESC");
            $stmt->execute([':EmployeeID' => $employeeID]);
            $attendanceRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $error = "User not found. Please contact the administrator.";
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
} else {
    $error = "Invalid session. Please log in again.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
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
        .main-content {
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

<div class="content">
    <?php include(__DIR__ . '/include/header.php'); ?>

    <div class="main-content">
    <div class="container mt-5">
        <h2 class="text-center">View Attendance</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Clock In</th>
                    <th>Clock Out</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($attendanceRecords)): ?>
                    <?php foreach ($attendanceRecords as $record): ?>
                        <tr>
                            <td><?= htmlspecialchars($record['Date']) ?></td>
                            <td><?= htmlspecialchars($record['ClockIn']) ?></td>
                            <td><?= htmlspecialchars($record['ClockOut'] ?? 'Not yet clocked out') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No attendance records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
