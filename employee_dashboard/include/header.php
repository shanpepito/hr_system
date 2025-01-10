<?php
include('../config/config.php');
?>

<!-- header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    
<style>
    .header {
        height: 70px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        background: rgb(29,41,57);
        box-shadow: 0px 2px 15px rgba(0, 0, 0, 0.1);
        padding: 0 20px;
        margin-bottom: 20px;
    }

    .user-info {
        color: #fff;
    }
</style>

<div class="header">
    <div class="user-info">
        <span>Welcome, <strong><?php echo htmlspecialchars($_SESSION['Username']); ?></strong></span>
    </div>
</div>

<!-- Bootstrap JS and Popper.js for the collapse functionality -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>