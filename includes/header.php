<?php
// includes/header.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="images/favicon.png">
    <title>University Hall Booking System</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <nav class="navbar"> 
        <div class="nav-container">
            <a href="index.php" class="logo">
                <img src="images/logo1.png" alt="Uniquate Logo" style="height: 50px;">
            </a>
            <div class="nav-links">
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <a href="admin.php">Dashboard</a>
                        <a href="admin_reports.php">Reports</a>
                    <?php else: ?>
                        <a href="index.php">Home</a>
                        <a href="halls.php">Browse Halls</a>
                        <a href="my_bookings.php">My Bookings</a>
                        <a href="functionalities.php">Functionalities</a>
                        
                    <?php endif; ?>
                    <a href="logout.php" class="btn btn-secondary">
                        Logout (<?= htmlspecialchars($_SESSION['username']) ?>)
                    </a>
                <?php else: ?>
                    <a href="index.php">Home</a>
                    <a href="halls.php">Browse Halls</a>
                    <a href="functionalities.php">Functionalities</a>
                    <a href="login.php" class="btn btn-primary">Sign In</a>
                    <a href="register.php" class="btn btn-secondary">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
</body>
</html>
