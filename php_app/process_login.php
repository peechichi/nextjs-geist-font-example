<?php
session_start();
require_once 'config.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username === ADMIN_USER && $password === ADMIN_PASS) {
    $_SESSION['logged_in'] = true;
    $_SESSION['username'] = $username;
    header("Location: admin.php");
} else {
    header("Location: index.php?error=" . urlencode("Invalid username or password."));
}
exit();
?>
