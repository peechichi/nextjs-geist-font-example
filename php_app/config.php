<?php
// Fixed admin credentials
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'admin123'); // Use password_hash() in production

// MariaDB connection settings for XAMPP
define('DB_HOST', 'localhost');
define('DB_NAME', 'corporation_search_db');
define('DB_USER', 'root');
define('DB_PASS', ''); // Default XAMPP has no password
define('DB_CHARSET', 'utf8mb4');

// PDO connection function
function getDBConnection() {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}
?>
