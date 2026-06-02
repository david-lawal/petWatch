<?php

class UserData
{
    private $id, $username, $email, $password_hash, $role;

    public function __construct($dbRow)
    {
        $this->id = $dbRow['id'];
        $this->username = $dbRow['username'];
        $this->email = $dbRow['email'];
        $this->password_hash = $dbRow['password_hash'];
        $this->role = $dbRow['role'];
    }

    public function getId() { return $this->id; }
    public function getUsername() { return $this->username; }
    public function getEmail() { return $this->email; }
    public function getPasswordHash() { return $this->password_hash; }
    public function getRole() { return $this->role; }
}