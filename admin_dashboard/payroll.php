<?php
session_start();
require_once '../config/config.php'; // Include your database configuration

// Check if the user is an admin
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Admin') {
    header("Location: ../login/login.php");
    exit(); // Make sure the script stops here if the user is not an admin
}


// Fetch employee attendance, base salary based on position, and salary data
try {
    // Modified query to fetch employee data with base salary based on their position
    $stmt = $conn->prepare("
        SELECT e.EmployeeID, CONCAT(e.FirstName, ' ', e.LastName) AS Name, e.Attendance_Percentage, e.Salary, p.BaseSalary, p.Title, e.PositionID
        FROM Employees e
        LEFT JOIN Position p ON e.PositionID = p.PositionID
    ");
    $stmt->execute();
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if the query returns results
    if (empty($employees)) {
        // If no employees are found, you can handle it here (e.g., show a message or redirect)
        $_SESSION['error_message'] = "No employees found.";
    }

} catch (Exception $e) {
    die("Error fetching employees: " . $e->getMessage());
}


// Define tax deduction rates based on position titles
$taxRates = [
    'Staff' => 5, // 5% deduction for Staff
    'Manager' => 10, // 10% deduction for Manager
    // Add more positions here if needed
];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['employeeSelection']) && $_POST['employeeSelection'] == 'all') {
        // Generate payroll for all employees
        $stmt = $conn->prepare("SELECT * FROM employees");
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($employees as $employee) {
            $employeeID = $employee['EmployeeID'];
            $positionID = $employee['PositionID'];

            // Check if payroll already exists for this employee today
            $stmt = $conn->prepare("SELECT * FROM Payroll WHERE EmployeeID = :EmployeeID AND PayrollDate = :PayrollDate");
            $stmt->execute([
                ':EmployeeID' => $employeeID,
                ':PayrollDate' => date('Y-m-d')
            ]);
            $existingPayroll = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$existingPayroll) { 
                $stmt = $conn->prepare("SELECT BaseSalary, Title FROM position WHERE PositionID = :PositionID");
                $stmt->execute([':PositionID' => $positionID]);
                $position = $stmt->fetch(PDO::FETCH_ASSOC);
                $employeeQuery = $conn->prepare("SELECT Attendance_Percentage FROM Employees WHERE EmployeeID = :EmployeeID");
                $employeeQuery->execute([':EmployeeID' => $employeeID]);
                $employee = $employeeQuery->fetch(PDO::FETCH_ASSOC);
                $attendancePercentage = $employee['Attendance_Percentage'];
                $baseSalary = $position['BaseSalary'] ?? 0;
                $positionTitle = $position['Title'];

                // Deduction based on position
                $deductionPercentage = isset($taxRates[$positionTitle]) ? $taxRates[$positionTitle] : 5;

                $totalEarnings = $baseSalary * ($attendancePercentage / 100);
                $deductionAmount = $totalEarnings * ($deductionPercentage / 100);
                $netEarnings = $totalEarnings - $deductionAmount;

                // Insert into Payroll table
                $stmt = $conn->prepare("INSERT INTO Payroll (EmployeeID, AttendancePercentage, DeductionAmount, NetSalary, PayrollDate, PositionID) VALUES (:EmployeeID, :AttendancePercentage, :DeductionAmount, :NetEarnings, :PayrollDate, :PositionID)");
                $stmt->execute([ 
                    ':EmployeeID' => $employeeID,
                    ':AttendancePercentage' => $attendancePercentage,
                    ':DeductionAmount' => $deductionAmount,
                    ':NetEarnings' => $netEarnings,
                    ':PayrollDate' => date('Y-m-d'),
                    ':PositionID' => $positionID
                ]);
            }
        }
    } elseif (isset($_POST['employeeSelection']) && $_POST['employeeSelection'] == 'selected' && isset($_POST['employeeID'])) {
        // Generate payroll for selected employee
        $employeeID = $_POST['employeeID'];
        $stmt = $conn->prepare("SELECT * FROM employees WHERE EmployeeID = :EmployeeID");
        $stmt->execute([':EmployeeID' => $employeeID]);
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($employee) {
            // Check if payroll already exists for this employee today
            $stmt = $conn->prepare("SELECT * FROM Payroll WHERE EmployeeID = :EmployeeID AND PayrollDate = :PayrollDate");
            $stmt->execute([
                ':EmployeeID' => $employeeID,
                ':PayrollDate' => date('Y-m-d')
            ]);
            $existingPayroll = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$existingPayroll) { 
                $positionID = $employee['PositionID'];

                // Fetch the base salary from the positions table based on the PositionID
                $stmt = $conn->prepare("SELECT BaseSalary, Title FROM position WHERE PositionID = :PositionID");
                $stmt->execute([':PositionID' => $positionID]);
                $position = $stmt->fetch(PDO::FETCH_ASSOC);
                $employeeQuery = $conn->prepare("SELECT Attendance_Percentage FROM Employees WHERE EmployeeID = :EmployeeID");
                $employeeQuery->execute([':EmployeeID' => $employeeID]);
                $employee = $employeeQuery->fetch(PDO::FETCH_ASSOC);
            $attendancePercentage = $employee['Attendance_Percentage'];
                $baseSalary = $position['BaseSalary'] ?? 0;
                $positionTitle = $position['Title'];

                // Deduction based on position
                $deductionPercentage = isset($taxRates[$positionTitle]) ? $taxRates[$positionTitle] : 5;

                $totalEarnings = $baseSalary * ($attendancePercentage / 100);
                $deductionAmount = $totalEarnings * ($deductionPercentage / 100);
                $netEarnings = $totalEarnings - $deductionAmount;

                

                // Insert into Payroll table
                $stmt = $conn->prepare("INSERT INTO Payroll (EmployeeID, AttendancePercentage, DeductionAmount, NetSalary, PayrollDate, PositionID) 
                VALUES (:EmployeeID, :AttendancePercentage, :DeductionAmount, :NetEarnings, :PayrollDate, :PositionID)");
                $stmt->execute([
                    ':EmployeeID' => $employeeID,
                    ':AttendancePercentage' => $attendancePercentage,
                    ':DeductionAmount' => $deductionAmount,
                    ':NetEarnings' => $netEarnings,
                    ':PayrollDate' => date('Y-m-d'),
                    ':PositionID' => $positionID
                ]);
            }
        }
    }

    $_SESSION['success_message'] = "Payroll generated successfully.";
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}

$stmt = $conn->prepare("
    SELECT Payroll.*, employees.FirstName, employees.LastName, position.BaseSalary
    FROM payroll
    JOIN employees ON payroll.EmployeeID = employees.EmployeeID
    JOIN position ON payroll.PositionID = position.PositionID
");
$stmt->execute();
$payrolls = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Payroll</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include(__DIR__ . '/include/sidebar.php'); ?>

<div class="content">
<?php include(__DIR__ . '/include/header.php'); ?>
    <div class="container mt-5">

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success_message']) ?></div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error_message']) ?></div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <h2 class="mt-5">Employee's Payroll</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Base Salary</th>
                    <th>Attendance (%)</th>
                    <th>Deduction</th>
                    <th>Net Earnings</th> 
                    <th>Payroll Date</th> 
                    <th>Action</th> 
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($payrolls)): ?>
                    <?php foreach ($payrolls as $payroll): ?>
                        <tr>
                            <td><?= htmlspecialchars($payroll['EmployeeID']) ?></td>
                            <td><?= htmlspecialchars($payroll['FirstName'] . ' ' . $payroll['LastName']) ?></td>
                            <td><?= htmlspecialchars('₱' . number_format($payroll['BaseSalary'], 2)) ?></td>
                            <td><?= htmlspecialchars(number_format($payroll['AttendancePercentage'], 2)) ?>%</td>
                            <td><?= htmlspecialchars('₱' . number_format($payroll['DeductionAmount'], 2)) ?></td>
                            <td><?= htmlspecialchars('₱' . number_format($payroll['NetSalary'], 2)) ?></td>
                            <td><?= htmlspecialchars($payroll['PayrollDate']) ?></td> 
                            <td>
                                <a href="payslip.php?employeeID=<?= $payroll['EmployeeID'] ?>" class="btn btn-info">View Payslip</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">No payroll data available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>


        <!-- Generate Payroll Button -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#payrollModal">Generate Payroll</button>

     <!-- Modal for selecting employee -->
<div class="modal fade" id="payrollModal" tabindex="-1" aria-labelledby="payrollModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="payrollModalLabel">Select Employee for Payroll</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="employeeSelection" id="selectAllEmployees" value="all" checked>
                        <label class="form-check-label" for="selectAllEmployees">
                            Select All Employees
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="employeeSelection" id="selectEmployee" value="selected">
                        <label class="form-check-label" for="selectEmployee">
                            Select Employee
                        </label>
                    </div>

                    <!-- Employee selection dropdown, initially hidden -->
                    <div id="employeeDropdown" class="mt-3" style="display:none;">
                        <label for="employeeName">Select Employee:</label>
                        <select name="employeeID" id="employeeName" class="form-control">
                            <?php
                            // Fetch employees from database
                            $stmt = $conn->prepare("SELECT EmployeeID, FirstName, LastName FROM employees");
                            $stmt->execute();
                            $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($employees as $employee) {
                                echo "<option value='{$employee['EmployeeID']}'>{$employee['FirstName']} {$employee['LastName']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript to toggle employee dropdown visibility
    document.getElementById('selectEmployee').addEventListener('change', function() {
        document.getElementById('employeeDropdown').style.display = 'block';
    });

    document.getElementById('selectAllEmployees').addEventListener('change', function() {
        document.getElementById('employeeDropdown').style.display = 'none';
    });
</script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>