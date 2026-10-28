<?php
/**
 * Freelance Client Hub - Configuration Example
 * Copy this file to config.php and fill in your values.
 * Never commit config.php to version control.
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'freelance_hub');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_db_password');

// Used for session security / CSRF tokens
define('APP_SECRET', 'change-this-to-a-long-random-string');

// App settings
define('APP_NAME', 'Freelance Client Hub');
define('APP_URL', 'http://localhost:8000'); // no trailing slash

// Timezone
date_default_timezone_set('UTC');

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
