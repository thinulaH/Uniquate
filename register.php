<?php

// register.php
include_once 'config/database.php';
include_once 'classes/User.php';
include_once 'auth/session.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$message = '';
$error = '';

if ($_POST) {
    $database = new Database();
    $db = $database->getConnection();
    $user = new User($db);
    
    $user->username = $_POST['username'];
    $user->password = $_POST['password'];
    $user->email = $_POST['email'];
    $user->role = 'user';
    
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $error = 'Passwords do not match';
    } elseif ($user->create()) {
        $message = 'Registration successful! You can now login.';
    } else {
        $error = 'Registration failed. Username or email might already exist.';
    }
}

include_once 'includes/header.php';
?>

<div class="container">
    <div class="form-container">
        <h2>Create Your Account</h2>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">Sign Up</button>
        </form>
        
        <p style="text-align: center; margin-top: 1rem;">
            Already have an account? <a href="login.php" style="color: var(--purple);">Sign in here</a>
        </p>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>
