<?php
// logout.php
include_once 'auth/session.php';

// Clear all session data
$_SESSION = [];
session_destroy();

// Redirect to login page
header('Location: login.php');
exit();
?>