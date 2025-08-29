<?php
// Database config
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "root";
$DB_NAME = "hall_room_booking";

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, 8889);
if($conn->connect_error){ die("Database connection failed: ".$conn->connect_error); }
$conn->set_charset("utf8mb4");

session_start();

// CSRF
if(empty($_SESSION['csrf'])){
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
function csrf_field(){
    $t = $_SESSION['csrf'] ?? '';
    echo '<input type="hidden" name="csrf" value="'.htmlspecialchars($t).'">';
}
function check_csrf(){
    if(($_POST['csrf'] ?? '') !== ($_SESSION['csrf'] ?? '')){
        die("Invalid CSRF token");
    }
}

// Helpers
function is_logged_in(){ return !empty($_SESSION['user']); }
function require_login(){
    if(!is_logged_in()){ header("Location: index.php?msg=Please+login"); exit; }
}
function current_user(){ return $_SESSION['user'] ?? null; }
function is_admin(){ return (current_user()['role'] ?? '') === 'admin'; }
function require_admin(){
    if(!is_admin()){ http_response_code(403); die("Forbidden: Admins only."); }
}
function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES,'UTF-8'); }
?>