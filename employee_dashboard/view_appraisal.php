<?php
session_start();
require_once '../config/config.php';

// Ensure the user is logged in
if (!isset($_SESSION['UserID'])) {
    header("Location: ../login/login.php");
    exit();
}

// Assuming the logged-in user’s EmployeeID is stored in the session
$userID = $_SESSION['UserID']; 

// Fetch the EmployeeID for this user from the 'users' table
$query = "SELECT EmployeeID FROM users WHERE UserID = :userID";
$stmt = $conn->prepare($query);
$stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
$stmt->execute();

// Check if the query was successful
if ($stmt->errorCode() != '00000') {
    echo "Error executing query: " . implode(", ", $stmt->errorInfo());
}

// Fetch the result
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the user is found
if (!$user) {
    echo "User not found.";
    exit();
}

// Now that we have the EmployeeID, fetch the appraisal data for this employee

// Now that we have the EmployeeID, fetch the employee's name and appraisal data
$employeeID = $user['EmployeeID'];

$query = "
    SELECT e.FirstName, e.LastName, a.* 
    FROM appraisal a 
    JOIN employees e ON a.EmployeeID = e.EmployeeID
    WHERE a.EmployeeID = :employeeID
";
$stmt = $conn->prepare($query);
$stmt->bindParam(':employeeID', $employeeID, PDO::PARAM_INT);
$stmt->execute();

// Fetch appraisal data
$appraisals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Appraisal</title>
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
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .container {
            max-width: 900px;
            margin-top: 50px;
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .accordion-item {
            border-radius: 10px;
            margin-bottom: 15px;
        }
        .accordion-button {
            border-radius: 10px;
            background-color: #007bff;
            color: #fff;
        }
        .accordion-button:not(.collapsed) {
            background-color: #0056b3;
        }
        .accordion-body {
            font-size: 1.1em;
        }
    </style>
</head>
<body>
<?php include(__DIR__ . '/include/sidebar.php'); ?>

<div class="content">
    <?php include(__DIR__ . '/include/header.php'); ?>

    <div class="main-content">
        <div class="form-title text-center mb-4">Employee Appraisal</div>

        <!-- Check if appraisal data exists -->
        <?php if ($appraisals): ?>
            <div class="container">
                <div class="accordion" id="appraisalAccordion">
                    <?php foreach ($appraisals as $index => $appraisal): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading<?= $index ?>">
                                <button class="accordion-button <?= $index === 0 ? '' : 'collapsed' ?>" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#collapse<?= $index ?>" 
                                        aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>" 
                                        aria-controls="collapse<?= $index ?>">
                                    Appraisal for <?= htmlspecialchars($appraisal['FirstName'] . ' ' . $appraisal['LastName']) ?>
                                </button>
                            </h2>
                            <div id="collapse<?= $index ?>" 
                                 class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" 
                                 aria-labelledby="heading<?= $index ?>" 
                                 data-bs-parent="#appraisalAccordion">
                                <div class="accordion-body">
                                    <p><strong>Quality of Work:</strong> <?= $appraisal['QualityOfWork'] ?></p>
                                    <p><strong>Communication Skills:</strong> <?= $appraisal['CommunicationSkills'] ?></p>
                                    <p><strong>Teamwork:</strong> <?= $appraisal['TeamWork'] ?></p>
                                    <p><strong>Punctuality:</strong> <?= $appraisal['Punctuality'] ?></p>
                                    <p><strong>Overall Performance Rating:</strong> <?= $appraisal['PerformanceRating'] ?></p>
                                    <p><strong>Additional Comments:</strong> <?= nl2br($appraisal['comments']) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <p class="text-center text-muted">No appraisal data found.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</div>
</body>
</html>

