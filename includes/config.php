<?php
/**
 * Freelance Client Hub - Configuration
 */
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'freelance_hub');
define('DB_USER', getenv('DB_USER') ?: 'your_db_user');
define('DB_PASS', getenv('DB_PASS') ?: 'your_db_password');

define('APP_SECRET', getenv('APP_SECRET') ?: 'change-this-to-a-long-random-string');

define('APP_NAME', 'Freelance Client Hub');
define('APP_URL', getenv('APP_URL') ?: 'http://localhost:8000');

date_default_timezone_set('UTC');

// Temporarily show errors so we can debug the demo
error_reporting(E_ALL);
ini_set('display_errors', 1);
