<?php

if (!defined('IN_INDEX')) {
    exit;
}

class DatabaseException extends Exception {

    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}

// This class manages the connection to the MySQL database
// This is a singleton class, so the object should be invoked
// using the method getDatabase()
class Database {

    private $db_name = null;
    private $db_server = null;
    private $db_user = null;
    private $db_password = null;
    private $localDatabase = null;
    private static $databaseInstance = null; // prevents

    // constructs the class and saves the values for the local variables
    // this values are gonna be used in the mysql connection
    // created by a PDO object

    private function __construct($server, $user, $password, $name) {

        if ($server == null || $user == null || $password == null || $name == null) {
            throw new DatabaseException("Parameters provided cannot be null");
        }

        $this->db_name = $name;
        $this->db_server = $server;
        $this->db_user = $user;
        $this->db_password = $password;
    }

    // prevents the user to create more than one connection
    // also allows the subclasses to access the static method
    public static function getDatabase($server, $user, $password, $name) {
        if (!isset(Database::$databaseInstance)) {
            Database::$databaseInstance = new Database($server, $user, $password, $name);
        }

        return Database::$databaseInstance;
    }

    public static function getDatabaseInstance() {
        if (!isset(Database::$databaseInstance)) {
            throw new DatabaseException("The database has not been loaded");
        }

        return Database::$databaseInstance;
    }

    // tries to connect to the database
    // saves a null value in the $localDatabase variable
    // if the connection is not initialized
    public function connect() {
        $dsn = "mysql:dbname=" . $this->db_name . ";host=" . $this->db_server;

        try {
            $this->localDatabase = new PDO($dsn, $this->db_user, $this->db_password);
        } catch (PDOException $e) {
            $this->localDatabase = null;
            throw new DatabaseException("Connection failed: " . $e->__toString());
        }
    }

    // returns the local database instance (PDO Object)
    public function getPDOInstance() {
        if (!isset($this->localDatabase)) {
            throw new DatabaseException("The database has not been loaded!");
        }

        return $this->localDatabase;
    }

    // checks if the connection is active
    public function isConnected() {
        return ($this->localDatabase instanceOf PDO);
    }

}

?>