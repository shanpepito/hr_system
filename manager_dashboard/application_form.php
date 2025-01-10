<?php 
session_start();
require_once '../config/config.php';

// Fetch job titles from the database
try {
    $stmt = $conn->query("SELECT JobID, JobTitle FROM Jobposting WHERE JobStatus = 'Open'");
    $jobTitles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


// Fetch questions based on the selected job title
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jobTitle'])) {
    $jobTitle = $_POST['jobTitle'];

    // Fetch questions based on the selected Job Title
    function fetchQuestions($jobTitle) {
        global $conn;
        $stmt = $conn->prepare("
            SELECT pq.QuestionID, pq.QuestionText, pq.AnswerType, pq.Options 
            FROM PositionQuestions pq
            JOIN Jobposting j ON pq.JobID = j.JobID
            WHERE j.JobTitle = :jobTitle
        ");
        $stmt->execute([':jobTitle' => $jobTitle]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $questions = fetchQuestions($jobTitle);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Job Application Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header text-center bg-primary text-white">
            <h3>Job Application Form</h3>
        </div>
        <form method="POST" enctype="multipart/form-data" class="card-body">
            <div class="row">
                <!-- Left Side: Personal Information -->
                <div class="col-md-6 border-end">
                    <h5 class="mb-4">Personal Information</h5>

                     <!-- Position Applying For -->
                     <div class="mb-3">
                        <label for="jobTitleSelect" class="form-label">Position Applying For</label>
                        <select id="jobTitleSelect" name="jobTitle" class="form-control" onchange="this.form.submit()" required>
                            <option value="">Select a Job Title</option>
                            <?php foreach ($jobTitles as $title): ?>
                                <option value="<?= $title['JobTitle'] ?>" <?= isset($jobTitle) && $jobTitle == $title['JobTitle'] ? 'selected' : '' ?>>
                                    <?= $title['JobTitle'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- Full Name -->
                    <div class="mb-3">
                        <label for="fullName" class="form-label">Full Name</label>
                        <input type="text" id="fullName" name="fullName" class="form-control" required>
                    </div>
                    <!-- Email Address -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <!-- Phone Number -->
                    <div class="mb-3">
                        <label for="phoneNumber" class="form-label">Phone Number</label>
                        <input type="text" id="phoneNumber" name="phoneNumber" class="form-control" required>
                    </div>

                      <!-- Resume Upload -->
                    <div class="mb-3">
                        <label for="resume" class="form-label">Resume/CV Upload</label>
                        <input type="file" id="resume" name="resume" class="form-control" required>
                    </div>
                
                </div>


                     <!-- Right Side: Screening Questions -->   
                <div class="col-md-6">
                    <h5 class="mb-4">Screening Questions</h5>
                    <?php if (isset($questions)): ?>
                        <?php foreach ($questions as $index => $question): ?>
                            <div class="mb-3">
                                <label><?= $index + 1 ?>. <?= $question['QuestionText'] ?></label>
                                <?php if ($question['AnswerType'] == 'select'): ?>
                                    <select name="q<?= $index + 1 ?>" class="form-control" required>
                                        <option value="" disabled selected>Select an answer</option>
                                        <?php 
                                            $options = explode(',', $question['Options']);  // Split the comma-separated options
                                            foreach ($options as $key => $option): 
                                        ?>
                                            <option value="<?= chr(97 + $key) ?>"><?= trim($option) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php elseif ($question['AnswerType'] == 'text'): ?>
                                    <textarea name="q<?= $index + 1 ?>" class="form-control" rows="3" required></textarea>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Submit Application</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
                                