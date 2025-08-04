<?php
require_once 'config.php';

// Function to sanitize input data
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Function to search IIBS records
function searchIIBS($query) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM iibs_records WHERE 
            first_name LIKE :query OR 
            middle_name LIKE :query OR 
            last_name LIKE :query OR 
            corporation_name_fullname LIKE :query OR 
            alternate_name_alias LIKE :query OR 
            alternate_name_alias_2 LIKE :query OR 
            resolution LIKE :query OR 
            source_media LIKE :query 
            ORDER BY created_at DESC");
        $stmt->execute(['query' => '%' . $query . '%']);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("IIBS Search Error: " . $e->getMessage());
        return [];
    }
}

// Function to search OFAC records
function searchOFAC($query) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM ofac_records WHERE 
            first_name LIKE :query OR 
            middle_name LIKE :query OR 
            last_name LIKE :query OR 
            corporation_name_fullname LIKE :query OR 
            alternate_name_alias LIKE :query OR 
            alternate_name_alias_2 LIKE :query OR 
            resolution LIKE :query OR 
            source_media LIKE :query 
            ORDER BY created_at DESC");
        $stmt->execute(['query' => '%' . $query . '%']);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("OFAC Search Error: " . $e->getMessage());
        return [];
    }
}

// Function to format display name for records
function formatDisplayName($record) {
    if ($record['individual_corporation_involved'] === 'Corporation') {
        return $record['corporation_name_fullname'];
    } else {
        $name_parts = array_filter([
            $record['first_name'],
            $record['middle_name'],
            $record['last_name'],
            $record['name_ext']
        ]);
        return implode(' ', $name_parts);
    }
}

// Function to get alternate names
function getAlternateNames($record) {
    $alternates = array_filter([
        $record['alternate_name_alias'],
        $record['alternate_name_alias_2']
    ]);
    return $alternates;
}

// Function to verify admin login
function verifyAdmin($username, $password) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $admin = $stmt->fetch();
        
        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }
        return false;
    } catch (PDOException $e) {
        error_log("Admin Verification Error: " . $e->getMessage());
        return false;
    }
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['admin']) && !empty($_SESSION['admin']);
}

// Function to redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}
?>
