<?php
session_start();

// Log the logout
if (isset($_SESSION['admin'])) {
    error_log("Admin logout: " . $_SESSION['admin'] . " at " . date('Y-m-d H:i:s'));
}

// Destroy all session data
session_unset();
session_destroy();

// Set success message for login page
session_start();
$_SESSION['success'] = "You have been successfully logged out.";

// Redirect to login page
header("Location: login.php");
exit;
?>
