<?php 
session_start();
require_once '../config/config.php';

// Ensure user is logged in and is an Admin
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Admin') {
    header("Location: ../login/login.php");
    exit();
}

// Handle form submissions for adding an employee
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addEmployee'])) {
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
        $stmt = $conn->prepare("INSERT INTO Employees (FirstName, LastName, Gender, DOB, Hire_Date, DepartmentID, PositionID, Email, Phone, Address) 
                                VALUES (:firstName, :lastName, :gender, :dob, :hireDate, :departmentID, :positionID, :email, :phone, :address)");
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
        ]);

        header("Location: employee.php");
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Insert hired candidates into Employees table
try {
    // Fetch hired candidates from Candidateeval table
    $hiredCandidatesStmt = $conn->prepare("
        SELECT c.EvaluationID, c.FirstName, c.LastName, c.Gender, c.DOB, c.CandidateEmail AS Email, c.CandidateNum AS Phone, c.Address, c.EvaluationDate AS Hire_Date,
               c.DepartmentID, c.PositionID
        FROM Candidateeval c
        WHERE c.EvalStatus = :evalStatus
    ");
    $hiredCandidatesStmt->execute([':evalStatus' => 'Hired']);
    $hiredCandidates = $hiredCandidatesStmt->fetchAll(PDO::FETCH_ASSOC);

    // Start a transaction
    $conn->beginTransaction();

    if ($hiredCandidates) {
        foreach ($hiredCandidates as $candidate) {
            // Check if the candidate's email already exists in Employees table
            $checkEmailStmt = $conn->prepare("
                SELECT COUNT(*) FROM Employees WHERE Email = :email
            ");
            $checkEmailStmt->execute([':email' => $candidate['Email']]);
            $emailExists = $checkEmailStmt->fetchColumn();

            if ($emailExists == 0) {
                // Insert candidate into Employees table only if email doesn't exist
                $insertStmt = $conn->prepare("
                    INSERT INTO Employees (FirstName, LastName, Gender, DOB, Email, Phone, Address, Hire_Date, DepartmentID, PositionID)
                    VALUES (:firstName, :lastName, :gender, :dob, :email, :phone, :address, :hireDate, :departmentID, :positionID)
                ");
                $insertStmt->execute([
                    ':firstName' => $candidate['FirstName'],
                    ':lastName' => $candidate['LastName'],
                    ':gender' => $candidate['Gender'],
                    ':dob' => $candidate['DOB'],
                    ':email' => $candidate['Email'],
                    ':phone' => $candidate['Phone'],
                    ':address' => $candidate['Address'],
                    ':hireDate' => $candidate['Hire_Date'],
                    ':departmentID' => $candidate['DepartmentID'],
                    ':positionID' => $candidate['PositionID']
                ]);
            } 
        }

        // Commit the transaction
        $conn->commit();
    } else {
        echo "No hired candidates found to insert.";
    }
} catch (Exception $e) {
    // Rollback the transaction in case of error
    $conn->rollBack();
    echo "Error: " . $e->getMessage();
}

// Fetch employees or employees by department
if (isset($_GET['departmentID']) && $_GET['departmentID'] != 'all') {
    $departmentID = $_GET['departmentID'];
    $employeesStmt = $conn->prepare("
        SELECT e.EmployeeID, e.FirstName, e.LastName, e.Gender, e.DOB, e.Hire_Date, 
               d.Name AS DepartmentName, p.Title AS PositionTitle, e.Email, e.Phone, e.Address
        FROM Employees e
        JOIN Department d ON e.DepartmentID = d.DepartmentID
        JOIN Position p ON e.PositionID = p.PositionID
        WHERE e.DepartmentID = :departmentID
    ");
    $employeesStmt->execute([':departmentID' => $departmentID]);
    $employees = $employeesStmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $employeesStmt = $conn->query("
        SELECT e.EmployeeID, e.FirstName, e.LastName, e.Gender, e.DOB, e.Hire_Date, 
               d.Name AS DepartmentName, p.Title AS PositionTitle, e.Email, e.Phone, e.Address
        FROM Employees e
        JOIN Department d ON e.DepartmentID = d.DepartmentID
        JOIN Position p ON e.PositionID = p.PositionID
    ");
    $employees = $employeesStmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to fetch positions based on department ID
function getPositionsByDepartment($conn, $departmentID) {
    $stmt = $conn->prepare("SELECT p.PositionID, p.Title FROM Position p WHERE p.DepartmentID = :departmentID");
    $stmt->bindParam(':departmentID', $departmentID, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch department options for dropdown
$departments = $conn->query("SELECT * FROM Department")->fetchAll(PDO::FETCH_ASSOC);
$positions = $conn->query("SELECT * FROM Position")->fetchAll(PDO::FETCH_ASSOC);
// Handle AJAX request to fetch positions dynamically based on department selection
if (isset($_GET['ajax']) && $_GET['ajax'] == '1' && isset($_GET['departmentID'])) {
    $departmentID = $_GET['departmentID'];
    $positions = getPositionsByDepartment($conn, $departmentID);
    echo json_encode($positions);  // Return positions as JSON response
    exit;
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
    <title>Employees</title>
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
            padding: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-new {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include(__DIR__ . '/include/sidebar.php'); ?>

    <div class="content">
    <?php include(__DIR__ . '/include/header.php'); ?>
        <div class="main-content">
            <h1>Employees</h1>
            <button class="btn btn-primary btn-new" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                <i class="fas fa-plus"></i>
            </button>

            <!-- Department Filter -->
            <form method="GET" class="mb-3">
                <select name="departmentID" class="form-select w-25 d-inline-block">
                    <option value="all">All Departments</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?= $department['DepartmentID'] ?>" <?= isset($departmentID) && $departmentID == $department['DepartmentID'] ? 'selected' : '' ?>>
                            <?= $department['Name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>

            <!-- Display Employees -->
            <div class="row">
                <?php foreach ($employees as $row): ?>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <?= $row['FirstName'] . ' ' . $row['LastName'] ?>
                            </div>
                            <div class="card-body">
                                <ul class="employee-details">
                                    <li><strong>Gender:</strong> <?= $row['Gender'] ?? 'N/A' ?></li>
                                    <li><strong>Date of Birth:</strong> <?= $row['DOB'] ?? 'N/A' ?></li>
                                    <li><strong>Hire Date:</strong> <?= $row['Hire_Date'] ?></li>
                                    <li><strong>Department:</strong> <?= $row['DepartmentName'] ?></li>
                                    <li><strong>Position:</strong> <?= $row['PositionTitle'] ?></li>
                                    <li><strong>Email:</strong> <?= $row['Email'] ?></li>
                                    <li><strong>Phone:</strong> <?= $row['Phone'] ?></li>
                                    <li><strong>Address:</strong> <?= $row['Address'] ?></li>
                                </ul>
                                <div class="d-flex justify-content-between mt-3">
                                    <a href="edit_employee.php?id=<?= $row['EmployeeID'] ?>" class="btn btn-warning btn-sm">
                                        Edit
                                    </a>
                                    <a href="delete_employee.php?id=<?= $row['EmployeeID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this entry?')">
                                        Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                <div class="container mt-2">
    <div class="row">
        <!-- Left Column -->
        <div class="col-md-6">
            <div class="card p-3 mb-3">
                <div class="card-body">
                    <h5 class="card-title">Personal Information</h5>
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" id="firstName" name="firstName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" id="lastName" name="lastName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select id="gender" name="gender" class="form-control" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input type="date" id="dob" name="dob" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" id="address" name="address" class="form-control" required>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column -->
        <div class="col-md-6">
            <div class="card p-3 mb-3">
                <div class="card-body">
                    <h5 class="card-title">Job Information</h5>
                    <div class="mb-3">
                        <label for="departmentID" class="form-label">Department</label>
                        <select id="departmentID" name="departmentID" class="form-select" onchange="fetchPositionsByDepartment()" required>
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $department): ?>
                                <option value="<?= $department['DepartmentID'] ?>"><?= $department['Name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="positionID" class="form-label">Position</label>
                        <select id="positionID" name="positionID" class="form-select" required>
                            <option value="">Select Position</option>
                            <?php foreach ($positions as $position): ?>
                                <option value="<?= $position['PositionID'] ?>"><?= $position['Title'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="hireDate" class="form-label">Hire Date</label>
                        <input type="date" id="hireDate" name="hireDate" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" id="phone" name="phone" class="form-control" required>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="addEmployee" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Fetch Positions based on selected Department
    function fetchPositionsByDepartment() {
        var departmentID = document.getElementById('departmentID').value;
        var positionDropdown = document.getElementById('positionID');

        // Clear previous positions
        positionDropdown.innerHTML = '<option value="">Select Position</option>';

        if (departmentID) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '?ajax=1&departmentID=' + departmentID, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var positions = JSON.parse(xhr.responseText);
                    positions.forEach(function(position) {
                        var option = document.createElement('option');
                        option.value = position.PositionID;
                        option.textContent = position.Title;
                        positionDropdown.appendChild(option);
                    });
                }
            };
            xhr.send();
        }
    }
</script>

</body>
</html>
