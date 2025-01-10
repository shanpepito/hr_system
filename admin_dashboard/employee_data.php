<?php
// Database connection
$host = 'localhost';
$dbname = 'hr_system';
$user = 'root';
$pass = ''; // Use the actual password or leave it empty if there is none

try {
    // Create a PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Update Employee Salaries from Payroll
    $updateSalaryQuery = "
        UPDATE employees e
        JOIN payroll p ON e.EmployeeID = p.EmployeeID
        SET e.Salary = p.NetSalary
    ";
    $updateSalaryStmt = $conn->prepare($updateSalaryQuery);
    $updateSalaryStmt->execute();

    // Update PerformanceRating in employees table with the latest from appraisal table
    $updateRatingQuery = "
        UPDATE employees e
        JOIN appraisal a ON e.EmployeeID = a.EmployeeID
        SET e.PerformanceRating = a.PerformanceRating
        WHERE a.AppraisalDate = (
            SELECT MAX(AppraisalDate) 
            FROM appraisal 
            WHERE EmployeeID = e.EmployeeID
        )
    ";
    $updateRatingStmt = $conn->prepare($updateRatingQuery);
    $updateRatingStmt->execute();

    // Query to fetch employee data with positions and performance ratings
    $query = "
        SELECT 
            e.EmployeeID,
            CONCAT(e.FirstName, ' ', e.LastName) AS Name,
            d.Name AS Department,
            p.Title AS Position,
            CONCAT('₱', FORMAT(e.Salary, 2)) AS Salary,
            CONCAT(e.Attendance_Percentage, '%') AS Attendance,
            COALESCE(e.PerformanceRating, 'Not Rated') AS PerformanceRating
        FROM 
            employees e
        JOIN 
            department d ON e.DepartmentID = d.DepartmentID
        JOIN 
            position p ON e.PositionID = p.PositionID
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Include Sidebar -->
    <?php include(__DIR__ . '/include/sidebar.php'); ?>

    <div class="content">
        <?php include(__DIR__ . '/include/header.php'); ?>

        <div class="container mt-5">
            <h1 class="mb-4">Employee Data</h1>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Role</th>  
                            <th>Salary</th>
                            <th>Attendance</th>
                            <th>Performance Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($employees)): ?>
                            <?php foreach ($employees as $employee): ?>
                                <tr>
                                    <td><?= 'E' . str_pad(htmlspecialchars($employee['EmployeeID']), 3, '0', STR_PAD_LEFT) ?></td>
                                    <td><?= htmlspecialchars($employee['Name']) ?></td>
                                    <td><?= htmlspecialchars($employee['Department']) ?></td>
                                    <td><?= htmlspecialchars($employee['Position']) ?></td>
                                    <td><?= htmlspecialchars($employee['Salary']) ?></td>
                                    <td><?= htmlspecialchars($employee['Attendance']) ?></td>
                                    <td><?= htmlspecialchars($employee['PerformanceRating']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No employees found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>
</html>
