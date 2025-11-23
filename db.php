<?php
/**
 * Database Connection Utility
 */

require_once 'config.php';

class Database {
    private static $connection = null;
    
    /**
     * Get database connection
     * @return mysqli
     */
    public static function getConnection() {
        if (self::$connection === null) {
            self::$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            if (self::$connection->connect_error) {
                die("Connection failed: " . self::$connection->connect_error);
            }
            
            self::$connection->set_charset("utf8mb4");
        }
        
        return self::$connection;
    }
    
    /**
     * Close database connection
     */
    public static function closeConnection() {
        if (self::$connection !== null) {
            self::$connection->close();
            self::$connection = null;
        }
    }
}
