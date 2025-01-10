<?php
session_start();
include('../config/config.php'); 
if (isset($_SESSION['role'])) {
    header("Location: dashboard.php"); 
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT * FROM Users WHERE Username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['Password'])) {
        // Set session variables
        $_SESSION['UserID'] = $user['UserID'];
        $_SESSION['Username'] = $user['Username'];
        $_SESSION['EmployeeID'] = $user['EmployeeID'];
        $_SESSION['Role'] = $user['Role'];
        
        // Redirect based on role
        if ($user['Role'] == 'Admin') {
            header("Location: ../admin_dashboard/dashboard.php"); 
        } elseif ($user['Role'] == 'Manager') {
            header("Location: ../manager_dashboard/dashboard.php"); 
        } else {
            header("Location: ../employee_dashboard/dashboard.php"); 
        }
        exit();
    } else {
        $error = 'Invalid username or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4" style="width: 400px;">
        <h3 class="text-center mb-4">Login</h3>
        
        <?php if ($error): ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="form-group position-relative">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <i class="fas fa-eye position-absolute" id="togglePassword" style="right: 10px; top: 45px; cursor: pointer;"></i>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>

        <div class="text-center mt-3">
            <a href="#">Forgot password?</a>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordField = document.getElementById('password');
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;
        this.classList.toggle('fa-eye-slash');
    });
</script>
</body>
</html>
