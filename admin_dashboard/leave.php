<?php
session_start();
require_once '../config/config.php'; // Include the database configuration file

// Check if the user is an admin
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Admin') {
    header("Location: ../login/login.php");
    exit();
}

// Handle the delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteLeave'])) {
    $timeOffId = $_POST['deleteLeave'];
    try {
        $stmt = $conn->prepare("DELETE FROM TimeOff WHERE TimeOffID = :timeOffId");
        $stmt->bindParam(':timeOffId', $timeOffId, PDO::PARAM_INT);
        $stmt->execute();
        $_SESSION['success_message'] = "Leave request deleted successfully.";
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error deleting leave request: " . $e->getMessage();
    }
    header("Location: leave.php");
    exit();
}

// Fetch employees to map EmployeeID to FirstName and LastName
try {
    $employeeStmt = $conn->prepare("SELECT EmployeeID, FirstName, LastName FROM Employees");
    $employeeStmt->execute();
    $employees = $employeeStmt->fetchAll(PDO::FETCH_ASSOC);

    $employeeIdToName = [];
    foreach ($employees as $employee) {
        $employeeIdToName[$employee['EmployeeID']] = $employee['FirstName'] . ' ' . $employee['LastName'];
    }
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error fetching employees: " . $e->getMessage();
}

// Fetch only approved leave requests for admin view
try {
    $leaveStmt = $conn->prepare("
        SELECT t.TimeOffID, t.StartDate, t.EndDate, t.Type, t.ApprovalStatus, t.Description, t.EmployeeID
        FROM TimeOff t
        WHERE t.ApprovalStatus = 'Approved' -- Only fetch approved leave requests
        ORDER BY t.StartDate DESC
    ");
    $leaveStmt->execute();
    $leaveRequests = $leaveStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error fetching leave requests: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include(__DIR__ . '/include/sidebar.php'); ?>

    <div class="content">
        <?php include(__DIR__ . '/include/header.php'); ?>

        <div class="container mt-5">
            <h1>Leave</h1>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success_message'] ?></div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error_message'] ?></div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee Name</th>
                        <th>Leave Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Countdown</th>
                        <th>Status</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $counter = 1; 
                    foreach ($leaveRequests as $request): 
                    ?>
                        <tr>
                            <td><?= $counter++ ?></td>
                            <td><?= isset($employeeIdToName[$request['EmployeeID']]) ? htmlspecialchars($employeeIdToName[$request['EmployeeID']]) : 'Unknown' ?></td>
                            <td><?= htmlspecialchars($request['Type']) ?></td>
                            <td><?= htmlspecialchars($request['StartDate']) ?></td>
                            <td><?= htmlspecialchars($request['EndDate']) ?></td>
                            <td id="countdown-<?= $request['TimeOffID'] ?>"></td>
                            <td><?= htmlspecialchars($request['ApprovalStatus']) ?></td>
                            <td><?= $request['Description'] ? htmlspecialchars($request['Description']) : 'N/A' ?></td>
                        </tr>
                        <script>
                            (function() {
                                var endDate = new Date("<?= $request['EndDate'] ?> 23:59:59").getTime();
                                var countdownElement = document.getElementById("countdown-<?= $request['TimeOffID'] ?>");

                                function updateTimer() {
                                    var now = new Date().getTime();
                                    var remainingTime = endDate - now;

                                    if (remainingTime > 0) {
                                        var days = Math.floor(remainingTime / (1000 * 60 * 60 * 24));
                                        var hours = Math.floor((remainingTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                        var minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
                                        var seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

                                        countdownElement.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s remaining";
                                    } else {
                                        countdownElement.innerHTML = "Leave period has ended";
                                        clearInterval(interval);
                                    }
                                }

                                var interval = setInterval(updateTimer, 1000);
                                updateTimer();
                            })();
                        </script>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
