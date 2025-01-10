<?php 
// Include the database connection file
include('../config/config.php');

// Fetch Employee IDs from the Employee table
$employees = [];
try {
    $query = "SELECT EmployeeID, CONCAT(FirstName, ' ', LastName) AS FullName FROM Employee";
    $query = "SELECT EmployeeID, CONCAT(FirstName, ' ', LastName) AS FullName FROM Employees";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger' role='alert'>Error: " . $e->getMessage() . "</div>";
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Collect form inputs
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $employee_id = $_POST['employee_id'];

    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    try {
        // Insert the new user into the Users table
        $sql = "INSERT INTO Users (EmployeeID, Username, Password, Role) 
                VALUES (:employee_id, :username, :password, :role)";
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);

        // Execute the query
        if ($stmt->execute()) {
            echo "<div class='alert alert-success' role='alert'>New user added successfully!</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Failed to add user.</div>";
        }
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Add New User</h1>

        <!-- Back button -->
        <div class="mb-3">
            <a href="javascript:history.back()" class="btn btn-secondary">Back</a>
        </div>

        <!-- Form starts here -->
        <form method="POST" action="add_user.php">
            <div class="mb-3">
                <label for="employee_id" class="form-label">Employee:</label>
                <select id="employee_id" name="employee_id" class="form-select" required>
                    <option value="" selected disabled>Select Employee</option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?php echo $employee['EmployeeID']; ?>">
                            <?php echo $employee['FullName']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role:</label>
                <select id="role" name="role" class="form-select" required>
                    <option value="Employee">Employee</option>
                    <option value="Admin">Admin</option>
                    <option value="Manager">Manager</option> <!-- Added Manager Role -->
                </select>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Add User</button>
            </div>
        </form>
        <!-- Form ends here -->
    </div>

    <!-- Bootstrap JS & Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
