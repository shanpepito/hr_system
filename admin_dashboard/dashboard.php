<?php
session_start();
require_once '../config/config.php'; // Adjust the path if necessary

// Ensure user is logged in and is an Admin
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Admin') {
    header("Location: ../login/login.php");
    exit();
}
// Fetch counts for Dashboard
try {
    // Query to count the number of departments
    $deptCountStmt = $conn->query("SELECT COUNT(*) FROM Department");
    $departmentCount = $deptCountStmt->fetchColumn();

    // Query to count the number of employees (example)
    $empCountStmt = $conn->query("SELECT COUNT(*) FROM Employees");
    $employeeCount = $empCountStmt->fetchColumn();

    // Fetch the number of pending leave requests for the admin
    try {
        // Count pending leave requests
        $stmt = $conn->prepare("SELECT COUNT(*) AS pendingCount FROM TimeOff WHERE ApprovalStatus = 'Pending'");
        $stmt->execute();
        $pendingRequestCount = $stmt->fetch(PDO::FETCH_ASSOC)['pendingCount'];
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error fetching pending leave requests: " . $e->getMessage();
    }

    // Query for attendance issues (absent count)
    $attendanceCountStmt = $conn->query("SELECT COUNT(*) FROM Attendance WHERE status = 'Absent'");
    $absentCount = $attendanceCountStmt->fetchColumn();

    // Query for payroll entries (corrected table name 'Payroll')
    $payrollCountStmt = $conn->query("SELECT COUNT(*) FROM Payroll");
    $payrollCount = $payrollCountStmt->fetchColumn();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
        }

        .content {
            margin-left: 270px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Responsive layout for smaller screens */
        @media (max-width: 768px) {
            .content {
                margin-left: 0;
            }

            .row > .col-md-4 {
                margin-bottom: 15px;
            }

            .card {
                padding: 15px;
            }
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
            <div class="col-md-4 col-sm-12">
                <div class="card text-center">
                    <h5>Departments</h5>
                    <p><?= $departmentCount ?></p>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="card text-center">
                    <h5>Employees</h5>
                    <p><?= $employeeCount ?></p>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="card text-center">
                    <h5>Pending Leave Requests</h5>
                    <p><?= $pendingRequestCount ?></p>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-4 col-sm-12">
                <div class="card text-center">
                    <h5>Attendance Issues</h5>
                    <p><?= $absentCount ?></p>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="card text-center">
                    <h5>Payroll Entries</h5>
                    <p><?= $payrollCount ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js for the collapse functionality -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
