<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appraisal</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #fff;
            border-bottom: none;
        }
        .card-body {
            padding: 20px;
        }
        .nav-tabs .nav-link.active {
            background-color: #007bff;
            color: white;
            border-radius: 5px;
        }
        .form-control:disabled, .form-control[readonly] {
            background-color: #e9ecef;
        }
        .rating-buttons {
            display: flex;
            justify-content: space-between;
        }
        .rating-buttons button {
            width: 18%;
            padding: 10px;
            font-size: 1rem;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .rating-buttons button.active {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>

    <!-- Include Sidebar -->
    <?php include(__DIR__ . '/include/sidebar.php'); ?>

    <div class="content">
        <!-- Include Header -->
        <?php include(__DIR__ . '/include/header.php'); ?>

        <div class="main-content">
            <h3>Employee Appraisal</h3>

            <form action="submit_appraisal.php" method="POST">
                <!-- Employee Details -->
                <div class="mb-3">
                    <label for="employeeName" class="form-label">Employee Name</label>
                    <input type="text" class="form-control" id="employeeName" name="employeeName" required>
                </div>
                <div class="mb-3">
                    <label for="appraisalDate" class="form-label">Appraisal Date</label>
                    <input type="date" class="form-control" id="appraisalDate" name="appraisalDate" required>
                </div>
                 <!-- Employee Rating -->
                <div class="mb-3">
                    <label for="employeeRating" class="form-label">Rate Your Manager</label>
                    <div class="rating-buttons">
                        <button type="button" class="btn btn-outline-danger" data-value="Poor">Poor</button>
                        <button type="button" class="btn btn-outline-warning" data-value="Average">Average</button>
                        <button type="button" class="btn btn-outline-primary" data-value="Good">Good</button>
                        <button type="button" class="btn btn-outline-success" data-value="Very Good">Very Good</button>
                        <button type="button" class="btn btn-outline-dark" data-value="Excellent">Excellent</button>
                    </div>
                    <input type="hidden" id="employeeRating" name="employeeRating" required>
                </div>

                <!-- Tabs Section -->
                <ul class="nav nav-tabs mt-4" id="appraisalTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="appraisal-tab" data-bs-toggle="tab" data-bs-target="#appraisal" type="button" role="tab">Appraisal</button>
                    </li>
                </ul>

                <div class="tab-content mt-3">
                    <!-- Appraisal Tab -->
                    <div class="tab-pane fade show active" id="appraisal" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Employee's Feedback</h6>
                                <div class="mb-3">
                                    <label for="employeeWork" class="form-label">My Work</label>
                                    <textarea class="form-control" id="employeeWork" name="employeeWork" rows="3" placeholder="What are my best achievements since my last appraisal?" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="employeeChallenges" class="form-label">Challenges</label>
                                    <textarea class="form-control" id="employeeChallenges" name="employeeChallenges" rows="3" placeholder="What has been the most challenging aspect of my work this past year and why?" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="employeeImprovements" class="form-label">Improvements</label>
                                    <textarea class="form-control" id="employeeImprovements" name="employeeImprovements" rows="3" placeholder="What would I need to improve my work?" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-4">Submit</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.rating-buttons button').forEach(button => {
            button.addEventListener('click', function () {
                document.querySelectorAll('.rating-buttons button').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                document.getElementById('employeeRating').value = this.getAttribute('data-value');
            });
        });
    </script>
</body>
</html>
