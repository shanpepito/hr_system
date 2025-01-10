<?php
session_start();
require_once '../config/config.php';

// Ensure user is logged in and is an Admin
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Admin') {
    header("Location: ../login/login.php");
    exit();
}

// Fetch departments for dropdown
$departments = $conn->query("SELECT * FROM Department")->fetchAll(PDO::FETCH_ASSOC);

// Fetch employee details for editing
if (isset($_GET['id'])) {
    $jobID = $_GET['id'];

    // Fetch jobposting  data
    $stmt = $conn->prepare("SELECT * FROM Jobposting WHERE JobID = :jobID");
    $stmt->bindParam(':jobID', $jobID);
    $stmt->execute();
    $jobposting = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$jobposting) {
        echo "Job not found!";
        exit();
    }

    // Handle form submission for editing employee
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editJobPosting'])) {
        $jobTitle = $_POST['jobTitle'];
        $departmentID = $_POST['departmentID'];
        $jobDesc = $_POST['jobDesc'];
        $jobQual = $_POST['jobQual'];
        $jobStatus = $_POST['jobStatus'];

        try {
            $stmt = $conn->prepare("UPDATE Jobposting SET JobTitle = :jobTitle, DepartmentID = :departmentID, JobDesc = :jobDesc, 
                                    JobQual = :jobQual, JobStatus = :jobStatus WHERE JobID = :jobID");
            $stmt->execute([
                ':jobTitle' => $jobTitle,
                ':departmentID' => $departmentID,
                ':jobDesc' => $jobDesc,
                ':jobQual' => $jobQual,
                ':jobStatus' => $jobStatus,
                ':jobID' => $jobID,
            ]);

            header("Location: job_posting.php");
            exit();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
} else {
    // Redirect if no jobposting ID is provided
    header("Location: job_posting.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job Posting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include(__DIR__ . '/include/sidebar.php'); ?>

<div class="content">
    <?php include(__DIR__ . '/include/header.php'); ?>
    
    <div class="main-content">
        <h1>Edit Job Posting</h1>
        <form method="POST">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="jobTitle" class="form-label">Title</label>
                            <input type="text" id="jobTitle" name="jobTitle" class="form-control" value="<?= $jobposting['JobTitle'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="departmentID" class="form-label">Department</label>
                            <select id="departmentID" name="departmentID" class="form-control" required>
                                <?php foreach ($departments as $department): ?>
                                    <option value="<?= $department['DepartmentID'] ?>" <?= ($jobposting['DepartmentID'] == $department['DepartmentID']) ? 'selected' : '' ?>><?= $department['Name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jobDesc" class="form-label">Description</label>
                            <input type="text" id="jobDesc" name="jobDesc" class="form-control" value="<?= $jobposting['JobDesc'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="jobQual" class="form-label">Qualification</label>
                            <input type="text" id="jobQual" name="jobQual" class="form-control" value="<?= $jobposting['JobQual'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="jobStatus" class="form-label">Status</label>
                            <select id="jobStatus" name="jobStatus" class="form-control" required>
                                <option value="Open" <?= ($jobposting['JobStatus'] == 'Open') ? 'selected' : '' ?>>Open</option>
                                <option value="Closed" <?= ($jobposting['JobStatus'] == 'Closed') ? 'selected' : '' ?>>Closed</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="editJobPosting" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
