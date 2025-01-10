<?php
session_start();
require_once '../config/config.php';

// Ensure user is logged in and is an Admin
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Admin') {
    header("Location: ../login/login.php");
    exit();
}

// Get the position ID from the URL
if (isset($_GET['id'])) {
    $positionID = $_GET['id'];

    // Fetch the position data
    $stmt = $conn->prepare("SELECT * FROM Position WHERE PositionID = :id");
    $stmt->bindParam(':id', $positionID);
    $stmt->execute();
    $position = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$position) {
        // If the position doesn't exist, redirect to the main page
        header("Location: position.php");
        exit();
    }

    // Fetch all departments for the dropdown list
    $deptStmt = $conn->prepare("SELECT * FROM Department");
    $deptStmt->execute();
    $departments = $deptStmt->fetchAll(PDO::FETCH_ASSOC);

    // Handle the form submission to update the position
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updatePosition'])) {
        $positionTitle = $_POST['positionTitle'];
        $baseSalary = $_POST['baseSalary'];
        $departmentID = $_POST['departmentID'];

        // Update the position details
        $updateStmt = $conn->prepare("UPDATE Position SET Title = :positionTitle, BaseSalary = :baseSalary, DepartmentID = :departmentID WHERE PositionID = :id");
        $updateStmt->bindParam(':positionTitle', $positionTitle);
        $updateStmt->bindParam(':baseSalary', $baseSalary);
        $updateStmt->bindParam(':departmentID', $departmentID);
        $updateStmt->bindParam(':id', $positionID);
        $updateStmt->execute();

        // Redirect to the positions page after update
        header("Location: department.php");
        exit();
    }
} else {
    // If the ID is not provided, redirect to the main page
    header("Location: department.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Position</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Include Sidebar -->
    <?php include(__DIR__ . '/include/sidebar.php'); ?>

    <div class="content">
        <h1>Edit Position</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="positionTitle" class="form-label">Position Title</label>
                <input type="text" id="positionTitle" name="positionTitle" class="form-control" value="<?= htmlspecialchars($position['Title']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="baseSalary" class="form-label">Base Salary</label>
                <input type="number" id="baseSalary" name="baseSalary" class="form-control" value="<?= htmlspecialchars($position['BaseSalary']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="departmentID" class="form-label">Department</label>
                <select id="departmentID" name="departmentID" class="form-control" required>
                    <option value="" disabled>Select a Department</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?= $department['DepartmentID'] ?>" <?= ($position['DepartmentID'] == $department['DepartmentID']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($department['Name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="updatePosition" class="btn btn-primary">Update</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
