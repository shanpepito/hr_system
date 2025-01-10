<?php
session_start();
require_once '../config/config.php'; // Database configuration file


// Initialize variables for search filters
$searchEmployeeID = $_GET['EmployeeID'] ?? '';
$searchDate = $_GET['Date'] ?? '';

// Fetch attendance records based on filters
$sql = "SELECT a.AttendanceID, a.EmployeeID, e.FirstName, e.LastName, a.Date, a.ClockIn, a.ClockOut, a.Status 
        FROM attendance a 
        JOIN employees e ON a.EmployeeID = e.employeeId 
        WHERE (:EmployeeID = '' OR a.EmployeeID = :EmployeeID) 
        AND (:Date = '' OR a.Date = :Date)
        ORDER BY a.Date DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([':EmployeeID' => $searchEmployeeID, ':Date' => $searchDate]);
$attendanceRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Attendance Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
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
            padding: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
</style>
<body>
    
 <!-- Include Sidebar -->
 <?php include(__DIR__ . '/include/sidebar.php'); ?>

<div class="content">
    <!-- Include Header -->
    <?php include(__DIR__ . '/include/header.php'); ?>

    <div class="main-content">
    <div class="container mt-1">
        <h2 class="text-center">Attendance Records</h2>
        
        <!-- Search form -->
        <form class="row mb-4" method="GET" action="">
            <div class="col-md-5">
                <input type="text" class="form-control" name="EmployeeID" placeholder="Search by Employee ID" value="<?= htmlspecialchars($searchEmployeeID) ?>">
            </div>
            <div class="col-md-5">
                <input type="date" class="form-control" name="Date" value="<?= htmlspecialchars($searchDate) ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
        </form>

        <!-- Attendance table -->
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Attendance ID</th>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
                    <th>Date</th>
                    <th>Clock In</th>
                    <th>Clock Out</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($attendanceRecords) > 0): ?>
                    <?php foreach ($attendanceRecords as $record): ?>
                        <tr>
                            <td><?= $record['AttendanceID'] ?></td>
                            <td><?= $record['EmployeeID'] ?></td>
                            <td><?= htmlspecialchars($record['FirstName']) . ' ' . htmlspecialchars($record['LastName']) ?></td>
                            <td><?= $record['Date'] ?></td>
                            <td><?= $record['ClockIn'] ?: 'Not clocked in' ?></td>
                            <td><?= $record['ClockOut'] ?: 'Not clocked out' ?></td>
                            <td><?= $record['Status'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No attendance records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    </div>
</body>
</html>
