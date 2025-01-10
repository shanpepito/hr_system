<?php
session_start();
require_once '../config/config.php';

// Ensure the user is logged in and has the right role (Admin or Manager)
if (!isset($_SESSION['Role']) || ($_SESSION['Role'] !== 'Admin' && $_SESSION['Role'] !== 'Manager')) {
    header("Location: ../login/login.php");
    exit();
}

// Check if the AppraisalID is set in the request
if (isset($_POST['AppraisalID'])) {
    $appraisalID = $_POST['AppraisalID'];

    // Prepare the delete query
    $query = "DELETE FROM appraisal WHERE AppraisalID = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $appraisalID, PDO::PARAM_INT);

    // Execute the query and handle success or failure
    if ($stmt->execute()) {
        $_SESSION['message'] = "Appraisal deleted successfully.";
    } else {
        $_SESSION['error'] = "There was an error deleting the appraisal. Please try again.";
    }
} else {
    $_SESSION['error'] = "Invalid appraisal ID.";
}

// Redirect back to the view appraisals page
header("Location: view_appraisal.php");
exit();
