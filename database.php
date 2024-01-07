<?php
class Database
{
    private static $connection;

    public static function getConnection()
    {
        if (!self::$connection) {
            self::$connection = new PDO('mysql:host=localhost;dbname=ELECTRONACERV4', 'root', '');
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$connection;
    }
}

// Define a generic DAO class
class BaseDAO
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }
}
