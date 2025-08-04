# PHP Corporation Search System

A professional PHP-based search system for OFAC and IIBS corporation records with MariaDB database integration.

## Features

- **Fixed Admin Authentication**: Secure login with predefined credentials
- **Dual Modal System**: Separate OFAC and IIBS data viewing modals
- **Advanced Search**: Search by corporation names, individual names, and alternate names
- **Professional UI**: Modern, responsive design with clean typography
- **Database Integration**: Full MariaDB support via XAMPP

## Database Structure

The system uses the following database columns matching your requirements:
- SOURCE MEDIA
- SOURCE MEDIA DATE  
- RESOLUTION
- INDIVIDUAL / CORPORATION INVOLVED
- FIRST NAME
- MIDDLE NAME
- LAST NAME
- NAME EXT
- CORPORATION NAME / FULLNAME
- ALTERNATE NAME / ALIAS

## Setup Instructions

### Prerequisites
- XAMPP installed and running
- Apache and MySQL services started in XAMPP

### Installation Steps

1. **Copy Files**
   ```
   Copy the php_app folder to your XAMPP htdocs directory
   Example: C:\xampp\htdocs\php_app\
   ```

2. **Initialize Database**
   ```
   Open browser and navigate to:
   http://localhost/php_app/db_init.php
   
   This will create the database and sample data
   ```

3. **Access the System**
   ```
   Navigate to: http://localhost/php_app/
   
   Default Login Credentials:
   Username: admin
   Password: admin123
   ```

## System Usage

### Login
- Use the default credentials (admin/admin123) to access the system
- The system uses session-based authentication

### Search Functionality
- **Corporation Search**: Enter corporation names to search across all name fields
- **Category Filter**: Filter results by OFAC or IIBS categories
- **Advanced Matching**: Searches corporation names, alternate names, and individual names

### Modal Views
- **OFAC Modal**: View all OFAC records in a dedicated modal window
- **IIBS Modal**: View all IIBS records in a dedicated modal window

## File Structure

```
php_app/
├── config.php              # Database configuration and admin credentials
├── db_init.php             # Database initialization script
├── index.php               # Login page
├── process_login.php       # Login processing
├── admin.php               # Main dashboard
├── search_corporations.php # Search results page
├── logout.php              # Session termination
├── css/
│   └── style.css          # Professional styling
├── modals/
│   ├── ofac_modal.php     # OFAC records modal
│   └── iibs_modal.php     # IIBS records modal
├── sql/
│   └── database_setup.sql # Database schema and sample data
└── README.md              # This file
```

## Database Configuration

The system is configured for standard XAMPP setup:
- **Host**: localhost
- **Database**: corporation_search_db
- **Username**: root
- **Password**: (empty - default XAMPP)

To modify database settings, edit `config.php`.

## Security Features

- Session-based authentication
- SQL injection prevention using prepared statements
- Input sanitization with htmlspecialchars()
- XSS protection

## Customization

### Adding Data
- Use phpMyAdmin to import your corporation data
- Follow the database schema in `sql/database_setup.sql`
- The system supports both individual and corporation records

### Styling
- Modify `css/style.css` for custom styling
- The design uses a professional blue/gray color scheme
- Responsive design works on all device sizes

## Troubleshooting

### Database Connection Issues
1. Ensure XAMPP MySQL service is running
2. Check database credentials in `config.php`
3. Verify database exists by running `db_init.php`

### Login Issues
- Default credentials: admin/admin123
- Clear browser cache and cookies
- Check session configuration in PHP

### Search Not Working
- Verify database connection
- Check if sample data was inserted properly
- Review error logs in XAMPP

## Support

For technical support or customization requests, refer to the system documentation or contact your system administrator.
