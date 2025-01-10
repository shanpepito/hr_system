<?php
session_start();
require_once '../config/config.php';

// Ensure user is logged in and is an Admin
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Admin') {
    header("Location: ../login/login.php");
    exit();
}

// Handle form submissions for adding an application
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addApplication'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $candidateEmail = $_POST['candidateEmail'];
    $candidateNum = $_POST['candidateNum'];
    $resume = $_POST['resume'];
    $applicationDate = $_POST['applicationDate'];
    $positionID = $_POST['positionID'];

    try {
        $stmt = $conn->prepare("INSERT INTO Recruitment (FirstName, LastName, Gender, DOB, CandidateEmail, CandidateNum, Resume, 
                                ApplicationDate, HiringStatus, PositionID) 
                                VALUES (:firstName, :lastName, :gender, :dob, :candidateEmail, :candidateNum, :resume, :applicationDate, :hiringStatus, :positionID)");
        $stmt->execute([
            ':firstName' => $firstName,
            ':lastName' => $lastName,
            ':gender' => $gender,
            ':dob' => $dob,
            ':candidateEmail' => $candidateEmail,
            ':candidateNum' => $candidateNum,
            ':resume' => $resume,
            ':applicationDate' => $applicationDate,
            ':hiringStatus' => 'Applied', // Ensure HiringStatus is explicitly set
            ':positionID' => $positionID,
        ]);

        header("Location: applications.php");
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Handle updating hiring status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['screenResume'])) {
    $recruitmentID = $_POST['recruitmentID'];
    $screeningStatus = $_POST['screenResume'];
    $interviewDate = "2025-01-13"; // Static for now; can be dynamic
    $interviewLocation = "Interview Room 1"; // Static for now; can be dynamic

    try {
        // Fetch the current hiring status
        $stmt = $conn->prepare("SELECT HiringStatus FROM Recruitment WHERE RecruitmentID = :recruitmentID");
        $stmt->execute([':recruitmentID' => $recruitmentID]);
        $currentStatus = $stmt->fetchColumn();

        if ($screeningStatus === 'Confirm') {
            if ($currentStatus === 'Passed') {
                $_SESSION['message'] = "This candidate has already been confirmed as 'Passed'.";
            } else {
                // Final confirmation for passing
                $stmt = $conn->prepare("UPDATE Recruitment SET HiringStatus = 'Passed' WHERE RecruitmentID = :recruitmentID");
                $stmt->execute([':recruitmentID' => $recruitmentID]);

                // Save interview details
                $stmt = $conn->prepare("INSERT INTO InterviewSchedule (RecruitmentID, InterviewDate, Location)
                                        VALUES (:recruitmentID, :interviewDate, :location)
                                        ON DUPLICATE KEY UPDATE InterviewDate = VALUES(InterviewDate), Location = VALUES(Location)");
                $stmt->execute([
                    ':recruitmentID' => $recruitmentID,
                    ':interviewDate' => $interviewDate,
                    ':location' => $interviewLocation,
                ]);

                $_SESSION['message'] = "Candidate has been confirmed as 'Passed' with interview details saved.";
            }
        } elseif ($screeningStatus === 'Back') {
            $_SESSION['message'] = "You returned to the initial screening step.";
        } elseif ($screeningStatus === 'Failed') {
            $stmt = $conn->prepare("UPDATE Recruitment SET HiringStatus = 'Rejected' WHERE RecruitmentID = :recruitmentID");
            $stmt->execute([':recruitmentID' => $recruitmentID]);
            $_SESSION['message'] = "Candidate has been marked as 'Rejected'.";
        }

        header("Location: applications.php");
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Handle resume screening
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['screenResume'])) {
    $recruitmentID = $_POST['recruitmentID'];
    $screeningStatus = $_POST['screeningStatus'];

    try {
        $stmt = $conn->prepare("UPDATE Recruitment SET HiringStatus = :screeningStatus WHERE RecruitmentID = :recruitmentID");
        $stmt->execute([
            ':screeningStatus' => $screeningStatus,
            ':recruitmentID' => $recruitmentID
        ]);
        header("Location: applications.php");
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Function to fetch recruitment by department
function getRecruitmentByDepartment($conn, $departmentID) {
    $stmt = $conn->prepare("
        SELECT r.RecruitmentID, r.FirstName, r.LastName, r.Gender, r.DOB, r.CandidateEmail, r.CandidateNum, 
               r.ApplicationDate, r.Resume, r.HiringStatus, p.Title AS PositionTitle
        FROM Recruitment r
        JOIN Position p ON r.PositionID = p.PositionID
        WHERE p.DepartmentID = :departmentID
    ");
    $stmt->execute([':departmentID' => $departmentID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch recruitment data based on the department filter
$departmentID = isset($_GET['departmentID']) ? $_GET['departmentID'] : 'all';

if ($departmentID === 'all') {
    // Fetch all data
    $recruitmentQuery = "
        SELECT r.RecruitmentID, r.FirstName, r.LastName, r.Gender, r.DOB, r.CandidateEmail, r.CandidateNum, 
               r.ApplicationDate, r.Resume, r.HiringStatus, p.Title AS PositionTitle, d.Name AS DepartmentName
        FROM Recruitment r
        JOIN Position p ON r.PositionID = p.PositionID
        JOIN Department d ON p.DepartmentID = d.DepartmentID
        ORDER BY r.RecruitmentID ASC
    ";
    $recruitmentStmt = $conn->query($recruitmentQuery);
} else {
    // Fetch data filtered by department
    $recruitmentQuery = "
        SELECT r.RecruitmentID, r.FirstName, r.LastName, r.Gender, r.DOB, r.CandidateEmail, r.CandidateNum, 
               r.ApplicationDate, r.Resume, r.HiringStatus, p.Title AS PositionTitle, d.Name AS DepartmentName
        FROM Recruitment r
        JOIN Position p ON r.PositionID = p.PositionID
        JOIN Department d ON p.DepartmentID = d.DepartmentID
        WHERE p.DepartmentID = :departmentID
        ORDER BY r.RecruitmentID ASC
    ";
    $recruitmentStmt = $conn->prepare($recruitmentQuery);
    $recruitmentStmt->execute([':departmentID' => $departmentID]);
}

// Fetch all departments and positions
$departments = $conn->query("SELECT * FROM Department")->fetchAll(PDO::FETCH_ASSOC);
$positions = $conn->query("SELECT * FROM Position")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include(__DIR__ . '/include/sidebar.php'); ?>

<div class="content">
    <?php include(__DIR__ . '/include/header.php'); ?>
    <div class="main-content">
        <h1>Application</h1>

         <!-- Department Filter -->
         <form method="GET" class="mb-3">
            <select name="departmentID" class="form-select w-25 d-inline-block">
                <option value="all" <?= $departmentID === 'all' ? 'selected' : '' ?>>All Departments</option>
                <?php foreach ($departments as $department): ?>
                    <option value="<?= $department['DepartmentID'] ?>" <?= isset($departmentID) && $departmentID == $department['DepartmentID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($department['Name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Gender</th>
                    <th>Date of Birth</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Position</th>
                    <th>Application Date</th>
                    <th>Resume</th>
                    <th>Hiring Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['message']) ?>
                </div>
            <?php unset($_SESSION['message']); endif; ?>


            <tbody>
            <?php 
            $counter = 1;
            while ($row = $recruitmentStmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= $counter++ ?></td>
                <td><?= htmlspecialchars($row['FirstName']) ?></td>
                <td><?= htmlspecialchars($row['LastName']) ?></td>
                <td><?= htmlspecialchars($row['Gender']) ?></td>
                <td><?= htmlspecialchars($row['DOB']) ?></td>
                <td><?= htmlspecialchars($row['CandidateEmail']) ?></td>
                <td><?= htmlspecialchars($row['CandidateNum']) ?></td>
                <td><?= htmlspecialchars($row['PositionTitle'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($row['ApplicationDate']) ?></td>
                <td><a href="uploads/resumes/<?= htmlspecialchars($row['Resume']) ?>" target="_blank">Download</a></td>
                <td><?= htmlspecialchars($row['HiringStatus']) ?></td>
                <td>
                    <?php if ($row['HiringStatus'] === 'Applied'): ?>
                        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#screenResumeModal" 
                                data-recruitmentid="<?= $row['RecruitmentID'] ?>">Screen Resume</button>
                    <?php else: ?>
                        <button class="btn btn-secondary" disabled>Processed</button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>

        </table>
    </div>
</div>

<!-- Screen Resume Modal -->
<div class="modal fade" id="screenResumeModal" tabindex="-1" aria-labelledby="screenResumeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="screenResumeModalLabel">Screen Resume</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <p>Do you approve this resume?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" id="screenResumeForm">
                    <input type="hidden" name="recruitmentID" id="recruitmentIDScreen">
                    <input type="hidden" name="interviewDate" value="2025-01-13">
                    <input type="hidden" name="interviewLocation" value="Interview Room 1">
                    <button type="submit" name="screenResume" value="Passed" id="passButton" class="btn btn-success">Pass</button>
                    <button type="submit" name="screenResume" value="Failed" id="failButton" class="btn btn-danger">Fail</button>
                    <button type="submit" name="screenResume" value="Confirm" id="confirmButton" class="btn btn-primary d-none">Confirm</button>
                    <button type="submit" name="screenResume" value="Back" id="backButton" class="btn btn-warning d-none">Back</button>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const screenResumeModal = document.getElementById('screenResumeModal');
    const passButton = document.getElementById('passButton');
    const failButton = document.getElementById('failButton');
    const confirmButton = document.getElementById('confirmButton');
    const backButton = document.getElementById('backButton');
    const modalBody = document.getElementById('modalBody');
    
    // When modal is shown
    screenResumeModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // The button that triggered the modal
        const recruitmentID = button.getAttribute('data-recruitmentid');
        document.getElementById('recruitmentIDScreen').value = recruitmentID;
        
        // Reset modal to initial state when opening
        modalBody.innerHTML = "<p>Do you approve this resume?</p>";
        passButton.classList.remove('d-none');
        failButton.classList.remove('d-none');
        confirmButton.classList.add('d-none');
        backButton.classList.add('d-none');
    });
    
    // Handle "Pass" button click
   passButton.addEventListener('click', function (event) {
    event.preventDefault(); // Prevent the form from being submitted

    console.log('Pass button clicked'); // Debugging log
    
    // Update modal content for date and location selection
    modalBody.innerHTML = `
        <p>Interview Date: 
            <input type="date" name="interviewDate" required>
        </p>
        <p>Interview Location:
            <select name="interviewLocation" required>
                <option value="Interview Room 1">Interview Room 1</option>
                <option value="Interview Room 2">Interview Room 2</option>
                <option value="Interview Room 3">Interview Room 3</option>
            </select>
        </p>
        <p>Do you confirm the details?</p>
    `;

    // Hide Pass and Fail buttons, show Confirm and Back buttons
    passButton.classList.add('d-none');
    failButton.classList.add('d-none');
    confirmButton.classList.remove('d-none');
    backButton.classList.remove('d-none');
});

    // Handle "Back" button click to reset modal to initial state
    backButton.addEventListener('click', function () {
        modalBody.innerHTML = "<p>Do you approve this resume?</p>";
        passButton.classList.remove('d-none');
        failButton.classList.remove('d-none');
        confirmButton.classList.add('d-none');
        backButton.classList.add('d-none');
    });

    // Handle "Fail" button click
    failButton.addEventListener('click', function () {
        // Reset modal content if Fail is clicked
        modalBody.innerHTML = "<p>Candidate Rejected</p>";
        passButton.classList.add('d-none');
        failButton.classList.add('d-none');
        confirmButton.classList.add('d-none');
        backButton.classList.add('d-none');
    });
});
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>