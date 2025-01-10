<?php
session_start();

// Check if the appraisal submission was successful
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    // Clear the message after displaying it
    unset($_SESSION['message']);
} else {
    // Redirect if the message is not set (i.e., no submission happened)
    header("Location: appraisal.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appraisal Submission Success</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            margin: 50px auto;
            max-width: 600px;
            border: none;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #28a745;
            color: white;
            border-radius: 10px 10px 0 0;
        }
        .card-body {
            text-align: center;
        }
        .btn-back {
            background-color: #007bff;
            color: white;
        }
        .btn-back:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <!-- Include Sidebar -->
    <?php include(__DIR__ . '/include/sidebar.php'); ?>

    <div class="content">
        <!-- Include Header -->
        <?php include(__DIR__ . '/include/header.php'); ?>

        <div class="main-content">
            <div class="card">
                <div class="card-header">
                    <h4>Success!</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-success" role="alert">
                        <h5 class="alert-heading">Appraisal Submitted Successfully</h5>
                        <p><?php echo htmlspecialchars($message); ?></p>
                        <hr>
                        <p class="mb-0">Thank you for your submission! You can go back to the dashboard or continue working on other tasks.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
