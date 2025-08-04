<?php
require_once 'config.php';

try {
    // First connect without database to create it
    $dsn = "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Read and execute SQL setup script
    $sql = file_get_contents(__DIR__ . '/sql/database_setup.sql');
    
    // Split SQL into individual statements and execute them
    $statements = explode(';', $sql);
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            $pdo->exec($statement);
        }
    }
    
    echo "<h2>Database Setup Complete!</h2>";
    echo "<p>Database 'corporation_search_db' and tables created successfully!</p>";
    echo "<p>Sample corporation data has been inserted.</p>";
    echo "<p><a href='index.php'>Go to Login Page</a></p>";
    
} catch (PDOException $e) {
    echo "<h2>Database Setup Failed!</h2>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<p>Please make sure XAMPP is running and MySQL service is started.</p>";
}
?>
