<?php
session_start();
require_once '../config/config.php';

// Ensure user is logged in and is an Admin
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Admin') {
    header("Location: ../login/login.php");
    exit();
}

// Fetch positions for dropdown
$positions = $conn->query("SELECT * FROM Position")->fetchAll(PDO::FETCH_ASSOC);

// Check if application ID is provided for editing
if (isset($_GET['id'])) {
    $recruitmentID = $_GET['id'];

    // Fetch recruitment data
    $stmt = $conn->prepare("SELECT * FROM Recruitment WHERE RecruitmentID = :recruitmentID");
    $stmt->bindParam(':recruitmentID', $recruitmentID);
    $stmt->execute();
    $recruitment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$recruitment) {
        echo "Recruitment not found!";
        exit();
    }
} else {
    header("Location: applications.php");
    exit();
}

// Handle form submission for editing recruitment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editApplications'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $candidateEmail = $_POST['candidateEmail'];
    $candidateNum = $_POST['candidateNum'];
    $resume = $_POST['resume'];
    $applicationDate = $_POST['applicationDate'];
    $hiringStatus = $_POST['hiringStatus'];
    $positionID = $_POST['positionID'];

    try {
        $stmt = $conn->prepare("
            UPDATE Recruitment SET 
                FirstName = :firstName, 
                LastName = :lastName, 
                CandidateEmail = :candidateEmail, 
                CandidateNum = :candidateNum, 
                ApplicationDate = :applicationDate, 
                Resume = :resume, 
                HiringStatus = :hiringStatus, 
                PositionID = :positionID 
            WHERE RecruitmentID = :recruitmentID
        ");
        $stmt->execute([
            ':firstName' => $firstName,
            ':lastName' => $lastName,
            ':candidateEmail' => $candidateEmail,
            ':candidateNum' => $candidateNum,
            ':applicationDate' => $applicationDate,
            ':resume' => $resume,
            ':hiringStatus' => $hiringStatus,
            ':positionID' => $positionID,
            ':recruitmentID' => $recruitmentID,
        ]);

        header("Location: applications.php");
        exit();
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
    <title>Edit Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include(__DIR__ . '/include/sidebar.php'); ?>

<div class="content">
    <?php include(__DIR__ . '/include/header.php'); ?>
    
    <div class="main-content">
        <h1>Edit Applications</h1>
        <form method="POST">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="firstName" class="form-label"> First Name</label>
                            <input type="text" id="firstName" name="firstName" class="form-control" value="<?= $recruitment['FirstName'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" id="lastName" name="lastName" class="form-control" value="<?= $recruitment['LastName'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="candidateEmail" class="form-label">Email</label>
                            <input type="text" id="candidateEmail" name="candidateEmail" class="form-control" value="<?= $recruitment['CandidateEmail'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="candidateNum" class="form-label">Phone</label>
                            <input type="text" id="candidateNum" name="candidateNum" class="form-control" value="<?= $recruitment['CandidateNum'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="applicationDate" class="form-label">Application Date</label>
                            <input type="text" id="applicationDate" name="applicationDate" class="form-control" value="<?= $recruitment['ApplicationDate'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="resume" class="form-label">Resume</label>
                            <input type="resume" id="resume" name="resume" class="form-control" value="<?= $recruitment['Resume'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="positionID" class="form-label">Position</label>
                            <select id="positionID" name="positionID" class="form-control" required>
                                <?php foreach ($positions as $position): ?>
                                    <option value="<?= $position['PositionID'] ?>" <?= ($recruitment['PositionID'] == $position['PositionID']) ? 'selected' : '' ?>><?= $position['Title'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="editApplications" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>