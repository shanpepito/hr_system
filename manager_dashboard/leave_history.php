<?php 
session_start();
require_once '../config/config.php';

// Ensure user is logged in and is an Employee
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Manager') {
    header("Location: ../login/login.php");
    exit();
}

// Fetch EmployeeID using EmployeeID (directly from session)
$employeeId = $_SESSION['EmployeeID'];  // Retrieve EmployeeID from session

// Handle leave request submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmed']) && $_POST['confirmed'] == '1') {
    $leaveType = $_POST['leaveType'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $description = $_POST['description'];

    try {
        $stmt = $conn->prepare(" 
        INSERT INTO TimeOff (EmployeeID, StartDate, EndDate, Type, ApprovalStatus, Description) 
        VALUES (:employeeId, :startDate, :endDate, :leaveType, 'Pending', :description) 
        ");
        $stmt->execute([ 
            ':employeeId' => $employeeId, 
            ':startDate' => $startDate, 
            ':endDate' => $endDate, 
            ':leaveType' => $leaveType, 
            ':description' => $description 
        ]);

        $_SESSION['success_message'] = "Leave request submitted successfully!"; 

        // Redirect to refresh the page and show the new data
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error submitting leave request: " . $e->getMessage();
    }
}

// Fetch leave history for the logged-in employee
try {
    $leaveHistoryStmt = $conn->prepare("
    SELECT t.TimeOffID, t.StartDate, t.EndDate, t.Type, t.ApprovalStatus, t.Description, e.FirstName, e.LastName
    FROM TimeOff t
    JOIN Employees e ON t.EmployeeID = e.EmployeeID
    WHERE t.EmployeeID = :employeeId
    ORDER BY t.StartDate DESC
    ");
    $leaveHistoryStmt->execute([':employeeId' => $employeeId]);
    $leaveHistory = $leaveHistoryStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $_SESSION['error_message'] = "Error fetching leave history: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave History</title>
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
        /* Ensure table is scrollable on small screens */
        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <!-- Include Sidebar -->
    <?php include(__DIR__ . '/include/sidebar.php'); ?>

    <div class="content">
        <?php include(__DIR__ . '/include/header.php'); ?>

        <div class="main-content">
            <h1>Leave History</h1>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success_message'] ?></div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error_message'] ?></div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#leaveRequestModal">Request Leave</button>

            <!-- Leave History Table (Mobile responsive) -->
            <div class="table-responsive">
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $counter = 1; 
                        foreach ($leaveHistory as $leave): 
                        ?>
                            <tr>
                                <td><?= $counter++ ?></td> 
                                <td><?= htmlspecialchars($leave['FirstName'] . ' ' . $leave['LastName']) ?></td>
                                <td><?= htmlspecialchars($leave['Type']) ?></td>
                                <td><?= htmlspecialchars($leave['StartDate']) ?></td>
                                <td><?= htmlspecialchars($leave['EndDate']) ?></td>
                                <td><?= htmlspecialchars($leave['ApprovalStatus']) ?></td>
                                <td><?= htmlspecialchars($leave['Description']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Leave Request Modal -->
<div class="modal fade" id="leaveRequestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" id="leaveRequestForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Request Leave</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="leaveType" class="form-label">Leave Type</label>
                        <select id="leaveType" name="leaveType" class="form-control" required>
                            <option value="Sick Leave">Sick Leave</option>
                            <option value="Vacation">Vacation</option>
                            <option value="Maternity Leave">Maternity Leave</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="startDate" class="form-label">Start Date</label>
                        <input type="date" id="startDate" name="startDate" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="endDate" class="form-label">End Date</label>
                        <input type="date" id="endDate" name="endDate" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control" placeholder="Describe your leave reason" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- Hidden input to track confirmation -->
                    <input type="hidden" id="confirmed" name="confirmed" value="0">
                    <button type="button" class="btn btn-primary" onclick="confirmLeaveRequest()">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Confirmation dialog function
    function confirmLeaveRequest() {
        // Show confirmation dialog
        const confirmed = confirm("Are you sure you want to submit your leave request?");
        
        // If confirmed, set the hidden input value to '1' and submit the form
        if (confirmed) {
            document.getElementById("confirmed").value = "1";
            document.getElementById("leaveRequestForm").submit();
        }
    }
</script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
