<?php
session_start();
require_once '../config/config.php'; // Include your database configuration

if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Admin') {
    header("Location: ../login/login.php");
    exit();
}

// Fetch employee data based on EmployeeID from query parameter
$employeeID = $_GET['employeeID'] ?? null;
if ($employeeID) {
    try {
        // Fetch employee details and position title
        $stmt = $conn->prepare("
            SELECT e.EmployeeID, CONCAT(e.FirstName, ' ', e.LastName) AS Name, e.attendance_percentage, e.Salary, p.BaseSalary, p.Title 
            FROM employees e
            LEFT JOIN Position p ON e.PositionID = p.PositionID
            WHERE e.EmployeeID = :EmployeeID
        ");
        $stmt->execute([':EmployeeID' => $employeeID]);
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // If no employee found, display error
        if (!$employee) {
            die("Employee not found.");
        }
        
        // Tax deduction rates based on position title
        $taxRates = [
            'Staff' => 5,    // 5% deduction for Staff
            'Manager' => 10, // 10% deduction for Manager
            // Add more positions here if needed
        ];

        // Get tax deduction percentage based on the employee's position title
        $positionTitle = $employee['Title'];
        $deductionPercentage = isset($taxRates[$positionTitle]) ? $taxRates[$positionTitle] : 5; // Default to 5%

        // Calculate total earnings based on attendance percentage
        $totalEarnings = $employee['BaseSalary'] * ($employee['attendance_percentage'] / 100);

        // Calculate deduction
        $deductionAmount = $totalEarnings * ($deductionPercentage / 100);

        // Calculate net earnings (after deduction)
        $netEarnings = $totalEarnings - $deductionAmount;

    } catch (Exception $e) {
        die("Error fetching employee data: " . $e->getMessage());
    }
} else {
    die("Employee ID not provided.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    
    <div class="container mt-5">
        <h1>Payslip for <?= htmlspecialchars($employee['Name']) ?></h1>

        <table class="table table-bordered">
            <tr>
                <th>Employee Name</th>
                <td><?= htmlspecialchars($employee['Name']) ?></td>
            </tr>
            <tr>
                <th>Employee ID</th>
                <td><?= htmlspecialchars($employee['EmployeeID']) ?></td>
            </tr>
            <tr>
                <th>Position</th>
                <td><?= htmlspecialchars($employee['Title']) ?></td>
            </tr>
            <tr>
                <th>Base Salary</th>
                <td><?= htmlspecialchars('₱' . number_format($employee['BaseSalary'], 2)) ?></td>
            </tr>
            <tr>
                <th>Attendance Percentage</th>
                <td><?= htmlspecialchars(number_format($employee['attendance_percentage'], 2)) ?>%</td>
            </tr>
            <tr>
                <th>Total Earnings</th>
                <td><?= htmlspecialchars('₱' . number_format($totalEarnings, 2)) ?></td>
            </tr>
            <tr>
                <th>Deduction</th>
                <td><?= htmlspecialchars('₱' . number_format($deductionAmount, 2)) ?></td>
            </tr>
            <tr>
                <th>Net Earnings</th>
                <td><?= htmlspecialchars('₱' . number_format($netEarnings, 2)) ?></td>
            </tr>
        </table>

        <button onclick="window.print()" class="btn btn-primary">Print Payslip</button>
        <button onclick="window.history.back()" class="btn btn-secondary">Back</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>