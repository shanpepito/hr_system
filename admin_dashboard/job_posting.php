<?php
session_start();
require_once '../config/config.php';

// Ensure user is logged in and is an Admin
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Admin') {
    header("Location: ../login/login.php");
    exit();
}

if (isset($_GET['departmentID'])) {
    $departmentID = $_GET['departmentID'];

    try {
        $stmt = $conn->prepare("SELECT PositionID, Title FROM Position WHERE DepartmentID = :departmentID");
        $stmt->execute([':departmentID' => $departmentID]);
        $positions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($positions ?: ['error' => 'No positions found for this department']);
    } catch (Exception $e) {
        error_log("Error fetching positions: " . $e->getMessage());
        echo json_encode(['error' => 'An error occurred while fetching positions.']);
    }
    exit();
}

// Handle form submissions for adding a job posting
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addJobPosting'])) {
    $positionID = $_POST['positionID'];
    $departmentID = $_POST['departmentID'];
    $jobDesc = $_POST['jobDesc'];
    $jobQual = $_POST['jobQual'];
    $jobStatus = $_POST['jobStatus'];

    try {
        $stmt = $conn->prepare("INSERT INTO Jobposting (PositionID, DepartmentID, JobDesc, JobQual, JobStatus) 
                                VALUES (:positionID, :departmentID, :jobDesc, :jobQual, :jobStatus)");
        $stmt->execute([
            ':positionID' => $positionID,
            ':departmentID' => $departmentID,
            ':jobDesc' => $jobDesc,
            ':jobQual' => $jobQual,
            ':jobStatus' => $jobStatus,
        ]);

        header("Location: job_posting.php");
        exit();
    } catch (Exception $e) {
        error_log("Error adding job posting: " . $e->getMessage());
        echo "Error: " . $e->getMessage();
    }
}

// Fetch JobPosting with department names
$jobpostingStmt = $conn->query("
    SELECT j.JobID, p.Title AS PositionTitle, j.JobDesc, j.JobQual, j.JobStatus, d.Name AS DepartmentName
    FROM Jobposting j
    JOIN Position p ON j.PositionID = p.PositionID
    JOIN Department d ON j.DepartmentID = d.DepartmentID
");

// Fetch department options for dropdowns
$departments = $conn->query("SELECT * FROM Department")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Posting</title>
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
            padding: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-new {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <!-- Include Sidebar -->
    <?php include(__DIR__ . '/include/sidebar.php'); ?>

    <div class="content">
        <?php include(__DIR__ . '/include/header.php'); ?>
    
        <div class="main-content">
            <h1>Job Posting</h1>

            <!-- Create Button -->
            <button class="btn btn-primary btn-new" data-bs-toggle="modal" data-bs-target="#addJobPostingModal">
                <i class="fas fa-plus"></i> Add Job Posting
            </button>

            <!-- JobPosting List -->
            <table class="table table-striped mt-4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Description</th>
                        <th>Qualification</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $jobpostingStmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?= $row['JobID'] ?></td>
                            <td><?= $row['PositionTitle'] ?></td>
                            <td><?= $row['DepartmentName'] ?></td>
                            <td><?= $row['JobDesc'] ?></td>
                            <td><?= $row['JobQual'] ?></td>
                            <td><?= $row['JobStatus'] ?></td>
                            <td>
                                <a href="edit_jobposting.php?id=<?= $row['JobID'] ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete_jobposting.php?id=<?= $row['JobID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this Job Posting?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Job Posting Modal -->
    <div class="modal fade" id="addJobPostingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Job</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="departmentID" class="form-label">Department</label>
                            <select id="departmentID" name="departmentID" class="form-control" onchange="loadPositions()" required>
                                <option value="">Select Department</option>
                                <?php foreach ($departments as $department): ?>
                                    <option value="<?= $department['DepartmentID'] ?>"><?= $department['Name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="positionID" class="form-label">Position</label>
                            <select id="positionID" name="positionID" class="form-control" required>
                                <option value="">Select Position</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jobDesc" class="form-label">Description</label>
                            <textarea id="jobDesc" name="jobDesc" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="jobQual" class="form-label">Qualification</label>
                            <textarea id="jobQual" name="jobQual" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="jobStatus" class="form-label">Status</label>
                            <select id="jobStatus" name="jobStatus" class="form-control" required>
                                <option value="open">Open</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="addJobPosting" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        async function loadPositions() {
            const departmentID = document.getElementById('departmentID').value;
            const positionDropdown = document.getElementById('positionID');

            positionDropdown.innerHTML = '<option value="">Select a Position</option>';

            if (!departmentID) return;

            try {
                const response = await fetch(`job_posting.php?departmentID=${departmentID}`);
                const positions = await response.json();

                if (positions.error) {
                    alert(positions.error);
                    return;
                }

                positions.forEach(position => {
                    const option = document.createElement('option');
                    option.value = position.PositionID;
                    option.textContent = position.Title;
                    positionDropdown.appendChild(option);
                });
            } catch (error) {
                console.error('Error fetching positions:', error);
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>