<?php 
session_start(); 
if(isset($_SESSION['logged_in'])) { 
    header("Location: admin.php"); 
    exit(); 
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corporation Search System - Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <img src="https://placehold.co/400x120?text=Corporation+Search+System+Admin+Portal" 
             alt="Corporation Search System Admin Portal with professional business interface design" 
             onerror="this.style.display='none'" />
        <h2>Admin Login</h2>
        <?php if(isset($_GET['error'])): ?>
            <div class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        <form action="process_login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="login-info">
            <p><strong>Default Login:</strong></p>
            <p>Username: admin</p>
            <p>Password: admin123</p>
        </div>
    </div>
</body>
</html>
