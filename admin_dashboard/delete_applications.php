<?php
session_start();
require_once '../config/config.php';

// Ensure user is logged in and is an Admin
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Admin') {
    header("Location: ../login/login.php");
    exit();
}

// Delete application logic
if (isset($_GET['id'])) {
    $recruitmentID = $_GET['id']; // Correctly assign the ID to $recruitmentID

    try {
        // Prepare DELETE query
        $stmt = $conn->prepare("DELETE FROM Recruitment WHERE RecruitmentID = :recruitmentID");
        $stmt->bindParam(':recruitmentID', $recruitmentID, PDO::PARAM_INT); // Bind the correct variable
        $stmt->execute();

        // Redirect back to application list
        header("Location: applications.php");
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Redirect if no application ID is provided
    header("Location: applications.php");
    exit();
}
?>
