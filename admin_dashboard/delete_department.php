<?php
session_start();
require_once '../config/config.php';

// Ensure user is logged in and is an Admin
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Admin') {
    header("Location: ../login/login.php");
    exit();
}

// Check if department ID is set
if (isset($_GET['id'])) {
    $departmentID = $_GET['id'];

    try {
        // Start a transaction
        $conn->beginTransaction();

        // Delete all positions associated with the department first (to avoid foreign key constraint issues)
        $deletePositionsStmt = $conn->prepare("DELETE FROM Position WHERE DepartmentID = :id");
        $deletePositionsStmt->bindParam(':id', $departmentID);
        $deletePositionsStmt->execute();

        // Delete the department
        $deleteDeptStmt = $conn->prepare("DELETE FROM Department WHERE DepartmentID = :id");
        $deleteDeptStmt->bindParam(':id', $departmentID);
        $deleteDeptStmt->execute();

        // Commit transaction
        $conn->commit();

        // Redirect back to the department page
        header("Location: department.php");
        exit();
    } catch (Exception $e) {
        // Rollback in case of an error
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    // If ID is not provided, redirect to the department page
    header("Location: department.php");
    exit();
}
?>
