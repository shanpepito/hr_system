<?php
session_start();
require_once '../config/config.php';

// Ensure the user is logged in and is a Manager or Admin
if (!isset($_SESSION['Role']) || ($_SESSION['Role'] !== 'Manager' && $_SESSION['Role'] !== 'Admin')) {
    header("Location: ../login/login.php");
    exit();
}

// Get the appraisal ID from the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: view_appraisal.php");
    exit();
}

$appraisalID = $_GET['id'];

// Fetch the appraisal details from the database
$query = "SELECT * FROM appraisal WHERE AppraisalID = :appraisalID";
$stmt = $conn->prepare($query);
$stmt->bindParam(':appraisalID', $appraisalID);
$stmt->execute();
$appraisal = $stmt->fetch(PDO::FETCH_ASSOC);

// If no appraisal is found, redirect back to the appraisals list
if (!$appraisal) {
    header("Location: view_appraisal.php");
    exit();
}

// Check if the form was submitted to update the feedback and evaluation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data for feedback and evaluation
    $managerFeedback = $_POST['managerFeedback'];
    $evaluationStressResistance = $_POST['stressResistance'];
    $evaluationTimeManagement = $_POST['timeManagement'];
    $evaluationTeamwork = $_POST['teamwork'];
    $evaluationAutonomy = $_POST['autonomy'];

    // Prepare the query with only feedback and evaluation data
    $updateQuery = "UPDATE appraisal SET 
                    ManagerFeedback = :managerFeedback,
                    StressResistance = :stressResistance,
                    TimeManagement = :timeManagement,
                    Teamwork = :teamwork,
                    Autonomy = :autonomy
                    WHERE AppraisalID = :appraisalID";
    
    // If Skills or PrivateNote are part of the form, include them
    if (!empty($_POST['skills']) && !empty($_POST['privateNote'])) {
        $updateQuery .= ", Skills = :skills, PrivateNote = :privateNote";
    }

    // Prepare statement
    $stmt = $conn->prepare($updateQuery);

    // Bind parameters
    $stmt->bindParam(':managerFeedback', $managerFeedback);
    $stmt->bindParam(':stressResistance', $evaluationStressResistance);
    $stmt->bindParam(':timeManagement', $evaluationTimeManagement);
    $stmt->bindParam(':teamwork', $evaluationTeamwork);
    $stmt->bindParam(':autonomy', $evaluationAutonomy);
    $stmt->bindParam(':appraisalID', $appraisalID);

    // Bind Skills and PrivateNote only if they were provided
    if (!empty($_POST['skills']) && !empty($_POST['privateNote'])) {
        $skills = $_POST['skills'];
        $privateNote = $_POST['privateNote'];
        $stmt->bindParam(':skills', $skills);
        $stmt->bindParam(':privateNote', $privateNote);
    }

    // Execute the update query
    if ($stmt->execute()) {
        $_SESSION['message'] = "Feedback, Evaluation, and optionally Skills and Private Note updated successfully!";
        header("Location: view_appraisal_details.php?id=" . $appraisalID);
        exit();
    } else {
        $_SESSION['error'] = "There was an error updating the appraisal. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appraisal Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Include Sidebar -->
    <?php include(__DIR__ . '/include/sidebar.php'); ?>

    <div class="content">
        <!-- Include Header -->
        <?php include(__DIR__ . '/include/header.php'); ?>

        <div class="main-content">
            <h3>Appraisal Details</h3>

            <div class="card">
                <div class="card-header">
                    <h5>Employee: <?php echo htmlspecialchars($appraisal['EmployeeName']); ?></h5>
                </div>
                <div class="card-body">
                    <p><strong>Manager Name:</strong> <?php echo htmlspecialchars($appraisal['ManagerName']); ?></p>
                    <p><strong>Department:</strong> <?php echo htmlspecialchars($appraisal['Department']); ?></p>
                    <p><strong>Appraisal Date:</strong> <?php echo htmlspecialchars($appraisal['AppraisalDate']); ?></p>
                    <p><strong>Company:</strong> <?php echo htmlspecialchars($appraisal['Company']); ?></p>

                    <h6>Employee Feedback</h6>
                    <p><strong>My Work:</strong> <?php echo nl2br(htmlspecialchars($appraisal['EmployeeWork'])); ?></p>
                    <p><strong>Challenges:</strong> <?php echo nl2br(htmlspecialchars($appraisal['EmployeeChallenges'])); ?></p>
                    <p><strong>Improvements:</strong> <?php echo nl2br(htmlspecialchars($appraisal['EmployeeImprovements'])); ?></p>

                    <h6>Additional Information</h6>
                    <p><strong>Skills:</strong> <?php echo nl2br(htmlspecialchars($appraisal['Skills'])); ?></p>
                    <p><strong>Private Note:</strong> <?php echo nl2br(htmlspecialchars($appraisal['PrivateNote'])); ?></p>

<!-- Form to update feedback and evaluation -->
<form action="" method="POST">
    <div class="mb-3">
        <label for="managerFeedback" class="form-label">Manager's Feedback</label>
        <textarea class="form-control" id="managerFeedback" name="managerFeedback" rows="3" placeholder="Provide your feedback on the employee's performance." required><?php echo htmlspecialchars($appraisal['ManagerFeedback']); ?></textarea>
    </div>

    <h6>Evaluation</h6>
    <div class="mb-3">
        <label for="stressResistance" class="form-label">Stress Resistance</label>
        <input type="number" class="form-control" id="stressResistance" name="stressResistance" min="1" max="5" value="<?php echo htmlspecialchars($appraisal['StressResistance']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="timeManagement" class="form-label">Time Management</label>
        <input type="number" class="form-control" id="timeManagement" name="timeManagement" min="1" max="5" value="<?php echo htmlspecialchars($appraisal['TimeManagement']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="teamwork" class="form-label">Teamwork</label>
        <input type="number" class="form-control" id="teamwork" name="teamwork" min="1" max="5" value="<?php echo htmlspecialchars($appraisal['Teamwork']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="autonomy" class="form-label">Autonomy</label>
        <input type="number" class="form-control" id="autonomy" name="autonomy" min="1" max="5" value="<?php echo htmlspecialchars($appraisal['Autonomy']); ?>" required>
    </div>

    <button type="submit" class="btn btn-primary">Update Feedback</button>
</form>


                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
