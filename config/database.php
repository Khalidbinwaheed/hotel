<?php
// require_once __DIR__ . '/../includes/functions.php';

class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        $this->conn = new mysqli(
            'localhost', 
            'root', 
            '', 
            'hotel_management',
            null,
            'C:/xampp/mysql/mysql.sock'
        );
        
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        
        // Set charset to utf8mb4 for full Unicode support
        $this->conn->set_charset("utf8mb4");
    }
    
    public static function getConnection() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
    
    // Prevent cloning and serialization
    function __clone() {}
    function __wakeup() {}
}

// Initialize connection at the start
$conn = Database::getConnection();

// Ensure tables and default admin user are initialized
initialize_tables($conn);

/**
 * Creates the database if it doesn't exist
 */
function create_database($host, $user, $pass, $dbname, $socket = null) {
    $temp_conn = $socket ? 
        mysqli_connect($host, $user, $pass, null, null, $socket) :
        mysqli_connect($host, $user, $pass);
        
    if (!$temp_conn) {
        error_log("Temporary connection failed: " . mysqli_connect_error());
        return false;
    }
    
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if (mysqli_query($temp_conn, $sql)) {
        mysqli_close($temp_conn);
        return true;
    } else {
        error_log("Database creation error: " . mysqli_error($temp_conn));
        mysqli_close($temp_conn);
        return false;
    }
}

/**
 * Initializes all required tables with proper constraints
 */
function initialize_tables($conn) {
    // Enable foreign key checks
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=0");
    
    $tables = [
        'users' => "CREATE TABLE IF NOT EXISTS users (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            role ENUM('admin', 'manager', 'receptionist', 'staff') NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB",
        
        'room_categories' => "CREATE TABLE IF NOT EXISTS room_categories (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL UNIQUE,
            description TEXT,
            base_price DECIMAL(10,2) NOT NULL,
            capacity INT(11) NOT NULL,
            amenities TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB",
        
        // ... [keep all your other table definitions as they were] ...
    ];

    // Execute all table creation queries with error handling
    foreach ($tables as $table => $sql) {
        if (!mysqli_query($conn, $sql)) {
            error_log("Table creation error ($table): " . mysqli_error($conn));
        }
    }

    // Create default admin user if none exists (using prepared statement)
    $check_admin = "SELECT id FROM users WHERE role = 'admin' LIMIT 1";
    $result = mysqli_query($conn, $check_admin);
    
    if (mysqli_num_rows($result) == 0) {
        $username = 'admin';
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $name = 'System Administrator';
        $email = 'admin@luxestay.com';
        $role = 'admin';
        
        $stmt = mysqli_prepare($conn, 
            "INSERT INTO users (username, password, name, email, role) 
             VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'sssss', $username, $password, $name, $email, $role);
        
        if (!mysqli_stmt_execute($stmt)) {
            error_log("Admin creation failed: " . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt);
    }
    
    // Re-enable foreign key checks
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=1");
}

// Close connection when done (optional, as PHP will close it automatically at script end)
// if (isset($conn)) {
//     mysqli_close($conn);
// }
?>