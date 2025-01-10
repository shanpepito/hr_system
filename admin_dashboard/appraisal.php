<?php
session_start();
require_once '../config/config.php';

if (!isset($_SESSION['UserID'])) {
    header("Location: ../login/login.php");
    exit();
}

$userID = $_SESSION['UserID']; 

$query = "SELECT role FROM users WHERE UserID = :userID";
$stmt = $conn->prepare($query);
$stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->errorCode() != '00000') {
    echo "Error executing query: " . implode(", ", $stmt->errorInfo());
}

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit();
}

if ($user['role'] !== 'Admin') {
    echo "Access denied.";
    exit();
}

$query = "SELECT DepartmentID, Name FROM department";
$stmt = $conn->prepare($query);
$stmt->execute();
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

$selectedDepartment = isset($_GET['department']) ? $_GET['department'] : '';

$query = "SELECT appraisal.*, employees.FirstName, employees.LastName, department.Name 
          FROM appraisal
          JOIN employees ON appraisal.EmployeeID = employees.EmployeeID
          JOIN department ON employees.DepartmentID = department.DepartmentID";

if ($selectedDepartment) {
    $query .= " WHERE employees.DepartmentID = :departmentID";
}

$query .= " ORDER BY appraisal.PerformanceRating DESC";

$stmt = $conn->prepare($query);
if ($selectedDepartment) {
    $stmt->bindParam(':departmentID', $selectedDepartment, PDO::PARAM_INT);
}
$stmt->execute();
$appraisals = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = "
    SELECT 
        appraisal.*, 
        CONCAT(employees.FirstName, ' ', employees.LastName) AS EmployeeName,
        department.Name AS DepartmentName
    FROM appraisal
    JOIN employees ON appraisal.EmployeeID = employees.EmployeeID
    JOIN department ON employees.DepartmentID = department.DepartmentID
    ORDER BY appraisal.PerformanceRating DESC
    LIMIT 1
";

$stmt = $conn->prepare($query);
$stmt->execute();
$topPerformer = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Employee Appraisals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            color: #333;
        }

        .container {
            max-width: 900px;
            margin-top: 50px;
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            font-size: 2em;
            font-weight: 600;
            color: #4c4f56;
            margin-bottom: 40px;
            text-align: center;
        }

        .appraisal-section {
            margin-bottom: 30px;
        }

        .appraisal-section h4 {
            font-size: 1.5em;
            color: #495057;
            margin-bottom: 15px;
        }

        .appraisal-section p {
            font-size: 1.1em;
            line-height: 1.6;
        }

        .appraisal-section strong {
            color: #007bff;
        }

        .top-performer-section {
            margin-bottom: 30px;
            background-color: #e8f8e8;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .top-performer-section h4 {
            color: #28a745;
        }

        .filter-section {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
 <!-- Include Sidebar -->
 <?php include(__DIR__ . '/include/sidebar.php'); ?>

<div class="content">
    <!-- Include Header -->
    <?php include(__DIR__ . '/include/header.php'); ?>

    <div class="main-content">

    <div class="filter-section">
        <form action="" method="GET">
            <div class="mb-3">
                <label for="department" class="form-label">Select Department</label>
                <select name="department" id="department" class="form-select" onchange="this.form.submit()">
                    <option value="">All Departments</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?= $department['DepartmentID']; ?>" <?= $selectedDepartment == $department['DepartmentID'] ? 'selected' : ''; ?>>
                            <?= $department['Name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>
    <?php if ($appraisals): ?>
    <div class="appraisal-section">
        <h4>Appraisals</h4>
        <table class="table table-bordered table-striped">
            <thead>
                    <th class="text-center" style="width: 15%;">Employee</th>
                    <th class="text-center">Department</th>
                    <th class="text-center">Performance Rating</th>
                    <th class="text-center">Quality of Work</th>
                    <th class="text-center">Communication Skills</th>
                    <th class="text-center">Teamwork</th>
                    <th class="text-center">Punctuality</th>
                    <th class="text-center">Additional Comments</th>
            </thead>
            <tbody>
                <?php foreach ($appraisals as $appraisal): ?>
                    <tr>
                    <td class="text-center"><?= $appraisal['FirstName'] . ' ' . $appraisal['LastName']; ?></td>
                        <td class="text-center"><?= $appraisal['Name']; ?></td>
                        <td class="text-center"><?= $appraisal['PerformanceRating']; ?></td>
                        <td class="text-center"><?= $appraisal['QualityOfWork']; ?></td>
                        <td class="text-center"><?= $appraisal['CommunicationSkills']; ?></td>
                        <td class="text-center"><?= $appraisal['TeamWork']; ?></td>
                        <td class="text-center"><?= $appraisal['Punctuality']; ?></td>
                        <td class="text-center"><?= nl2br($appraisal['comments']); ?></td>
                    </tr>  
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p class="text-center text-muted">No appraisal data available for the selected department.</p>
<?php endif; ?>

    <div class="top-performer-section">
        <h4>Top Performer</h4>
        <?php if ($topPerformer): ?>
            <p><strong>Employee:</strong> <?= htmlspecialchars($topPerformer['EmployeeName']); ?></p>
            <p><strong>Department:</strong> <?= htmlspecialchars($topPerformer['DepartmentName']); ?></p>
            <p><strong>Performance Rating:</strong> <?= htmlspecialchars($topPerformer['PerformanceRating']); ?></p>
            <p><strong>Quality of Work:</strong> <?= htmlspecialchars($topPerformer['QualityOfWork']); ?></p>
            <p><strong>Communication Skills:</strong> <?= htmlspecialchars($topPerformer['CommunicationSkills']); ?></p>
            <p><strong>Teamwork:</strong> <?= htmlspecialchars($topPerformer['TeamWork']); ?></p>
            <p><strong>Punctuality:</strong> <?= htmlspecialchars($topPerformer['Punctuality']); ?></p>
            <p><strong>Additional Comments:</strong> <?= nl2br(htmlspecialchars($topPerformer['comments'])); ?></p>
        <?php else: ?>
            <p class="text-center text-muted">No appraisal data available.</p>
        <?php endif; ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
