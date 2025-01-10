<?php
session_start();
require_once '../config/config.php';

// Ensure user is logged in and is an Admin
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Admin') {
    header("Location: ../login/login.php");
    exit();
}

// Get the department ID from the URL
if (isset($_GET['id'])) {
    $departmentID = $_GET['id'];

    // Fetch the department data
    $stmt = $conn->prepare("SELECT * FROM Department WHERE DepartmentID = :id");
    $stmt->bindParam(':id', $departmentID);
    $stmt->execute();
    $department = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$department) {
        // If the department doesn't exist, redirect to the main page
        header("Location: department.php");
        exit();
    }

    // Handle the form submission to update the department
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateDepartment'])) {
        $deptName = $_POST['departmentName'];

        // Update the department name
        $updateStmt = $conn->prepare("UPDATE Department SET Name = :deptName WHERE DepartmentID = :id");
        $updateStmt->bindParam(':deptName', $deptName);
        $updateStmt->bindParam(':id', $departmentID);
        $updateStmt->execute();

        // Redirect to the departments page after update
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
    <title>Edit Department</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Include Sidebar -->
    <?php include(__DIR__ . '/include/sidebar.php'); ?>

    <div class="content">
        <h1>Edit Department</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="departmentName" class="form-label">Department Name</label>
                <input type="text" id="departmentName" name="departmentName" class="form-control" value="<?= $department['Name'] ?>" required>
            </div>
            <button type="submit" name="updateDepartment" class="btn btn-primary">Update</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
