<?php
session_start();
require_once '../config/config.php';

// Ensure the user is logged in and authorized (Admin or Evaluator)
if (!isset($_SESSION['Role']) || ($_SESSION['Role'] !== 'Admin' && $_SESSION['Role'] !== 'Evaluator')) {
    header("Location: ../login/login.php");
    exit();
}

// Fetch rejected candidates based on selected category
$tableFilter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

try {
    // Fetch rejected applications
    $applicationsQuery = "
        SELECT 
            Recruitment.RecruitmentID, 
            Recruitment.FirstName, 
            Recruitment.LastName, 
            Recruitment.CandidateEmail, 
            Recruitment.hiringStatus AS Status, 
            Recruitment.ApplicationDate AS RejectionDate 
        FROM Recruitment 
        WHERE Recruitment.hiringStatus = 'Rejected'
        ORDER BY Recruitment.ApplicationDate ASC
    ";
    $stmtApplications = $conn->prepare($applicationsQuery);
    $stmtApplications->execute();
    $applications = $stmtApplications->fetchAll(PDO::FETCH_ASSOC);

        // Fetch rejected evaluations
    $evaluationsQuery = "
    SELECT 
        Candidateeval.RecruitmentID, 
        Candidateeval.FirstName, 
        Candidateeval.LastName, 
        Candidateeval.CandidateEmail, 
        Candidateeval.EvalStatus AS Status, 
        Candidateeval.EvaluationDate AS RejectionDate 
    FROM Candidateeval 
    WHERE Candidateeval.EvalStatus = 'Rejected'
    ORDER BY Candidateeval.EvaluationDate ASC
    ";
    $stmtEvaluations = $conn->prepare($evaluationsQuery);
    $stmtEvaluations->execute();
    $evaluations = $stmtEvaluations->fetchAll(PDO::FETCH_ASSOC);


    // Determine which data to display
    if ($tableFilter === 'application') {
        $rejectedCandidates = $applications;
    } elseif ($tableFilter === 'evaluation') {
        $rejectedCandidates = $evaluations;
    } else {
        // Combine applications and interviews for "all"
        $rejectedCandidates = array_merge($applications, $evaluations);

        // Sort combined data by RejectionDate (oldest to newest)
        usort($rejectedCandidates, function ($a, $b) {
            return strtotime($a['RejectionDate']) - strtotime($b['RejectionDate']);
        });
    }
} catch (PDOException $e) {
    die("Error fetching rejected candidates: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reject History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include(__DIR__ . '/include/sidebar.php'); ?>

<div class="content">
    <?php include(__DIR__ . '/include/header.php'); ?>
    <div class="main-content">
        <h1>Reject History</h1>

        <div class="d-flex mb-3">
            <a href="?filter=all" class="btn btn-secondary me-2">All Rejected Candidates</a>
            <a href="?filter=application" class="btn btn-primary me-2">Rejected Applications</a>
            <a href="?filter=evaluation" class="btn btn-success">Rejected Interviews</a>
        </div>

        <!-- Display Rejected Candidates -->
        <?php if (count($rejectedCandidates) > 0): ?>
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Rejection Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rejectedCandidates as $candidate): ?>
                        <tr>
                            <td><?= htmlspecialchars($candidate['RecruitmentID']) ?></td>
                            <td><?= htmlspecialchars($candidate['FirstName']) ?></td>
                            <td><?= htmlspecialchars($candidate['LastName']) ?></td>
                            <td><?= htmlspecialchars($candidate['CandidateEmail']) ?></td>
                            <td><?= htmlspecialchars($candidate['Status']) ?></td>
                            <td><?= htmlspecialchars($candidate['RejectionDate']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted">No rejected candidates found.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>