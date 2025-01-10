<?php 
session_start();
require_once '../config/config.php';

// Ensure user is logged in and is an Admin
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Admin') {
    header("Location: ../login/login.php");
    exit();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Start a transaction
        $conn->beginTransaction();

        if (isset($_POST['addDepartment'])) {
            $deptName = $_POST['departmentName'];
            $stmtDept = $conn->prepare("INSERT INTO Department (Name) VALUES (:deptName)");
            $stmtDept->bindParam(':deptName', $deptName);
            $stmtDept->execute();
        }

        if (isset($_POST['addPosition'])) {
            $departmentID = $_POST['departmentID'];
            $positionTitle = $_POST['positionTitle'];
            $baseSalary = $_POST['baseSalary'];
            $stmtPosition = $conn->prepare("INSERT INTO Position (DepartmentID, Title, BaseSalary) VALUES (:departmentID, :positionTitle, :baseSalary)");
            $stmtPosition->bindParam(':departmentID', $departmentID);
            $stmtPosition->bindParam(':positionTitle', $positionTitle);
            $stmtPosition->bindParam(':baseSalary', $baseSalary);
            $stmtPosition->execute();
        }

        // Commit transaction
        $conn->commit();
        header("Location: department.php");
        exit();
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}

// Fetch departments and positions
$departmentsStmt = $conn->query("SELECT * FROM Department");
$departments = $departmentsStmt->fetchAll(PDO::FETCH_ASSOC);

$positionsStmt = $conn->query("
    SELECT p.*, d.Name AS DepartmentName 
    FROM Position p
    INNER JOIN Department d ON p.DepartmentID = d.DepartmentID
");
$positions = $positionsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments & Positions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
        .btn-new {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<?php include(__DIR__ . '/include/sidebar.php'); ?>

<div class="content">
<?php include(__DIR__ . '/include/header.php'); ?>


        <div class="main-content">
            <h1>Departments & Positions</h1>
 
            <!-- Button to trigger the main "Add New" modal -->
            <button class="btn btn-primary btn-new d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addDataModal">
                <i class="fas fa-plus me-2"></i>
            </button>

            <!-- Main Modal to Choose Action -->
            <div class="modal fade" id="addDataModal" tabindex="-1" aria-labelledby="addDataModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="addDataModalLabel">What would you like to create?</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <div class="d-grid gap-3">
                                <button class="btn btn-outline-primary btn-lg rounded-pill" data-bs-toggle="modal" data-bs-target="#addDepartmentModal" data-bs-dismiss="modal">
                                    <i class="fas fa-building me-2"></i> Create Department
                                </button>
                                <button class="btn btn-outline-secondary btn-lg rounded-pill" data-bs-toggle="modal" data-bs-target="#addPositionModal" data-bs-dismiss="modal">
                                    <i class="fas fa-briefcase me-2"></i> Create Position
                                </button>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal for Adding Department -->
            <div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="addDepartmentModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="addDepartmentModalLabel">Add Department</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="departmentName" class="form-label">Department Name</label>
                                    <input type="text" id="departmentName" name="departmentName" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="addDepartment" class="btn btn-primary">Save</button>
                                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal for Adding Position -->
            <div class="modal fade" id="addPositionModal" tabindex="-1" aria-labelledby="addPositionModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-secondary text-white">
                            <h5 class="modal-title" id="addPositionModalLabel">Add Position</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="departmentID" class="form-label">Select Department</label>
                                    <select id="departmentID" name="departmentID" class="form-control" required>
                                        <?php foreach ($departments as $department): ?>
                                            <option value="<?= $department['DepartmentID'] ?>"><?= $department['Name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="positionTitle" class="form-label">Position Title</label>
                                    <input type="text" id="positionTitle" name="positionTitle" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="baseSalary" class="form-label">Base Salary</label>
                                    <input type="number" id="baseSalary" name="baseSalary" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="addPosition" class="btn btn-secondary">Save</button>
                                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Display Departments -->
            <h2 class="mt-5">Departments</h2>
            <table class="table table-striped" style="width: 80%; margin-left: auto; margin-right: auto;">
                     <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($departments as $department): ?>
                        <tr>
                            <td><?= $department['DepartmentID'] ?></td>
                            <td><?= $department['Name'] ?></td>
                            <td class="d-flex">
                                <a href="edit_department.php?id=<?= $department['DepartmentID'] ?>" class="btn btn-sm btn-warning me-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete_department.php?id=<?= $department['DepartmentID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this employee?')">
                                    <i class="fas fa-trash"></i> 
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Display Positions -->
            <h2 class="mt-5">Positions</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Base Salary</th>
                        <th>Department</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($positions as $position): ?>
                        <tr>
                            <td><?= $position['PositionID'] ?></td>
                            <td><?= $position['Title'] ?></td>
                            <td><?= $position['BaseSalary'] ?></td>
                            <td><?= $position['DepartmentName'] ?></td>
                            <td class="d-flex">
                                <a href="edit_position.php?id=<?= $position['PositionID'] ?>" class="btn btn-sm btn-warning me-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete_position.php?id=<?= $position['PositionID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this employee?')">
                                    <i class="fas fa-trash"></i> 
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>