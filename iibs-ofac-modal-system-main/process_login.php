<?php
session_start();
require_once 'functions.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

// Get and sanitize input
$username = sanitize_input($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Validate input
if (empty($username) || empty($password)) {
    $_SESSION['error'] = "Please fill in all fields.";
    header("Location: login.php");
    exit;
}

// Verify credentials
$admin = verifyAdmin($username, $password);

if ($admin) {
    // Login successful
    $_SESSION['admin'] = $admin['username'];
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['login_time'] = time();
    
    // Log successful login
    error_log("Admin login successful: " . $username . " at " . date('Y-m-d H:i:s'));
    
    header("Location: admin_dashboard.php");
    exit;
} else {
    // Login failed
    $_SESSION['error'] = "Invalid username or password.";
    
    // Log failed login attempt
    error_log("Failed login attempt for username: " . $username . " at " . date('Y-m-d H:i:s'));
    
    header("Location: login.php");
    exit;
}
?>
