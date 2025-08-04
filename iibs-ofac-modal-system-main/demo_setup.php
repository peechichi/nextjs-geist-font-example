<?php
// Demo setup for web environment using SQLite
$db_file = __DIR__ . '/demo_database.sqlite';

try {
    // Create SQLite database
    $pdo = new PDO("sqlite:$db_file");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create admin_users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS admin_users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Create iibs_records table
    $pdo->exec("CREATE TABLE IF NOT EXISTS iibs_records (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        source_media VARCHAR(100),
        source_media_date DATE,
        resolution TEXT,
        individual_corporation_involved VARCHAR(20) DEFAULT 'Individual',
        first_name VARCHAR(100),
        middle_name VARCHAR(100),
        last_name VARCHAR(100),
        name_ext VARCHAR(50),
        corporation_name_fullname VARCHAR(200),
        alternate_name_alias VARCHAR(200),
        alternate_name_alias_2 VARCHAR(200),
        status VARCHAR(20) DEFAULT 'active',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Create ofac_records table
    $pdo->exec("CREATE TABLE IF NOT EXISTS ofac_records (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        source_media VARCHAR(100),
        source_media_date DATE,
        resolution TEXT,
        individual_corporation_involved VARCHAR(20) DEFAULT 'Individual',
        first_name VARCHAR(100),
        middle_name VARCHAR(100),
        last_name VARCHAR(100),
        name_ext VARCHAR(50),
        corporation_name_fullname VARCHAR(200),
        alternate_name_alias VARCHAR(200),
        alternate_name_alias_2 VARCHAR(200),
        status VARCHAR(20) DEFAULT 'active',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Insert default admin user (password: admin123)
    $pdo->exec("INSERT OR IGNORE INTO admin_users (username, password) VALUES 
        ('admin', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')");
    
    // Insert sample IIBS corporation records
    $pdo->exec("INSERT OR IGNORE INTO iibs_records (
        source_media, source_media_date, resolution, individual_corporation_involved,
        corporation_name_fullname, alternate_name_alias, status
    ) VALUES
    ('IIBS Database', '2024-01-15', 'Company under investigation for money laundering', 'Corporation', 'ABC Corporation Ltd', 'ABC Corp', 'active'),
    ('IIBS Database', '2024-02-20', 'Entity with questionable transaction patterns', 'Corporation', 'XYZ Holdings Inc', 'XYZ Holdings', 'active'),
    ('IIBS Database', '2024-03-10', 'Corporation flagged for suspicious banking activities', 'Corporation', 'Global Trading Company', 'Global Trade Co', 'active'),
    ('IIBS Database', '2024-04-05', 'International corporation under review', 'Corporation', 'International Business Corp', 'IBC Ltd', 'active'),
    ('IIBS Database', '2024-05-12', 'Technology company with compliance issues', 'Corporation', 'TechSolutions International', 'TechSol Intl', 'active'),
    ('IIBS Database', '2024-06-18', 'Financial services company under scrutiny', 'Corporation', 'Premier Financial Services', 'Premier FS', 'active'),
    ('IIBS Database', '2024-07-22', 'Import/Export company with irregular patterns', 'Corporation', 'WorldWide Import Export', 'WWIE Corp', 'active'),
    ('IIBS Database', '2024-08-15', 'Manufacturing corporation with compliance concerns', 'Corporation', 'Advanced Manufacturing Inc', 'AMI Corp', 'active')");
    
    // Insert sample OFAC corporation records
    $pdo->exec("INSERT OR IGNORE INTO ofac_records (
        source_media, source_media_date, resolution, individual_corporation_involved,
        corporation_name_fullname, alternate_name_alias, status
    ) VALUES
    ('OFAC SDN List', '2024-01-10', 'Company sanctioned for terrorism financing', 'Corporation', 'Blacklisted Corporation', 'Blacklisted Corp', 'active'),
    ('OFAC SDN List', '2024-02-15', 'Entity blocked under economic sanctions', 'Corporation', 'Restricted Industries LLC', 'Restricted Industries', 'active'),
    ('OFAC SDN List', '2024-03-20', 'Financial institution under OFAC restrictions', 'Corporation', 'Sanctioned Bank International', 'Sanctioned Bank', 'active'),
    ('OFAC SDN List', '2024-04-25', 'Trading company on sanctions list', 'Corporation', 'Prohibited Trading Corp', 'PTC Ltd', 'active'),
    ('OFAC SDN List', '2024-05-30', 'Energy company under sanctions program', 'Corporation', 'Blocked Energy Solutions', 'BES Corp', 'active'),
    ('OFAC SDN List', '2024-06-12', 'Transportation company on SDN list', 'Corporation', 'Sanctioned Transport Inc', 'STI Corp', 'active'),
    ('OFAC SDN List', '2024-07-08', 'Investment firm under OFAC restrictions', 'Corporation', 'Restricted Investment Group', 'RIG Holdings', 'active'),
    ('OFAC SDN List', '2024-08-03', 'Telecommunications company on sanctions list', 'Corporation', 'Blocked Communications Ltd', 'BCL Corp', 'active')");
    
    // Insert some individual records for completeness
    $pdo->exec("INSERT OR IGNORE INTO iibs_records (
        source_media, source_media_date, resolution, individual_corporation_involved,
        first_name, middle_name, last_name, alternate_name_alias, status
    ) VALUES
    ('IIBS Database', '2024-01-15', 'Individual flagged for suspicious banking activities', 'Individual', 'John', 'Michael', 'Smith', 'J. Smith', 'active'),
    ('IIBS Database', '2024-02-20', 'Person of interest in financial fraud case', 'Individual', 'Maria', 'Elena', 'Garcia', 'M. Garcia', 'active')");
    
    $pdo->exec("INSERT OR IGNORE INTO ofac_records (
        source_media, source_media_date, resolution, individual_corporation_involved,
        first_name, middle_name, last_name, alternate_name_alias, status
    ) VALUES
    ('OFAC SDN List', '2024-01-10', 'Individual on OFAC sanctions list - Terrorism', 'Individual', 'Ahmed', 'Ali', 'Hassan', 'A. Hassan', 'active'),
    ('OFAC SDN List', '2024-02-15', 'Person designated under sanctions program', 'Individual', 'Ivan', 'Sergei', 'Petrov', 'I. Petrov', 'active')");
    
    echo "<h2>Demo Database Setup Complete!</h2>";
    echo "<p>SQLite database created successfully with sample data.</p>";
    echo "<p><strong>Database file:</strong> " . $db_file . "</p>";
    echo "<p><strong>Admin credentials:</strong> admin / admin123</p>";
    echo "<p><a href='index.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Main Page</a></p>";
    
    // Update config.php to use SQLite
    $config_content = "<?php
// Demo configuration using SQLite
\$db_file = __DIR__ . '/demo_database.sqlite';

// PDO options for better error handling
\$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];

try {
    \$pdo = new PDO(\"sqlite:\$db_file\", null, null, \$options);
} catch (PDOException \$e) {
    die(\"Database connection failed: \" . \$e->getMessage());
}
?>";
    
    file_put_contents(__DIR__ . '/config.php', $config_content);
    echo "<p><em>Configuration updated to use SQLite database.</em></p>";
    
} catch (PDOException $e) {
    echo "<h2>Database Setup Failed!</h2>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>
