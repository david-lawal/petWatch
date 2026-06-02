<?php
require_once('Database.php');

class SightingDataSet {
    private $_dbHandle;

    public function __construct() {
        $_dbInstance = Database::getInstance();
        $this->_dbHandle = $_dbInstance->getdbConnection();
    }

    public function addSighting($pet_id, $user_id, $comment, $latitude, $longitude)
    {
        $sql = "INSERT INTO sightings (pet_id, user_id, comment, latitude, longitude, timestamp)
                VALUES (:pet_id, :user_id, :comment, :latitude, :longitude, datetime('now'))";

        $statement = $this->_dbHandle->prepare($sql);
        $statement->bindValue(':pet_id', $pet_id);
        $statement->bindValue(':user_id', $user_id);
        $statement->bindValue(':comment', $comment);
        $statement->bindValue(':latitude', $latitude);
        $statement->bindValue(':longitude', $longitude);
        $statement->execute();
    }

    public function fetchSightingsById($pet_id)
    {
        $sql = "SELECT s.*, u.username
                FROM sightings s
                LEFT JOIN users u ON s.user_id = u.id
                WHERE s.pet_id = :pet_id
                ORDER BY s.timestamp DESC";

        $statement = $this->_dbHandle->prepare($sql);
        $statement->bindValue(':pet_id', $pet_id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}