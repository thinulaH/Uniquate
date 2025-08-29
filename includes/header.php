<?php
// includes/header.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Hall Booking System</title>
    <style>

        html, body {
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #fffcd7ff 0%, #8e7295ff 100%);
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        :root {
            --cream: #F0ECE3;
            --light-tan: #DFD3C3;
            --tan: #C7B198;
            --purple: #A68DAD;
            --dark-gray: #2c2c2c;
            --light-gray: #f8f9fa;
        }

        /* Removed duplicate body definition to ensure consistent gradient background for all pages */

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 90%;
            margin: 0 auto;
            display: flex;
            justify-content: space-between; /* keeps logo left, nav-links right */
            align-items: center;
            padding: 0; /* remove left/right padding */
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--purple);
            text-decoration: none;
            margin: 0; /* remove any margin */
            padding: 0; /* remove any padding */
        }

        .logo img {
            height: 100%;
            width: auto;
            display: block;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--dark-gray);
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: black;
            font-weight: bold;
            font-weight: 550;
            transition: all 0.3s ease;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-block;
            text-align: center;
        }

        .btn-primary, .btn-secondary {
            transition: all 0.3s ease-in-out;
        }

        .btn-primary {
            background: var(--purple);
            color: white;
            margin-bottom: 5px;
        }

        .btn-primary:hover {
            background: #9580a1;
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 20px rgba(166, 141, 173, 0.4);
        }

        .btn-secondary {
            background: var(--tan);
            color: white;
        }

        .btn-secondary:hover {
            background: #b8a485;
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 20px rgba(194, 197, 171, 0.4);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .hero {
            padding: 4rem 0;
            text-align: center;
            margin-bottom: 0;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--dark-gray);
        }

        .hero p {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .search-box {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            max-width: 900px;
            margin: 2rem auto;
        }

        .search-form {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 1rem;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark-gray);
        }

        .form-group input, .form-group select {
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: var(--purple);
        }

        .halls-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }

        .hall-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hall-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .hall-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(45deg, var(--tan), var(--purple));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .hall-info {
            padding: 1.5rem;
        }

        .hall-name {
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: var(--dark-gray);
        }

        .hall-location {
            color: #666;
            margin-bottom: 1rem;
        }

        .hall-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: #666;
        }

        .hall-price {
            font-size: 1.1rem;
            font-weight: bold;
            color: var(--purple);
            margin-bottom: 1rem;
        }

        .form-container {
            max-width: 500px;
            margin: 2rem auto;
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 2rem;
            color: var(--dark-gray);
        }

        .form-container .form-group {
            margin-bottom: 1rem;
        }

        .alert {
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 10px;
            font-weight: 500;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .bookings-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin: 2rem 0;
        }

        .bookings-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .bookings-table th,
        .bookings-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e1e5e9;
        }

        .bookings-table th {
            background: var(--light-tan);
            font-weight: 600;
            color: var(--dark-gray);
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .footer {
            background: var(--dark-gray);
            color: white;
            text-align: center;
            padding: 2rem 0;
            margin-top: 4rem;
        }

        @media (max-width: 100%) {
            .search-form {
                grid-template-columns: 1fr;
            }
            
            .hero h1 {
                font-size: 2rem;
            }
            
            .nav-container {
                flex-direction: column;
                gap: 1rem;
            }
            
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }
        }

        details {
            background: #fdfdfd;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 10px;
            cursor: pointer;
        }

        summary {
            font-weight: bold;
            color: #444;
        }

        details[open] summary {
            color: #0073e6;
        }
        
    </style>
</head>
<body>
    <nav class="navbar"> 
        <div class="nav-container">
            <a href="index.php" class="logo">
                <img src="images/logo.png" alt="Uniquate Logo" style="height: 50px;">
            </a>
            <div class="nav-links">
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                    <?php else: ?>
                        <a href="index.php">Home</a>
                        <a href="halls.php">Browse Halls</a>
                        <a href="my_bookings.php">My Bookings</a>
                    <?php endif; ?>
                    <a href="logout.php" class="btn btn-secondary">
                        Logout (<?= htmlspecialchars($_SESSION['username']) ?>)
                    </a>
                <?php else: ?>
                    <a href="index.php">Home</a>
                    <a href="halls.php">Browse Halls</a>
                    <a href="login.php" class="btn btn-primary">Sign In</a>
                    <a href="register.php" class="btn btn-secondary">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
</body>
</html>
