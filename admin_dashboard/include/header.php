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
        justify-content: space-between;
        align-items: center;
        background: rgb(29,41,57);
        box-shadow: 0px 2px 15px rgba(0, 0, 0, 0.1);
        padding: 0 20px;
        margin-bottom: 20px;
    }

    .center-container {
        flex: 1;
        display: flex;
        justify-content: center;
    }

    .search-bar {
        width: 50%;
        max-width: 600px;
    }

    .tools {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #fff;
        border-radius: 50px;
    }
    .tools:hover{
        background: rgb(29,41,57);
        background: linear-gradient(90deg, rgba(29,41,57,1) 0%, rgba(2,113,141,1) 35%, rgba(84,175,161,1) 100%);
    }

    .notification-btn {
        font-size: 1.5rem;
        background: none;
        border: none;
        cursor: pointer;
    }
    .user-info{
        color: #fff;
    }
</style>

<div class="header">
    
    <div class="user-info">
        <span>Welcome, <b>Admin</b></span>
    </div>

<!--     
    <div class="center-container">
        <input type="text" placeholder="Search..." class="form-control search-bar">
    </div>

    
    <div class="tools">
        <button class="notification-btn">🔔</button>
    </div> -->
</div>

<!-- Bootstrap JS and Popper.js for the collapse functionality -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>