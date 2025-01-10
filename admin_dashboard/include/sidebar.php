<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            width: 250px;
            position: fixed;
            height: 100vh;
            background: #1d2939;
            box-shadow: 0px 2px 15px rgba(0, 0, 0, 0.1);
            padding-top: 20px;
            overflow-y: auto;
            transition: transform 0.3s ease-in-out;
        }
        .sidebar a {
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            color: #fff;
            border-radius: 5px;
            margin: 10px 0;
        }
        .sidebar a:hover {
            background: rgb(29,41,57);
            background: linear-gradient(90deg, rgba(29,41,57,1) 0%, rgba(2,113,141,1) 35%, rgba(84,175,161,1) 100%);
            color: #fff;
        }
        .submenu-item {
            padding-left: 40px;
            font-size: 14px;
        }
        .content {
            margin-left: 270px;
            padding: 20px;
        }
        .text-center {
            color: #fff;
        }
        .card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(75, 36, 36, 0.1);
        }
        /* Hide sidebar in mobile view */
        @media (max-width: 767px) {
            .sidebar {
                transform: translateX(-250px); /* Hide sidebar */
                z-index: 1000;
            }
            .sidebar.show {
                transform: translateX(0); /* Show sidebar when toggled */
                z-index:1000;
            }
            .content {
                margin-left: 0; /* Full-width content on mobile */
            }
            .navbar-toggler {
                display: block; /* Show hamburger button on mobile */
            }
        }

        /* Style for the button */
        .navbar-toggler {
            background-color: black;
            border: none;
            padding: 10px;
            color: white;
            font-size: 20px;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 9999;
            border-radius: 5px;
        }

        /* Color the icon inside the button */
        .navbar-toggler-icon {
            color: black; /* Icon color set to black */
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center">Admin Panel</h4>
        <a href="dashboard.php"><i class="fa fa-tachometer-alt"></i> Dashboard</a>
        
        <!-- Department & Position -->
        <a href="department.php"><i class="fa fa-building"></i> Department & Position</a>
        
        <!-- Employee with submenu -->
        <a href="#" class="submenu-toggle" data-bs-toggle="collapse" data-bs-target="#employeeSubmenu">
            <i class="fa fa-users"></i> Employee <i class="fa fa-chevron-down float-end"></i>
        </a>
        <div id="employeeSubmenu" class="collapse">
            <a href="employee.php" class="submenu-item"><i class="fa fa-users"></i> Employees</a>
            <a href="employee_data.php" class="submenu-item"><i class="fa fa-file-alt"></i> Employee Data</a>
        </div>
        
        <!-- Leave Management with submenu -->
        <a href="#" class="submenu-toggle" data-bs-toggle="collapse" data-bs-target="#leaveManagementSubmenu">
            <i class="fa fa-calendar-check"></i> Leave Management <i class="fa fa-chevron-down float-end"></i>
        </a>
        <div id="leaveManagementSubmenu" class="collapse">
            <a href="leave.php" class="submenu-item"><i class="fa fa-calendar-day"></i> Leave</a>
            <a href="leave_requests.php" class="submenu-item"><i class="fa fa-inbox"></i> Leave Requests</a>
            <a href="leave_reports.php" class="submenu-item"><i class="fa fa-file-alt"></i> Leave Reports</a>
        </div>
        
        <!-- Attendance -->
        <a href="attendance.php"><i class="fa fa-clock"></i> Attendance</a>
        
        <!-- Payroll -->
        <a href="payroll.php"><i class="fa fa-credit-card"></i> Payroll</a>

        <!-- Recruitment with submenu -->
        <a href="#" class="submenu-toggle" data-bs-toggle="collapse" data-bs-target="#recruitmentSubmenu">
            <i class="fa fa-briefcase"></i> Recruitment <i class="fa fa-chevron-down float-end"></i>
        </a>
        <div id="recruitmentSubmenu" class="collapse">
            <a href="job_posting.php" class="submenu-item"><i class="fa fa-file-signature"></i> Job Postings</a>
            <a href="applications.php" class="submenu-item"><i class="fa fa-user"></i> Applications</a>
            <a href="candidates_eval.php" class="submenu-item"><i class="fa fa-check-circle"></i> Candidate Evaluations</a>
            <a href="reject_history.php" class="submenu-item"><i class="fa-solid fa-x"></i> Reject History</a>
        </div>

        <!-- Appraisal -->
        <a href="appraisal.php"><i class="fa fa-chart-line"></i> Appraisal</a>

        <!-- Add User -->
        <a href="../login/add_user.php"><i class="fa fa-user"></i> Add User</a>

        <!-- Logout -->
        <a href="../login/logout.php" class="submenu-item"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Toggle Button -->
    <button class="navbar-toggler" type="button" aria-expanded="false" aria-label="Toggle navigation" onclick="toggleSidebar()">
        <span class="navbar-toggler-icon"><i class="fa fa-bars"></i></span>
    </button>

    <div class="content">
        <!-- Your content here -->
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script>
        // Toggle sidebar visibility for mobile
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
        }

        // Add toggle functionality for submenus
        document.querySelectorAll('.submenu-toggle').forEach(toggle => {
            toggle.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('data-bs-target'));
                target.classList.toggle('show'); // Toggle the clicked submenu
            });
        });
    </script>
</body>
</html>
