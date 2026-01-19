# Crime Monitor

A web-based crime monitoring system with public statistics dashboard and admin panel for managing crime incidents.

## Features

- **Public Dashboard**: View crime statistics and recent incidents
- **Admin Panel**: Secure login for administrators to manage crime data
- **Crime Categories**: Organized crime types (Theft, Assault, Vandalism, etc.)
- **Status Tracking**: Track incident status (Reported, Investigating, Resolved)
- **Severity Levels**: Low, Medium, High priority incidents
- **Location Data**: Store location information with optional coordinates
- **Responsive Design**: Works on desktop and mobile devices

## Requirements

- PHP 8.0 or higher
- MySQL 5.7 or higher (or MariaDB)
- Web server (Apache/Nginx) or PHP built-in server

## Installation

### 1. Install XAMPP (Windows)

1. Download XAMPP from [apachefriends.org](https://www.apachefriends.org/)
2. Install XAMPP with Apache, MySQL, and PHP
3. Start Apache and MySQL from XAMPP Control Panel

### 2. Database Setup

1. Open phpMyAdmin (usually at `http://localhost/phpmyadmin/`)
2. Create a new database named `crime_monitor`
3. Import the schema file: Go to Import tab and select `database/schema.sql`

### 3. Configure Database Connection

Edit `includes/config.php` and update the database settings if needed:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'crime_monitor');
define('DB_USER', 'root'); // Your MySQL username
define('DB_PASS', ''); // Your MySQL password
```

### 4. File Permissions (if needed)

Make sure the web server can read/write to the project directory.

## Usage

### Accessing the Application

- **Public Dashboard**: `http://localhost/crime-monitor/public/`
- **Admin Login**: `http://localhost/crime-monitor/admin/login.php`

### Default Admin Credentials

- **Username**: admin
- **Password**: admin123

⚠️ **Important**: Change the default password immediately after first login!

### Adding Crime Incidents

1. Login to admin panel
2. Click "Add Crime Incident"
3. Fill in the required fields (Title, Category, Incident Date)
4. Optional: Add description, location, coordinates, status, severity
5. Click "Add Incident"

## Project Structure

```
crime-monitor/
├── public/           # Public web files
│   ├── index.php     # Public dashboard
│   ├── css/style.css # Custom styles
│   ├── js/app.js     # Frontend JavaScript
│   └── api/          # API endpoints
│       ├── stats.php # Statistics API
│       └── crimes.php# Crimes data API
├── admin/            # Admin panel
│   ├── index.php     # Admin dashboard
│   ├── login.php     # Admin login
│   ├── logout.php    # Admin logout
│   └── add-crime.php # Add crime form
├── includes/         # Backend includes
│   ├── config.php    # Configuration
│   ├── database.php  # Database class
│   ├── functions.php # Helper functions
│   └── classes/      # PHP classes (future use)
├── database/         # Database files
│   └── schema.sql    # Database schema
└── README.md         # This file
```

## Security Features

- Password hashing for admin accounts
- Input validation and sanitization
- CSRF protection (implemented but can be enhanced)
- Session-based authentication
- SQL injection prevention with prepared statements

## Development

### Running with PHP Built-in Server

If you don't have Apache/Nginx:

```bash
cd crime-monitor/public
php -S localhost:8000
```

Then access at `http://localhost:8000/`

### Adding New Features

1. Database changes: Update `database/schema.sql`
2. Backend logic: Add to `includes/database.php` or create new classes
3. Frontend: Modify `public/index.php`, CSS, or JavaScript
4. Admin features: Add to `admin/` directory

## API Endpoints

- `GET /public/api/stats.php` - Get crime statistics
- `GET /public/api/crimes.php?limit=X&offset=Y` - Get crime incidents

## Troubleshooting

### Database Connection Issues

- Check MySQL is running
- Verify database credentials in `config.php`
- Ensure database `crime_monitor` exists

### Admin Login Not Working

- Check if database tables were created correctly
- Verify admin user exists: `SELECT * FROM admins;`
- Check password hash in database

### Permission Errors

- Ensure web server can access project files
- On Linux/Mac: `chmod -R 755 crime-monitor/`

## License

This project is for educational purposes. Modify and use as needed.

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make changes
4. Test thoroughly
5. Submit a pull request
