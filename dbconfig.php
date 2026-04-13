<?php
// =============================================
// dbconfig.php - Database Configuration File
// Aling Nena's Sari-Sari Store System
// =============================================

// Database connection constants
define('DB_HOST',    'localhost');       // Server where MySQL is hosted
define('DB_NAME',    'sarisari_store'); // Name of the database
define('DB_USER',    'root');            // MySQL username
define('DB_PASS',    '');                // MySQL password (empty for local XAMPP)
define('DB_CHARSET', 'utf8');           // Character encoding

/**
 * Creates and returns a PDO connection to the database.
 *
 * @return PDO - The PDO database connection object
 */
function getConnection(): PDO {
    // Build the DSN (Data Source Name) string for MySQL
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

    // PDO options for better error handling and security
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Throw exceptions on error
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Return rows as associative arrays
        PDO::ATTR_EMULATE_PREPARES   => false,                    // Use real prepared statements
    ];

    try {
        // Attempt to connect to the database
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        // Stop script and show error message if connection fails
        die("Connection failed: " . $e->getMessage());
    }
}

// Create the global connection object
$pdo = getConnection();
?>
