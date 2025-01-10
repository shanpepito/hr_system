<?php
session_start();
require_once '../config/config.php';

// Initialize variables
$successMessage = null;
$applicationDate = null;
$selectedPositionTitle = null;
$selectedPositionID = null;
$selectedDepartmentID = null;
$selectedJobID = isset($_GET['JobID']) ? (int)$_GET['JobID'] : null;

// Fetch position title, position ID, and department ID for the selected JobID
if ($selectedJobID) {
    try {
        $stmt = $conn->prepare("SELECT p.Title AS PositionTitle, p.PositionID, d.DepartmentID
                                FROM Jobposting jp
                                JOIN Position p ON jp.PositionID = p.PositionID
                                JOIN Department d ON p.DepartmentID = d.DepartmentID
                                WHERE jp.JobID = ?");
        $stmt->execute([$selectedJobID]);
        $details = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($details) {
            $selectedPositionTitle = $details['PositionTitle'];
            $selectedPositionID = $details['PositionID'];
            $selectedDepartmentID = $details['DepartmentID'];
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $candidateEmail = $_POST['candidateEmail'];
    $candidateNum = $_POST['candidateNum'];
    $address = $_POST['address'];
    $resume = null;

    // Handle resume upload
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/resumes/';
        $resume = basename($_FILES['resume']['name']);
        $targetPath = $uploadDir . $resume;

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (!move_uploaded_file($_FILES['resume']['tmp_name'], $targetPath)) {
            echo "Error uploading resume.";
            exit();
        }
    }

    try {
        // Insert data into Recruitment table
        $stmt = $conn->prepare("INSERT INTO Recruitment (FirstName, LastName, Gender, DOB, CandidateEmail, CandidateNum, Address, Resume, PositionID, DepartmentID, ApplicationDate, HiringStatus)
                                VALUES (:firstName, :lastName, :gender, :dob, :candidateEmail, :candidateNum, :address, :resume, :positionID, :departmentID, NOW(), 'Applied')");
        $stmt->execute([
            ':firstName' => $firstName,
            ':lastName' => $lastName,
            ':gender' => $gender,
            ':dob' => $dob,
            ':candidateEmail' => $candidateEmail,
            ':candidateNum' => $candidateNum,
            ':address' => $address,
            ':resume' => $resume,
            ':positionID' => $selectedPositionID,
            ':departmentID' => $selectedDepartmentID
        ]);

        $applicationDate = date('Y-m-d H:i:s'); // Capture current date and time
        $successMessage = "You have successfully submitted your application.";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header text-center bg-primary text-white">
            <h3>Job Application Form</h3>
        </div>
        <form method="POST" enctype="multipart/form-data" class="card-body">
            <!-- Success Message -->
            <?php if ($successMessage): ?>
                <div class="alert alert-success text-center">
                    <p><?= htmlspecialchars($successMessage) ?></p>
                    <p><strong>Application Date:</strong> <?= htmlspecialchars($applicationDate) ?></p>
                </div>
            <?php endif; ?>
            <div class="row">
                <h5>Personal Information</h5>
                <div class="mb-3">
                    <label for="firstName" class="form-label">First Name</label>
                    <input type="text" id="firstName" name="firstName" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="lastName" class="form-label">Last Name</label>
                    <input type="text" id="lastName" name="lastName" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="candidateEmail" class="form-label">Email</label>
                    <input type="email" id="candidateEmail" name="candidateEmail" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select id="gender" name="gender" class="form-control" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="date" id="dob" name="dob" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="candidateNum" class="form-label">Phone Number</label>
                    <input type="text" id="candidateNum" name="candidateNum" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea id="address" name="address" class="form-control" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="position" class="form-label">Position Applying For</label>
                    <input type="text" id="position" name="position" class="form-control" 
                           value="<?= htmlspecialchars($selectedPositionTitle ?? 'Position not selected') ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="resume" class="form-label">Resume/CV</label>
                    <input type="file" id="resume" name="resume" class="form-control" required>
                </div>
            </div>
            <div class="text-center">
                <a href="../joblist_web/jobs.php" class="btn btn-secondary">Back</a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>