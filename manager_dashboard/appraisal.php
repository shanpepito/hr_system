<?php
session_start();
require_once '../config/config.php'; // Adjust the path if necessary
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Evaluation</title>
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

        .container {
            max-width: 900px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            text-align: center;
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .rating-container {
            display: flex;
            justify-content: space-between;
        }

        .rating-label {
            font-size: 1.1em;
            margin-bottom: 10px;
        }

        .rating-stars input[type="radio"] {
            display: none;
        }

        .rating-stars label {
            font-size: 2em;
            color: #ccc;
            cursor: pointer;
        }

        .rating-stars input:checked ~ label {
            color: gold;
        }

        .rating-stars label:hover,
        .rating-stars label:hover ~ label {
            color: gold;
        }

        .comment-section {
            margin-top: 20px;
        }

        .comment-section textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            font-size: 1em;
            border-radius: 5px;
            border: 1px solid #ccc;
            resize: none;
        }

        .btn-submit {
            margin-top: 30px;
            width: 100%;
        }

        .performance-rating {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .performance-rating label {
            font-size: 1.2em;
        }
    </style>
</head>
<body>
<?php include(__DIR__ . '/include/sidebar.php'); ?>

<div class="content">
<?php include(__DIR__ . '/include/header.php'); ?>

<div class="main-content">
    <div class="form-title">Performance Evaluation Form</div>
    
    <form action="submit_evaluation.php" method="POST">
        <!-- EmployeeID Field -->
        <div class="mb-3">
            <label for="employeeid" class="form-label">Employee ID</label>
            <input type="number" class="form-control" id="employeeid" name="employeeid" required>
        </div>
        
        <!-- Evaluation Criteria -->
        <div class="rating-container">
            <div class="col-5">
                <label class="rating-label">Quality of Work</label>
                <div class="rating-stars">
                    <input type="radio" id="q1" name="quality_of_work" value="1"><label for="q1">★</label>
                    <input type="radio" id="q2" name="quality_of_work" value="2"><label for="q2">★</label>
                    <input type="radio" id="q3" name="quality_of_work" value="3"><label for="q3">★</label>
                    <input type="radio" id="q4" name="quality_of_work" value="4"><label for="q4">★</label>
                    <input type="radio" id="q5" name="quality_of_work" value="5"><label for="q5">★</label>
                </div>
            </div>

            <div class="col-5">
                <label class="rating-label">Communication Skills</label>
                <div class="rating-stars">
                    <input type="radio" id="c1" name="communication_skills" value="1"><label for="c1">★</label>
                    <input type="radio" id="c2" name="communication_skills" value="2"><label for="c2">★</label>
                    <input type="radio" id="c3" name="communication_skills" value="3"><label for="c3">★</label>
                    <input type="radio" id="c4" name="communication_skills" value="4"><label for="c4">★</label>
                    <input type="radio" id="c5" name="communication_skills" value="5"><label for="c5">★</label>
                </div>
            </div>
        </div>

        <div class="rating-container">
            <div class="col-5">
                <label class="rating-label">Teamwork</label>
                <div class="rating-stars">
                    <input type="radio" id="t1" name="teamwork" value="1"><label for="t1">★</label>
                    <input type="radio" id="t2" name="teamwork" value="2"><label for="t2">★</label>
                    <input type="radio" id="t3" name="teamwork" value="3"><label for="t3">★</label>
                    <input type="radio" id="t4" name="teamwork" value="4"><label for="t4">★</label>
                    <input type="radio" id="t5" name="teamwork" value="5"><label for="t5">★</label>
                </div>
            </div>

            <div class="col-5">
                <label class="rating-label">Punctuality</label>
                <div class="rating-stars">
                    <input type="radio" id="p1" name="punctuality" value="1"><label for="p1">★</label>
                    <input type="radio" id="p2" name="punctuality" value="2"><label for="p2">★</label>
                    <input type="radio" id="p3" name="punctuality" value="3"><label for="p3">★</label>
                    <input type="radio" id="p4" name="punctuality" value="4"><label for="p4">★</label>
                    <input type="radio" id="p5" name="punctuality" value="5"><label for="p5">★</label>
                </div>
            </div>
        </div>

        <!-- Performance Rating (Text) -->
        <div class="performance-rating">
            <div class="col-5">
                <label class="rating-label">Overall Performance Rating</label>
                <select class="form-select" name="performance_rating" required>
                    <option value="Excellent">Excellent</option>
                    <option value="Very Good">Very Good</option>
                    <option value="Good">Good</option>
                    <option value="Average">Average</option>
                    <option value="Below Average">Below Average</option>
                </select>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="comment-section">
            <label for="comments">Additional Comments</label>
            <textarea id="comments" name="comments" placeholder="Provide any additional feedback here..."></textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary btn-submit">Submit Evaluation</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
