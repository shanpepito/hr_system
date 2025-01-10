<?php
session_start();
require_once '../config/config.php';

try {
    $stmt = $conn->query("
        SELECT 
            jp.JobID, 
            p.Title AS PositionTitle, 
            d.Name AS Department, 
            jp.JobDesc, 
            jp.JobQual, 
            jp.JobStatus 
        FROM Jobposting jp
        JOIN Department d ON jp.DepartmentID = d.DepartmentID
        JOIN Position p ON jp.PositionID = p.PositionID
        WHERE jp.JobStatus = 'Open';
    ");
    $jobDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "SQL Error: " . $e->getMessage();
    $jobDetails = [];
}
?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobFinder - Job Lists</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 15px;
            text-align: center;
        }

        header h1 {
            margin: 0;
        }

        .nav-links {
            list-style: none;
            padding: 0;
            text-align: center;
            margin-top: 10px;
        }

        .nav-links li {
            display: inline;
            margin-right: 20px;
        }

        .nav-links li a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
        }

        .nav-links li a:hover,
        .dropdown-menu li a:hover {
            text-decoration: underline;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-toggle {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            background-color: #fff;
            color: #333;
            list-style: none;
            padding: 10px;
            margin-top: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .dropdown-menu li {
            padding: 10px;
        }

        .dropdown-menu.show {
            display: block;
        }

        main {
            padding: 30px;
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
        }

        .hero {
            text-align: center;
            margin-bottom: 30px;
        }

        .hero h2 {
            font-size: 32px;
            color: #fffff;
        }

        .job-list {
            margin: 0;
        }

        .job-list h3 {
            font-size: 28px;
            color: #333;
            margin-bottom: 50px;
        }

        .job-item {
            background-color: #f9f9f9;
            padding: 20px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            gap: 6px;
        }

        .job-item h2 {
            color: #2c3e50;
            font-size: 24px;
        }

        .job-item p {
            font-size: 16px;
            color: #555;
        }

        .job-item button {
            padding: 10px 20px;
            background-color: #568665;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
        }

        .job-item button:hover {
            background-color: #7fa484;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 15px;
            position: relative;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <header>
        <h1>JobFinder</h1>
        <ul class="nav-links">
            <li><a href="homeweb.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="jobs.php" class="active">Job Lists</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
        <div class="dropdown">
            <button class="dropdown-toggle">☰</button>
            <ul class="dropdown-menu">
                <li><a href="#">Settings</a></li>
                <li><a href="#">Sign Up</a></li>
            </ul>
        </div>
    </header>

    <main>
    <h2 class="text-center">Available Jobs</h2>
        <div class="job-list">
            <?php foreach ($jobDetails as $job): ?>
                <div class="job-item">
                    <h5><?= htmlspecialchars($job['PositionTitle']) ?></h5>
                    <p><strong>Department:</strong> <?= htmlspecialchars($job['Department']) ?></p>
                    <p><strong>Description:</strong> <?= htmlspecialchars($job['JobDesc']) ?></p>
                    <p><strong>Qualification:</strong> <?= htmlspecialchars($job['JobQual']) ?></p>
                    <p><strong>Status:</strong> <?= htmlspecialchars($job['JobStatus']) ?></p>
                    <a href="../admin_dashboard/application_form.php?JobID=<?= htmlspecialchars($job['JobID']) ?>" class="btn">Apply Now</a>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 JobFinder. All rights reserved.</p>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const dropdownToggle = document.querySelector(".dropdown-toggle");
            const dropdownMenu = document.querySelector(".dropdown-menu");

            dropdownToggle.addEventListener("click", function (e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle("show");
            });

            document.addEventListener("click", function () {
                dropdownMenu.classList.remove("show");
            });
        });
    </script>
</body>
</html>
