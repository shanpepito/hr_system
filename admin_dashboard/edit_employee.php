<?php
session_start();
require_once '../config/config.php';

// Ensure user is logged in and is an Admin
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Admin') {
    header("Location: ../login/login.php");
    exit();
}

// Fetch employee data if editing
if (isset($_GET['id'])) {
    $employeeId = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM Employees WHERE EmployeeID = :employeeId");
    $stmt->execute([':employeeId' => $employeeId]);
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle form submission for updating employee
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateEmployee'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $hireDate = $_POST['hireDate'];
    $departmentID = $_POST['departmentID'];
    $positionID = $_POST['positionID'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    try {
        $stmt = $conn->prepare("UPDATE Employees SET 
                                FirstName = :firstName, 
                                LastName = :lastName, 
                                Gender = :gender, 
                                DOB = :dob, 
                                Hire_Date = :hireDate, 
                                DepartmentID = :departmentID, 
                                PositionID = :positionID, 
                                Email = :email, 
                                Phone = :phone, 
                                Address = :address 
                                WHERE EmployeeID = :employeeId");

        $stmt->execute([
            ':firstName' => $firstName,
            ':lastName' => $lastName,
            ':gender' => $gender,
            ':dob' => $dob,
            ':hireDate' => $hireDate,
            ':departmentID' => $departmentID,
            ':positionID' => $positionID,
            ':email' => $email,
            ':phone' => $phone,
            ':address' => $address,
            ':employeeId' => $employeeId,
        ]);

        header("Location: employee.php");
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch department options for dropdown
$departments = $conn->query("SELECT * FROM Department")->fetchAll(PDO::FETCH_ASSOC);
$positions = $conn->query("SELECT * FROM Position")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
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
        .form-control, .form-select, .form-label {
            font-size: 1rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .btn {
            width: 100%;
            font-size: 1rem;
        }
    </style>
</head>
<body>

    <!-- Include Sidebar -->
    <?php include(__DIR__ . '/include/sidebar.php'); ?>

    <div class="content">
        <?php include(__DIR__ . '/include/header.php'); ?>
        
        <div class="main-content">
            <h1 class="mb-4">Edit Employee</h1>

            <form method="POST" action="edit_employee.php?id=<?= $employee['EmployeeID'] ?>">
                <div class="form-group">
                    <label for="firstName" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" value="<?= $employee['FirstName'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="lastName" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" value="<?= $employee['LastName'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-select" id="gender" name="gender" required>
                        <option value="Male" <?= $employee['Gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= $employee['Gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                        <option value="Other" <?= $employee['Gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="dob" name="dob" value="<?= $employee['DOB'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="hireDate" class="form-label">Hire Date</label>
                    <input type="date" class="form-control" id="hireDate" name="hireDate" value="<?= $employee['Hire_Date'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="departmentID" class="form-label">Department</label>
                    <select class="form-select" id="departmentID" name="departmentID" required>
                        <?php foreach ($departments as $department): ?>
                            <option value="<?= $department['DepartmentID'] ?>" <?= $employee['DepartmentID'] == $department['DepartmentID'] ? 'selected' : '' ?>>
                                <?= $department['Name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="positionID" class="form-label">Position</label>
                    <select class="form-select" id="positionID" name="positionID" required>
                        <?php foreach ($positions as $position): ?>
                            <option value="<?= $position['PositionID'] ?>" <?= $employee['PositionID'] == $position['PositionID'] ? 'selected' : '' ?>>
                                <?= $position['Title'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= $employee['Email'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?= $employee['Phone'] ?>" required>
                </div>

                <div class="form-group">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" required><?= $employee['Address'] ?></textarea>
                </div>
                <button type="submit" name="updateEmployee" class="btn btn-primary">Update Employee</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
