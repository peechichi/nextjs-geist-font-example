-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS modal_system;
USE modal_system;

-- Admin users table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- IIBS records table (based on consolidated sanctions list structure)
CREATE TABLE IF NOT EXISTS iibs_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    source_media VARCHAR(100),
    source_media_date DATE,
    resolution TEXT,
    individual_corporation_involved ENUM('Individual', 'Corporation') DEFAULT 'Individual',
    first_name VARCHAR(100),
    middle_name VARCHAR(100),
    last_name VARCHAR(100),
    name_ext VARCHAR(50),
    corporation_name_fullname VARCHAR(200),
    alternate_name_alias VARCHAR(200),
    alternate_name_alias_2 VARCHAR(200),
    status VARCHAR(20) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_names (first_name, last_name, corporation_name_fullname),
    INDEX idx_alternate (alternate_name_alias),
    INDEX idx_status (status)
);

-- OFAC records table (based on consolidated sanctions list structure)
CREATE TABLE IF NOT EXISTS ofac_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    source_media VARCHAR(100),
    source_media_date DATE,
    resolution TEXT,
    individual_corporation_involved ENUM('Individual', 'Corporation') DEFAULT 'Individual',
    first_name VARCHAR(100),
    middle_name VARCHAR(100),
    last_name VARCHAR(100),
    name_ext VARCHAR(50),
    corporation_name_fullname VARCHAR(200),
    alternate_name_alias VARCHAR(200),
    alternate_name_alias_2 VARCHAR(200),
    status VARCHAR(20) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_names (first_name, last_name, corporation_name_fullname),
    INDEX idx_alternate (alternate_name_alias),
    INDEX idx_status (status)
);

-- Insert default admin user (password: admin123)
INSERT INTO admin_users (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert sample IIBS records with proper structure
INSERT INTO iibs_records (
    source_media, source_media_date, resolution, individual_corporation_involved,
    first_name, middle_name, last_name, corporation_name_fullname, alternate_name_alias, status
) VALUES
('IIBS Database', '2024-01-15', 'Flagged for suspicious banking activities', 'Individual', 'John', 'Michael', 'Smith', NULL, 'J. Smith', 'active'),
('IIBS Database', '2024-02-20', 'Company under investigation for money laundering', 'Corporation', NULL, NULL, NULL, 'ABC Corporation Ltd', 'ABC Corp', 'active'),
('IIBS Database', '2024-03-10', 'Person of interest in financial fraud case', 'Individual', 'Maria', 'Elena', 'Garcia', NULL, 'M. Garcia', 'active'),
('IIBS Database', '2024-04-05', 'Entity with questionable transaction patterns', 'Corporation', NULL, NULL, NULL, 'XYZ Holdings Inc', 'XYZ Holdings', 'active'),
('IIBS Database', '2024-05-12', 'Individual with multiple suspicious transfers', 'Individual', 'Robert', 'James', 'Johnson', NULL, 'Bob Johnson', 'active');

-- Insert sample OFAC records with proper structure
INSERT INTO ofac_records (
    source_media, source_media_date, resolution, individual_corporation_involved,
    first_name, middle_name, last_name, corporation_name_fullname, alternate_name_alias, status
) VALUES
('OFAC SDN List', '2024-01-10', 'Individual on OFAC sanctions list - Terrorism', 'Individual', 'Ahmed', 'Ali', 'Hassan', NULL, 'A. Hassan', 'active'),
('OFAC SDN List', '2024-02-15', 'Company sanctioned for terrorism financing', 'Corporation', NULL, NULL, NULL, 'Blacklisted Corporation', 'Blacklisted Corp', 'active'),
('OFAC SDN List', '2024-03-20', 'Person designated under sanctions program', 'Individual', 'Ivan', 'Sergei', 'Petrov', NULL, 'I. Petrov', 'active'),
('OFAC SDN List', '2024-04-25', 'Entity blocked under economic sanctions', 'Corporation', NULL, NULL, NULL, 'Restricted Industries LLC', 'Restricted Industries', 'active'),
('OFAC SDN List', '2024-05-30', 'Financial institution under OFAC restrictions', 'Corporation', NULL, NULL, NULL, 'Sanctioned Bank International', 'Sanctioned Bank', 'active');
