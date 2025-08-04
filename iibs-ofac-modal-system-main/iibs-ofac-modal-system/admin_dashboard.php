<?php
session_start();
require_once 'functions.php';

// Check if user is logged in
requireLogin();

// Get admin info
$admin_username = $_SESSION['admin'];
$login_time = isset($_SESSION['login_time']) ? date('Y-m-d H:i:s', $_SESSION['login_time']) : 'Unknown';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - IIBS & OFAC System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h2>Welcome, <?php echo htmlspecialchars($admin_username); ?>!</h2>
            <p style="color: #666; margin-bottom: 10px;">
                You are logged in as administrator
            </p>
            <p style="color: #888; font-size: 0.9rem;">
                Login time: <?php echo $login_time; ?>
            </p>
        </div>

        <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 30px 0;">
            <h3 style="color: #333; margin-bottom: 15px;">System Overview</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <div style="background: white; padding: 15px; border-radius: 8px; text-align: center;">
                    <h4 style="color: #4CAF50; margin-bottom: 10px;">IIBS Records</h4>
                    <p style="font-size: 1.5rem; font-weight: bold; color: #333;">
                        <?php
                        try {
                            $stmt = $pdo->query("SELECT COUNT(*) FROM iibs_records");
                            echo $stmt->fetchColumn();
                        } catch (Exception $e) {
                            echo "N/A";
                        }
                        ?>
                    </p>
                </div>
                <div style="background: white; padding: 15px; border-radius: 8px; text-align: center;">
                    <h4 style="color: #2196F3; margin-bottom: 10px;">OFAC Records</h4>
                    <p style="font-size: 1.5rem; font-weight: bold; color: #333;">
                        <?php
                        try {
                            $stmt = $pdo->query("SELECT COUNT(*) FROM ofac_records");
                            echo $stmt->fetchColumn();
                        } catch (Exception $e) {
                            echo "N/A";
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>

        <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 30px 0;">
            <h3 style="color: #333; margin-bottom: 15px;">Quick Actions</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
                <a href="search_iibs.php" class="btn" style="text-decoration: none; display: block; text-align: center;">
                    Search IIBS Records
                </a>
                <a href="search_ofac.php" class="btn btn-secondary" style="text-decoration: none; display: block; text-align: center;">
                    Search OFAC Records
                </a>
            </div>
        </div>

        <div style="background: #fff3cd; padding: 20px; border-radius: 10px; margin: 30px 0; border-left: 4px solid #ffc107;">
            <h3 style="color: #856404; margin-bottom: 15px;">System Information</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="margin-bottom: 10px;">
                    <strong>Database:</strong> MariaDB/MySQL
                </li>
                <li style="margin-bottom: 10px;">
                    <strong>Server:</strong> XAMPP
                </li>
                <li style="margin-bottom: 10px;">
                    <strong>PHP Version:</strong> <?php echo phpversion(); ?>
                </li>
                <li style="margin-bottom: 10px;">
                    <strong>System Status:</strong> 
                    <span style="color: #28a745; font-weight: bold;">Online</span>
                </li>
            </ul>
        </div>

        <div class="dashboard-nav">
            <a href="index.php" class="btn" style="text-decoration: none;">
                Back to Home
            </a>
            <a href="logout.php" class="btn" style="background: linear-gradient(45deg, #dc3545, #c82333); text-decoration: none;">
                Logout
            </a>
        </div>
    </div>

    <script>
        // Auto-logout after 30 minutes of inactivity
        let inactivityTimer;
        const INACTIVITY_TIMEOUT = 30 * 60 * 1000; // 30 minutes

        function resetInactivityTimer() {
            clearTimeout(inactivityTimer);
            inactivityTimer = setTimeout(() => {
                alert('Session expired due to inactivity. You will be logged out.');
                window.location.href = 'logout.php';
            }, INACTIVITY_TIMEOUT);
        }

        // Reset timer on user activity
        document.addEventListener('mousemove', resetInactivityTimer);
        document.addEventListener('keypress', resetInactivityTimer);
        document.addEventListener('click', resetInactivityTimer);

        // Initialize timer
        resetInactivityTimer();

        // Confirm logout
        document.querySelector('a[href="logout.php"]').addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to logout?')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
