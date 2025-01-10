<?php
session_start();
require_once '../config/config.php';

// Ensure the user is logged in and authorized (Admin or Evaluator)
if (!isset($_SESSION['Role']) || ($_SESSION['Role'] !== 'Admin' && $_SESSION['Role'] !== 'Evaluator')) {
    header("Location: ../login/login.php");
    exit();
}

// Handle hiring or rejecting the candidate
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['recruitmentID'])) {
        $recruitmentID = $_POST['recruitmentID'];
        $action = $_POST['action'];
        $evaluatorName = isset($_POST['evaluatorName']) ? trim($_POST['evaluatorName']) : 'Not Provided';
        
        // Fetch candidate details
        try {
            $stmt = $conn->prepare("
                SELECT r.RecruitmentID, r.FirstName, r.LastName, r.Gender, r.DOB, r.CandidateEmail, r.CandidateNum,
                       r.Address, r.DepartmentID, r.PositionID, r.ApplicationDate,
                       i.ScheduleID, i.InterviewDate, p.Title AS PositionTitle
                FROM Recruitment r
                LEFT JOIN InterviewSchedule i ON r.RecruitmentID = i.RecruitmentID
                LEFT JOIN Position p ON r.PositionID = p.PositionID
                WHERE r.RecruitmentID = :recruitmentID
            ");
            $stmt->bindParam(':recruitmentID', $recruitmentID);
            $stmt->execute();
            $candidate = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$candidate) {
                $_SESSION['error'] = "Candidate not found.";
                header("Location: candidates_eval.php");
                exit();
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Error fetching candidate details: " . $e->getMessage();
            header("Location: candidates_eval.php");
            exit();
        }

        // Insert into Candidateeval table
        try {
            $evaluationDate = date('Y-m-d H:i:s');
            $evalStatus = $action === 'hire' ? 'Hired' : 'Rejected';

            $stmt = $conn->prepare("
                INSERT INTO Candidateeval (
                    RecruitmentID, FirstName, LastName, Gender, DOB, CandidateEmail, CandidateNum, 
                    Address, DepartmentID, PositionID, ApplicationDate, ScheduleID, 
                    EvaluatorName, EvaluationDate, EvalStatus
                ) VALUES (
                    :recruitmentID, :firstName, :lastName, :gender, :dob, :candidateEmail, :candidateNum, 
                    :address, :departmentID, :positionID, :applicationDate, :scheduleID, 
                    :evaluatorName, :evaluationDate, :evalStatus
                )
            ");
            $stmt->bindParam(':recruitmentID', $candidate['RecruitmentID']);
            $stmt->bindParam(':firstName', $candidate['FirstName']);
            $stmt->bindParam(':lastName', $candidate['LastName']);
            $stmt->bindParam(':gender', $candidate['Gender']);
            $stmt->bindParam(':dob', $candidate['DOB']);
            $stmt->bindParam(':candidateEmail', $candidate['CandidateEmail']);
            $stmt->bindParam(':candidateNum', $candidate['CandidateNum']);
            $stmt->bindParam(':address', $candidate['Address']);
            $stmt->bindParam(':departmentID', $candidate['DepartmentID']);
            $stmt->bindParam(':positionID', $candidate['PositionID']);
            $stmt->bindParam(':applicationDate', $candidate['ApplicationDate']);
            $stmt->bindParam(':scheduleID', $candidate['ScheduleID']);
            $stmt->bindParam(':evaluatorName', $evaluatorName);
            $stmt->bindParam(':evaluationDate', $evaluationDate);
            $stmt->bindParam(':evalStatus', $evalStatus);

            $stmt->execute();
            // $_SESSION['message'] = "Candidate successfully " . ($action === 'hire' ? 'hired' : 'rejected') . ".";
        } catch (Exception $e) {
            $_SESSION['error'] = "Error updating candidate evaluation: " . $e->getMessage();
        }

        // Redirect back to candidate evaluation page
        header("Location: candidates_eval.php");
        exit();
    }
}

try {
    $stmt = $conn->prepare("
        UPDATE Candidateeval
        SET EvalStatus = :evalStatus, EvaluatorName = :evaluatorName, EvaluationDate = :evaluationDate
        WHERE RecruitmentID = :recruitmentID
    ");
    $stmt->bindParam(':evalStatus', $evalStatus); // 'Hired' or 'Rejected'
    $stmt->bindParam(':evaluatorName', $evaluatorName);
    $stmt->bindParam(':evaluationDate', $evaluationDate);
    $stmt->bindParam(':recruitmentID', $recruitmentID);
    $stmt->execute();
} catch (Exception $e) {
    $_SESSION['error'] = "Error updating candidate evaluation: " . $e->getMessage();
}

$departmentID = isset($_GET['departmentID']) ? $_GET['departmentID'] : 'all';

// Fetch candidates with the hiring status 'Passed' and filter by department if necessary
try {
    $baseQuery = "
        SELECT r.RecruitmentID, r.FirstName, r.LastName, r.Gender, r.DOB, r.CandidateEmail, r.CandidateNum, 
               r.Address, r.ApplicationDate, p.Title AS PositionTitle, 
               i.ScheduleID, i.InterviewDate, i.Location, 
               ce.EvalStatus AS EvaluationStatus
        FROM Recruitment r
        JOIN Position p ON r.PositionID = p.PositionID
        LEFT JOIN InterviewSchedule i ON r.RecruitmentID = i.RecruitmentID
        LEFT JOIN Candidateeval ce ON r.RecruitmentID = ce.RecruitmentID
        WHERE r.HiringStatus = 'Passed'
    ";

    if ($departmentID !== 'all') {
        $baseQuery .= " AND p.DepartmentID = :departmentID";
    }

    $baseQuery .= " ORDER BY r.RecruitmentID ASC";

    $stmt = $conn->prepare($baseQuery);

    if ($departmentID !== 'all') {
        $stmt->bindParam(':departmentID', $departmentID, PDO::PARAM_INT);
    }

    $stmt->execute();
    $candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

// Fetch all departments
$departments = $conn->query("SELECT * FROM Department")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Evaluation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include(__DIR__ . '/include/sidebar.php'); ?>

<div class="content">
    <?php include(__DIR__ . '/include/header.php'); ?>
    <div class="main-content">
        <h1>Candidate Evaluation</h1>

        <!-- Department Filter -->
        <form method="GET" class="mb-3">
            <select name="departmentID" class="form-select w-25 d-inline-block">
                <option value="all" <?= $departmentID === 'all' ? 'selected' : '' ?>>All Departments</option>
                <?php foreach ($departments as $department): ?>
                    <option value="<?= $department['DepartmentID'] ?>" 
                        <?= isset($departmentID) && $departmentID == $department['DepartmentID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($department['Name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>

        <!-- Candidate Evaluation Table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Address</th>
                    <th>Position</th>
                    <th>Application Date</th>
                    <th>Interview Date</th>
                    <th>Location</th>
                    <th>Evaluation Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($candidates as $index => $candidate): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($candidate['FirstName']) ?></td>
                        <td><?= htmlspecialchars($candidate['LastName']) ?></td>
                        <td><?= htmlspecialchars($candidate['Gender']) ?></td>
                        <td><?= htmlspecialchars($candidate['CandidateEmail']) ?></td>
                        <td><?= htmlspecialchars($candidate['CandidateNum']) ?></td>
                        <td><?= htmlspecialchars($candidate['Address']) ?></td>
                        <td><?= htmlspecialchars($candidate['PositionTitle']) ?></td>
                        <td><?= htmlspecialchars($candidate['ApplicationDate']) ?></td>
                        <td><?= htmlspecialchars($candidate['InterviewDate']) ?></td>
                        <td><?= htmlspecialchars($candidate['Location']) ?></td>
                        <td>
                            <strong>
                                <?= htmlspecialchars($candidate['EvaluationStatus'] ?? 'On Going Interview') ?>
                            </strong>
                        </td>
                        <td>
                            <?php if ($candidate['EvaluationStatus'] === 'Hired' || $candidate['EvaluationStatus'] === 'Rejected'): ?>
                                <span class="text-muted">Processed</span>
                            <?php else: ?>
                                <form method="POST" id="hireForm-<?= $candidate['RecruitmentID'] ?>" class="hireForm d-inline">
                                    <input type="hidden" name="recruitmentID" value="<?= $candidate['RecruitmentID'] ?>">
                                    <input type="hidden" name="action" value="hire">
                                    <input type="hidden" name="evaluatorName">
                                    <button type="button" class="btn btn-success hireButton" data-recruitmentid="<?= $candidate['RecruitmentID'] ?>">Hire</button>
                                </form>

                                <form method="POST" id="rejectForm-<?= $candidate['RecruitmentID'] ?>" class="d-inline">
                                    <input type="hidden" name="recruitmentID" value="<?= $candidate['RecruitmentID'] ?>">
                                    <input type="hidden" name="action" value="reject">
                                    <button type="button" class="btn btn-danger rejectButton" data-recruitmentid="<?= $candidate['RecruitmentID'] ?>">Reject</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Hire Candidate Modal -->
<div class="modal fade" id="hireModal" tabindex="-1" aria-labelledby="hireModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hireModalLabel">Hire Candidate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="hireModalBody">
                <p>Please enter your name to hire this candidate:</p>
                <input type="text" id="evaluatorName" class="form-control" placeholder="Enter your name" required>
                <input type="hidden" id="recruitmentIDHire">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmHireButton">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const hireModal = new bootstrap.Modal(document.getElementById('hireModal'));
    const confirmHireButton = document.getElementById('confirmHireButton');
    const evaluatorNameInput = document.getElementById('evaluatorName');
    const recruitmentIDInput = document.getElementById('recruitmentIDHire');

    // Handle Hire Button
    document.querySelectorAll('.hireButton').forEach(button => {
        button.addEventListener('click', function () {
            const recruitmentID = this.getAttribute('data-recruitmentid');
            recruitmentIDInput.value = recruitmentID;
            evaluatorNameInput.value = ""; // Clear input
            hireModal.show();
        });
    });

    // Confirm Hire
    confirmHireButton.addEventListener('click', function () {
        const evaluatorName = evaluatorNameInput.value.trim();
        const recruitmentID = recruitmentIDInput.value;

        if (evaluatorName === "") {
            alert("Please enter your name.");
            return;
        }

        // Submit the Hire Form
        const form = document.querySelector(`#hireForm-${recruitmentID}`);
        form.querySelector('[name="evaluatorName"]').value = evaluatorName;
        form.submit();
    });

        // Handle Reject Button
    document.querySelectorAll('.rejectButton').forEach(button => {
        button.addEventListener('click', function () {
            const recruitmentID = this.getAttribute('data-recruitmentid');
            const confirmReject = confirm("Are you sure you want to reject this candidate?");
            if (confirmReject) {
                // Submit the Reject Form
                document.querySelector(`#rejectForm-${recruitmentID}`).submit();
            }
        });
    });
});
</script>

</body>
</html>