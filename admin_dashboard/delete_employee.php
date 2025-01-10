<?php
session_start();
require_once '../config/config.php';

// Ensure user is logged in and is an Admin
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Admin') {
    header("Location: ../login/login.php");
    exit();
}

// Delete employee logic
if (isset($_GET['id'])) {
    $employeeID = $_GET['id'];

    try {
        // Prepare DELETE query
        $stmt = $conn->prepare("DELETE FROM Employees WHERE EmployeeID = :employeeID");
        $stmt->bindParam(':employeeID', $employeeID);
        $stmt->execute();

        // Redirect back to employee list
        header("Location: employee.php");
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Redirect if no employee ID is provided
    header("Location: employee.php");
    exit();
}
?>
