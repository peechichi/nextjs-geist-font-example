<?php
// Demo configuration using SQLite
$db_file = __DIR__ . '/demo_database.sqlite';

// PDO options for better error handling
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];

try {
    $pdo = new PDO("sqlite:$db_file", null, null, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>