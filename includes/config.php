<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'crime_monitor');
define('DB_USER', 'root'); // Change this to your MySQL username
define('DB_PASS', ''); // Change this to your MySQL password

// Site configuration
define('SITE_NAME', 'Crime Monitor');
define('SITE_URL', 'http://localhost/crime-monitor/public/');

// Admin configuration
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin123'); // Change this immediately!

// Security settings
define('SESSION_LIFETIME', 3600); // 1 hour
define('CSRF_TOKEN_LENGTH', 32);

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
