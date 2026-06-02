<?php
class Database
{
    protected static $_dbInstance = null;
    protected $_dbHandle;

    public static function getInstance()
    {
        if (self::$_dbInstance === null)
        {
            self::$_dbInstance = new self();
        }
        return self::$_dbInstance;
    }

    public function __construct()
    {
        try
        {
            $dsn = 'sqlite:' . __DIR__ . '/../database/petwatch.sqlite';
            $this->_dbHandle = new PDO($dsn);
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
        }
    }

    public function getdbConnection()
    {
        return $this->_dbHandle;
    }

    public function __destruct()
    {
        $this->_dbHandle = null;
    }
}
