<?php
session_start();
require_once '../config/config.php';

// Ensure user is logged in and is an Admin
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Admin') {
    header("Location: ../login/login.php");
    exit();
}

// Check if position ID is set
if (isset($_GET['id'])) {
    $positionID = $_GET['id'];

    try {
        // Start a transaction
        $conn->beginTransaction();

        // Delete the position from the database
        $deleteStmt = $conn->prepare("DELETE FROM Position WHERE PositionID = :id");
        $deleteStmt->bindParam(':id', $positionID);
        $deleteStmt->execute();

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
