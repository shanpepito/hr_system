<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    // Email to send the message to
    $to = "your-email@example.com"; // Replace with your email
    $subject = "New Message from $name";
    $body = "Name: $name\nEmail: $email\nPhone: $phone\nMessage: $message";
    $headers = "From: $email";

    // Send the email
    if (mail($to, $subject, $body, $headers)) {
        echo "<script>alert('Message sent successfully!');</script>";
    } else {
        echo "<script>alert('Failed to send message. Please try again later.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobFinder - Contact</title>

    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kalnia:wght@600&family=Kanit:ital,wght@1,500;1,700&family=Poppins:wght@200;300;400;500&display=swap" rel="stylesheet">
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
            color: #333;
        }

        .job-list {
            margin: 0;
        }

        .job-list h3 {
            font-size: 28px;
            color: #333;
            margin-bottom: 15px;
        }

        .job-item {
            background-color: #f9f9f9;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
            background-color: #3498db;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
        }

        .job-item button:hover {
            background-color: #2980b9;
        }

 footer {
        background-color: #91b89d; /* Footer background color */
        color: #fff; /* Text color for contrast */
        text-align: center; /* Center align the content */
        padding: 15px; /* Space around the content */
        position: relative;
    }

    footer .last-text p {
        margin: 0; /* Remove extra margin for consistent spacing */
    }

    footer .top a i {
        color: #fff; /* White arrow for visibility */
        font-size: 1.5rem; /* Increase size for prominence */
        transition: transform 0.3s, color 0.3s;
    }

    footer .top a i:hover {
        transform: scale(1.2); /* Enlarge icon slightly on hover */
        color: #f2f8f4; /* Lightened hover color */
    }
/* Updated Styling for #91b89d Color Palette */
.contact {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 2rem;
    background: #d9e9dc;
    gap: 2rem;
}

.contact-text {
    flex: 1;
    animation: fadeIn 1.5s ease;
}

.contact-text h2 span {
    color: #91b89d; /* Accent color */
}

.contact-text p {
    color: #607d68; /* Soft text color */
}

.animated-icons i {
    font-size: 2rem;
    margin: 0 0.5rem;
    transition: transform 0.3s, color 0.3s;
    color: #91b89d;
}

.animated-icons i:hover {
    transform: scale(1.3);
    color: black;
}

.contact-form {
    flex: 1;
    padding: 1.5rem;
    background: #f2f8f4; /* Light green background */
    border-radius: 8px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.contact-form form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.contact-form input, .contact-form textarea {
    padding: 0.8rem;
    border: 1px solid #bdd4c6; /* Light green border */
    border-radius: 5px;
    transition: border-color 0.3s;
}

.contact-form input:focus, .contact-form textarea:focus {
    border-color: #91b89d;
}

.contact-form .submit {
    background: #91b89d;
    color: #fff;
    border: none;
    cursor: pointer;
    transition: background 0.3s;
}

.contact-form .submit:hover {
    background: #75987d; /* Darker green for hover effect */
}
.end{
    background-color: black;
}

.end .last-text p {
    color: white; /* Footer text color */
    font-weight: bold;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <h1>JobFinder</h1>
        <ul class="nav-links">
            <li><a href="homeweb.php">Home</a></li> 
            <li><a href="about.php">About</a></li> 
            <li><a href="jobs.php">Job Lists</a></li> 
            <li><a href="contact.php" class="active">Contact</a></li>
        </ul>
        <div class="dropdown">
            <button class="dropdown-toggle">☰</button>
            <ul class="dropdown-menu">
                <li><a href="#">Settings</a></li>
                <li><a href="#">Sign Up</a></li>
            </ul>
        </div>
    </header>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="contact-text">
            <h2>Get In <span>Touch</span></h2>
            <p>If you have any questions, need assistance, or are interested in exploring job opportunities, feel free to reach out. I'm here to help you on your career journey.</p>

            <div class="animated-icons">
                <a href="https://facebook.com/ariadne.jayne.1"><i class='bx bxl-facebook'></i></a>
                <a href="https://twitter.com/jayneeeea"><i class='bx bxl-twitter'></i></a>
                <a href="https://instagram.com/jayneeeea"><i class='bx bxl-instagram-alt'></i></a>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="contact-form">
            <form action="contact.php" method="POST">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email Address" required>
                <input type="text" name="phone" placeholder="Your Mobile Number" required>
                <textarea name="message" cols="35" rows="10" placeholder="Your Message" required></textarea>
                <input type="submit" value="Send Message" class="submit">
            </form>
        </div>
    </section>

    <!-- Footer -->
    <section class="end">
        <div class="last-text">
            <p>© 2024. JobFinder</p>
        </div>
        
    </section>

    <!-- Custom JS -->
    <script>
        // Sticky Navbar
        const header = document.querySelector("header");
        window.addEventListener("scroll", function() {
            header.classList.toggle("sticky", window.scrollY > 100);
        });

        // Dropdown Menu
        const dropdownToggle = document.querySelector(".dropdown-toggle");
        const dropdownMenu = document.querySelector(".dropdown-menu");

        dropdownToggle.addEventListener("click", function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle("show");
        });

        document.addEventListener("click", function() {
            dropdownMenu.classList.remove("show");
        });
    </script>
</body>
</html>
