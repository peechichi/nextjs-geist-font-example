CREATE DATABASE IF NOT EXISTS corporation_search_db;
USE corporation_search_db;

CREATE TABLE IF NOT EXISTS corporations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category ENUM('OFAC', 'IIBS') NOT NULL,
    source_media VARCHAR(255),
    source_media_date DATE,
    resolution VARCHAR(255),
    individual_corporation_involved ENUM('Individual', 'Corporation') DEFAULT 'Corporation',
    first_name VARCHAR(100),
    middle_name VARCHAR(100),
    last_name VARCHAR(100),
    name_ext VARCHAR(50),
    corporation_name_fullname VARCHAR(255),
    alternate_name_alias VARCHAR(255),
    alternate_name_alias_2 VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_corporation_name (corporation_name_fullname),
    INDEX idx_category (category),
    INDEX idx_first_name (first_name),
    INDEX idx_last_name (last_name),
    INDEX idx_alternate_name (alternate_name_alias)
);

-- Sample OFAC corporations
INSERT INTO corporations (category, source_media, source_media_date, resolution, individual_corporation_involved, corporation_name_fullname, alternate_name_alias) VALUES
('OFAC', 'Federal Register', '2024-01-15', 'Blocked Property', 'Corporation', 'ABC Trading Corporation', 'ABC Trade Corp'),
('OFAC', 'Treasury Notice', '2024-02-20', 'Sanctions List', 'Corporation', 'Global Import Export Ltd', 'Global IE Ltd'),
('OFAC', 'OFAC Update', '2024-03-10', 'SDN List', 'Corporation', 'International Commerce Inc', 'Intl Commerce'),
('OFAC', 'Federal Notice', '2024-04-05', 'Blocked Entity', 'Corporation', 'Worldwide Logistics Corp', 'WW Logistics'),
('OFAC', 'Treasury Alert', '2024-05-12', 'Sanctions Program', 'Corporation', 'Eastern Trading Company', 'East Trade Co');

-- Sample IIBS corporations
INSERT INTO corporations (category, source_media, source_media_date, resolution, individual_corporation_involved, corporation_name_fullname, alternate_name_alias) VALUES
('IIBS', 'IIBS Database', '2024-01-20', 'Investigation Active', 'Corporation', 'Tech Solutions Corp', 'TechSol Corp'),
('IIBS', 'IIBS Report', '2024-02-25', 'Under Review', 'Corporation', 'Digital Services LLC', 'DigiServ LLC'),
('IIBS', 'IIBS Notice', '2024-03-15', 'Compliance Check', 'Corporation', 'Data Systems International', 'DataSys Intl'),
('IIBS', 'IIBS Alert', '2024-04-10', 'Monitoring', 'Corporation', 'Cloud Computing Corp', 'CloudComp Corp'),
('IIBS', 'IIBS Update', '2024-05-18', 'Active Investigation', 'Corporation', 'Network Solutions Inc', 'NetSol Inc');

-- Sample individual records
INSERT INTO corporations (category, source_media, source_media_date, resolution, individual_corporation_involved, first_name, middle_name, last_name, name_ext, alternate_name_alias) VALUES
('OFAC', 'Treasury List', '2024-01-25', 'SDN Individual', 'Individual', 'John', 'Michael', 'Smith', 'Jr.', 'J.M. Smith'),
('OFAC', 'OFAC Notice', '2024-02-28', 'Blocked Person', 'Individual', 'Maria', 'Elena', 'Rodriguez', '', 'M. Rodriguez'),
('IIBS', 'IIBS Individual', '2024-03-20', 'Person of Interest', 'Individual', 'David', 'James', 'Wilson', 'Sr.', 'D.J. Wilson');
