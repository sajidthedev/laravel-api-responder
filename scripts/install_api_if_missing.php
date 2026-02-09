<?php

/**
 * This script checks if Laravel API features are installed and installs them if missing.
 * Specifically handles Laravel 11+ where API routes are not included by default.
 */

// Define the root directory
$root = __DIR__;

echo "Checking Laravel API installation...\n";

// 1. Check if it's a Laravel project
if (!file_exists($root . DIRECTORY_SEPARATOR . 'artisan')) {
    echo "Error: 'artisan' not found. Please run this script from the Laravel project root.\n";
    exit(1);
}

// 2. Check if API routes file exists
$apiRoutesPath = $root . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'api.php';

if (file_exists($apiRoutesPath)) {
    echo "API routes already exist at 'routes/api.php'. No action needed.\n";
    exit(0);
}

echo "API routes not found. Attempting to install API features via 'php artisan install:api'...\n";

// 3. Execute php artisan install:api
// We use --no-interaction to avoid blocking in non-interactive environments
$command = "php artisan install:api --no-interaction 2>&1";
exec($command, $output, $returnVar);

if ($returnVar === 0) {
    echo "Successfully installed Laravel API features.\n";
} else {
    echo "Failed to install Laravel API features. Exit code: {$returnVar}\n";
}

// Output the command result
if (!empty($output)) {
    echo "Command output:\n";
    foreach ($output as $line) {
        echo "  > $line\n";
    }
}

exit($returnVar);
