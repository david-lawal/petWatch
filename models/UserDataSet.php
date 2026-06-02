<?php

require_once('Database.php');
require_once('UserData.php');

class UserDataSet
{
    private $_dbInstance, $_dbHandle;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function findUser($username)
    {
        $sql = "SELECT * FROM users WHERE username = :username";
        $statement = $this->_dbHandle->prepare($sql);
        $statement->bindValue(':username', $username, PDO::PARAM_STR);
        $statement->execute();
        $row =  $statement->fetch(PDO::FETCH_ASSOC);

        return $row ? new UserData($row) : null;
    }
}
