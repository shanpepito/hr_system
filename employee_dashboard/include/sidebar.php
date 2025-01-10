<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Panel</title>
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
            transition: left 0.3s ease;
            z-index: 9999;
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
        }
        .content {
            margin-left: 270px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }
        .text-center {
            color: #fff;
        }
        .header {
            height: 70px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            box-shadow: 0px 2px 15px rgba(0, 0, 0, 0.1);
            padding: 0 20px;
            margin-bottom: 20px;
        }
        .card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Sidebar Toggle Button */
        .sidebar-toggle-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            background: #1d2939;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            cursor: pointer;
            z-index: 1001;
        }

        .sidebar-toggle-btn:focus {
            outline: none;
        }

        /* For mobile devices: hide sidebar by default */
        @media (max-width: 768px) {
            .sidebar {
                left: -250px;
            }

            .sidebar.active {
                left: 0;
            }

            .content {
                margin-left: 0; /* Remove content margin when sidebar is hidden on mobile */
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar Toggle Button -->
    <button class="sidebar-toggle-btn">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center">Employee Panel</h4>
        <a href="dashboard.php"><i class="fa fa-tachometer-alt"></i> Dashboard</a>
        <a href="myprofile.php"><i class="fa fa-user"></i> My Profile</a>
        <a href="leave_history.php"><i class="fa fa-calendar-check"></i> Leave History</a>
        <a href="time-book.php"><i class="fa fa-clock"></i> Time-Book</a>
        <a href="view_appraisal.php"><i class="fa fa-chart-line"></i> Appraisal</a>
        <a href="../login/logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Bootstrap JS and Popper.js for the collapse functionality -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
        // Sidebar toggle for mobile
        document.querySelector('.sidebar-toggle-btn').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.content').classList.toggle('shifted'); // Apply the shift effect to content
        });
    </script>
</body>
</html>
