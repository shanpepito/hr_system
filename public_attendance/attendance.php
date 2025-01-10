<?php
session_start(); // Start the session to store the message

require_once '../config/config.php'; // Database configuration file

// Initialize the message variable
$message = '';

// Only process the form submission if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeeID = $_POST['EmployeeID'] ?? '';

    if (!empty($employeeID)) {
        try {
            $date = date('Y-m-d');
            $time = date('H:i:s');

            // Check if the employee exists
            $stmt = $conn->prepare("SELECT * FROM employees WHERE employeeId = :EmployeeID");
            $stmt->execute([':EmployeeID' => $employeeID]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Check if attendance already exists for today
                $stmt = $conn->prepare("SELECT * FROM attendance WHERE EmployeeID = :EmployeeID AND Date = :Date");
                $stmt->execute([':EmployeeID' => $employeeID, ':Date' => $date]);
                $attendance = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$attendance) {
                    // Clock in
                    $stmt = $conn->prepare("INSERT INTO attendance (EmployeeID, Date, ClockIn, Status) VALUES (:EmployeeID, :Date, :ClockIn, 'Present')");
                    $stmt->execute([':EmployeeID' => $employeeID, ':Date' => $date, ':ClockIn' => $time]);
                    $_SESSION['message'] = "You have successfully clocked in.";
                } elseif (is_null($attendance['ClockOut'])) {
                    // Clock out
                    $stmt = $conn->prepare("UPDATE attendance SET ClockOut = :ClockOut WHERE AttendanceID = :AttendanceID");
                    $stmt->execute([':ClockOut' => $time, ':AttendanceID' => $attendance['AttendanceID']]);
                    $_SESSION['message'] = "You have successfully clocked out.";
                } else {
                    // User has already clocked in and out
                    $_SESSION['message'] = "You have already clocked in and out for today.";
                }

                // Calculate the attendance percentage
                $stmt = $conn->prepare("SELECT COUNT(*) AS total_attendance FROM attendance WHERE EmployeeID = :EmployeeID AND Status = 'Present'");
                $stmt->execute([':EmployeeID' => $employeeID]);
                $attendanceData = $stmt->fetch(PDO::FETCH_ASSOC);
                $totalAttendance = $attendanceData['total_attendance'];

                // Dynamically calculate total working days in the current month
                $currentMonth = date('m');
                $currentYear = date('Y');

                $startDate = new DateTime("$currentYear-$currentMonth-01");
                $endDate = new DateTime("$currentYear-$currentMonth-01");
                $endDate->modify('last day of this month');

                $days = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
                $totalDays = 0;

                foreach ($days as $day) {
                    if ($day->format('N') < 6) { // Count weekdays (Monday to Friday)
                        $totalDays++;
                    }
                }

                // Calculate attendance percentage
                $attendancePercentage = ($totalAttendance > 0 && $totalDays > 0) 
                    ? ($totalAttendance / $totalDays) * 100 
                    : 0;

                // Update the attendance percentage in the employees table
                $stmt = $conn->prepare("UPDATE employees SET attendance_percentage = :attendance_percentage WHERE employeeId = :EmployeeID");
                $stmt->execute([':attendance_percentage' => $attendancePercentage, ':EmployeeID' => $employeeID]);
            } else {
                $_SESSION['message'] = "Employee not found. Please check your Employee ID.";
            }

            // Redirect to avoid form resubmission on refresh
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;

        } catch (PDOException $e) {
            $_SESSION['message'] = "Error: " . $e->getMessage();
        }
    } else {
        $_SESSION['message'] = "Please enter your Employee ID.";
    }
}

// Retrieve message from session
$message = $_SESSION['message'] ?? '';

// Clear message from session after displaying it
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clock In/Out</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Public Clock In/Out System</h2>

        <!-- Display message only if it's set (i.e., after form submission) -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-info text-center">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- Form for EmployeeID -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="EmployeeID" class="form-label">Employee ID</label>
                <input type="text" class="form-control" id="EmployeeID" name="EmployeeID" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>