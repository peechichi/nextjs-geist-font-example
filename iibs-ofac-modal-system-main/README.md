# IIBS & OFAC Modal System

A professional PHP-based system for searching IIBS (Internal Investigation Banking System) and OFAC (Office of Foreign Assets Control) sanctions records using XAMPP and MariaDB.

## Features

- **Two Modal System**: IIBS Modal and OFAC Modal with search functionality
- **Admin Login**: Secure admin authentication system
- **Professional Design**: Modern CSS styling with responsive layout
- **Database Integration**: MariaDB/MySQL database with proper indexing
- **Real Data Structure**: Based on consolidated sanctions list format
- **Search Functionality**: Comprehensive search across multiple fields

## System Requirements

- XAMPP (Apache + MariaDB/MySQL + PHP)
- PHP 7.4 or higher
- MariaDB/MySQL 5.7 or higher
- Web browser (Chrome, Firefox, Safari, Edge)

## Installation Instructions

### 1. Setup XAMPP
1. Download and install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Start Apache and MySQL services from XAMPP Control Panel

### 2. Database Setup
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Import the database structure:
   - Click "Import" tab
   - Choose file: `db_setup.sql`
   - Click "Go" to execute

   **OR** run the SQL commands manually:
   ```sql
   -- Copy and paste the contents of db_setup.sql into phpMyAdmin SQL tab
   ```

### 3. File Setup
1. Copy all project files to your XAMPP htdocs directory:
   ```
   C:\xampp\htdocs\modal-system\  (Windows)
   /Applications/XAMPP/htdocs/modal-system/  (Mac)
   /opt/lampp/htdocs/modal-system/  (Linux)
   ```

### 4. Configuration
1. Edit `config.php` if needed to match your database settings:
   ```php
   $host = 'localhost';
   $db_name = 'modal_system';
   $username = 'root';
   $password = '';  // Default XAMPP MySQL password is empty
   ```

## Usage

### Accessing the System
1. Open your web browser
2. Navigate to: `http://localhost/modal-system/`

### Admin Login
- **URL**: `http://localhost/modal-system/login.php`
- **Username**: `admin`
- **Password**: `admin123`

### Search Functions
1. **IIBS Modal**: Search internal banking investigation records
2. **OFAC Modal**: Search OFAC sanctions list records

## Database Structure

### Tables Created
- `admin_users`: Admin authentication
- `iibs_records`: IIBS investigation records
- `ofac_records`: OFAC sanctions records

### Key Fields (Based on Consolidated Sanctions List)
- `source_media`: Source of the record
- `source_media_date`: Date from source
- `resolution`: Description/resolution details
- `individual_corporation_involved`: Type (Individual/Corporation)
- `first_name`, `middle_name`, `last_name`: Individual names
- `corporation_name_fullname`: Corporation name
- `alternate_name_alias`: Alternative names/aliases
- `status`: Record status

## File Structure
```
modal-system/
├── config.php              # Database configuration
├── functions.php            # Core functions
├── db_setup.sql            # Database setup script
├── index.php               # Main page with modals
├── login.php               # Admin login page
├── process_login.php       # Login processing
├── admin_dashboard.php     # Admin dashboard
├── logout.php              # Logout functionality
├── search_iibs.php         # IIBS search results
├── search_ofac.php         # OFAC search results
├── styles.css              # Professional styling
└── README.md               # This file
```

## Security Features

- **Password Hashing**: Admin passwords are hashed using PHP's password_hash()
- **SQL Injection Protection**: All queries use prepared statements
- **Input Sanitization**: All user inputs are sanitized
- **Session Management**: Secure session handling for admin authentication
- **XSS Protection**: Output is escaped using htmlspecialchars()

## Search Capabilities

The system searches across multiple fields:
- First Name, Middle Name, Last Name
- Corporation/Full Name
- Alternate Names/Aliases
- Resolution/Description
- Source Media

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Ensure MySQL is running in XAMPP
   - Check database credentials in `config.php`
   - Verify database `modal_system` exists

2. **Page Not Found**
   - Ensure files are in correct htdocs directory
   - Check Apache is running in XAMPP
   - Verify URL path is correct

3. **Login Issues**
   - Use default credentials: admin/admin123
   - Check if admin_users table has data
   - Clear browser cache/cookies

4. **Search Not Working**
   - Verify database tables have sample data
   - Check PHP error logs
   - Ensure database connection is working

### Error Logs
- Check XAMPP error logs: `xampp/apache/logs/error.log`
- PHP errors are logged to system error log

## Customization

### Adding Real Data
1. Export your consolidated sanctions list to CSV
2. Import data into `iibs_records` and `ofac_records` tables
3. Match columns to database structure

### Styling Changes
- Modify `styles.css` for design changes
- Colors, fonts, and layout can be customized
- Responsive design works on mobile devices

## Support

For technical support or questions:
1. Check this README file
2. Review PHP error logs
3. Verify XAMPP services are running
4. Test database connection in phpMyAdmin

## Version Information
- **Version**: 1.0
- **Last Updated**: January 2025
- **PHP Version**: 7.4+
- **Database**: MariaDB/MySQL
- **Framework**: Native PHP (No external dependencies)
