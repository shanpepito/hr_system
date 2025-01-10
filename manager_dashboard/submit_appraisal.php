<?php 
session_start();
require_once '../config/config.php';

// Ensure user is logged in and is an Employee
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Manager') {
    header("Location: ../login/login.php");
    exit();
}

// Get employee ID from session
<<<<<<< HEAD:manager_dashboard/submit_appraisal.php
$employeeID = $_SESSION['UserID'];  // Assuming UserID is storing the EmployeeID  
=======
$employeeID = $_SESSION['EmployeeID'];  // Change from 'UserID' to 'EmployeeID'
>>>>>>> e3ce2faf3494bb0025c7c86a6a92f8274b17e682:employee_dashboard/submit_appraisal.php

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get all form data
    $employeeName = $_POST['employeeName'];
    $appraisalDate = $_POST['appraisalDate'];
<<<<<<< HEAD:manager_dashboard/submit_appraisal.php
    $rating = $_POST['rating']; 
=======
    $employeeRating = $_POST['employeeRating']; // Changed from 'rating' to 'employeeRating'
>>>>>>> e3ce2faf3494bb0025c7c86a6a92f8274b17e682:employee_dashboard/submit_appraisal.php
    $employeeWork = $_POST['employeeWork'];
    $employeeChallenges = $_POST['employeeChallenges'];
    $employeeImprovements = $_POST['employeeImprovements'];

    // Prepare query
    $query = "INSERT INTO appraisal 
<<<<<<< HEAD:manager_dashboard/submit_appraisal.php
          (EmployeeID, EmployeeName, ManagerName, AppraisalDate, EmployeeRating, EmployeeWork, EmployeeChallenges, EmployeeImprovements) 
          VALUES 
          (:employee_id, :employee_name, :manager_name, :appraisal_date, :rating, :employee_work, :employee_challenges, :employee_improvements)";

=======
              (EmployeeID, EmployeeName, AppraisalDate, EmployeeRating, EmployeeWork, EmployeeChallenges, EmployeeImprovements) 
              VALUES 
              (:employee_id, :employee_name, :appraisal_date, :employee_rating, :employee_work, :employee_challenges, :employee_improvements)";
    
>>>>>>> e3ce2faf3494bb0025c7c86a6a92f8274b17e682:employee_dashboard/submit_appraisal.php
    // Prepare statement
    $stmt = $conn->prepare($query);
    
    // Bind parameters
<<<<<<< HEAD:manager_dashboard/submit_appraisal.php
    $stmt->bindParam(':employee_id', $employeeID); // Now binding the EmployeeID
=======
    $stmt->bindParam(':employee_id', $employeeID);  
>>>>>>> e3ce2faf3494bb0025c7c86a6a92f8274b17e682:employee_dashboard/submit_appraisal.php
    $stmt->bindParam(':employee_name', $employeeName);
    $stmt->bindParam(':appraisal_date', $appraisalDate);
    $stmt->bindParam(':employee_rating', $employeeRating); 
    $stmt->bindParam(':employee_work', $employeeWork);
    $stmt->bindParam(':employee_challenges', $employeeChallenges);
    $stmt->bindParam(':employee_improvements', $employeeImprovements);
    
    // Execute the query
    if ($stmt->execute()) {
        $_SESSION['message'] = "Appraisal submitted successfully!";
        header("Location: appraisal_success.php");
        exit();
    } else {
        $_SESSION['error'] = "There was an error submitting your appraisal. Please try again.";
        header("Location: appraisal.php");
        exit();
    }
}
?>
