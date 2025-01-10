<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - JobFinder</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .sectionh2{
            margin-bottom: 40px;
            padding: 20px;
            background-color: #91b89d;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .section {
            margin-bottom: 40px;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .section h2, .section h3 {
            color: #333;
            text-align: center;
        }

        .section p {
            text-align: center;
            color: #555;
            line-height: 1.6;
        }

        .features {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .feature {
            flex: 1 1 calc(30% - 20px);
            padding: 20px;
            background: #91b89d;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .feature h4 {
            color: #3498db;
            margin-bottom: 10px;
        }

        .feature p {
            color: #555;
        }

        @media (max-width: 768px) {
            .feature {
                flex: 1 1 100%;
            }
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
    <header>
        <h1>JobFinder</h1>
        <ul class="nav-links">
            <li><a href="homeweb.php">Home</a></li>
            <li><a href="about.php" class="active">About</a></li>
            <li><a href="jobs.php">Job Lists</a></li>
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
    <div class="container">
        <div class="sectionh2">
            <h2>About JobFinder</h2>
            <p>Our mission is to connect talented individuals with their dream jobs, bridging the gap between employers and job seekers.</p>
            <p>We envision a world where everyone finds meaningful work that aligns with their passion and skills.</p>
        </div>
        <div class="section">
            <h3>Key Features</h3>
            <div class="features">
                <div class="feature">
                    <h4>User-Friendly Interface</h4>
                    <p>Designed for ease of use, our platform ensures job seekers and employers navigate effortlessly.</p>
                </div>
                <div class="feature">
                    <h4>Smart Job Matching</h4>
                    <p>Our advanced algorithms match candidates with jobs that fit their skills and interests.</p>
                </div>
                <div class="feature">
                    <h4>Wide Range of Opportunities</h4>
                    <p>From entry-level to executive roles, explore thousands of job postings across various industries.</p>
                </div>
                <div class="feature">
                    <h4>Employer Tools</h4>
                    <p>Employers can post jobs, track applications, and find the perfect candidate efficiently.</p>
                </div>
                <div class="feature">
                    <h4>24/7 Accessibility</h4>
                    <p>Access our services anytime, anywhere, on any device.</p>
                </div>
            </div>
        </div>
    </div>
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
