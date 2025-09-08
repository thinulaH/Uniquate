<?php
// login.php
include_once 'config/database.php';
include_once 'classes/User.php';
include_once 'auth/session.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_POST) {
    $database = new Database();
    $db = $database->getConnection();
    $user = new User($db);
    
    if ($user->login($_POST['username'], $_POST['password'])) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['role'] = $user->role;
        header('Location: index.php');
        exit();
    } else {
        $error = 'Invalid username or password';
    }
}

include_once 'includes/header.php';
?>

<div class="container">
    <div class="form-container">
        <h2>Sign In to Your Account</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">Sign In</button>
        </form>
        
        <p style="text-align: center; margin-top: 1rem;">
            Don't have an account? <a href="register.php" style="color: var(--purple);">Sign up here</a>
        </p>
        
        <!--<div style="margin-top: 2rem; padding: 1rem; background: var(--light-gray); border-radius: 10px;">
            <h4>Default Test Account:</h4>
            <p><strong>Username:</strong> uoc</p>
            <p><strong>Password:</strong> uoc</p>
        </div>-->
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>