<?php

require_once('Database.php');
require_once('PetData.php');

class PetDataSet
{
    protected $_dbHandle, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function fetchAllPets()
    {
        $sqlQuery = 'SELECT * FROM pets ORDER BY status DESC, date_reported DESC';

        $statement = $this->_dbHandle->prepare($sqlQuery); //prepare a PDO statement
        $statement->execute(); //execute the PDO statement

        $pets = [];
        //loop through and read the results of the query and cast appropriately
        //into a matching object
        while ($row = $statement->fetch())
        {
            $pets[] = new PetData($row);
        }
        return $pets;
    }

    public function searchPets($keyword)
    {
        $sqlQuery = 'SELECT * FROM pets
                     WHERE name LIKE :keyword
                     OR species LIKE :keyword
                     OR breed LIKE :keyword
                     OR color LIKE :keyword
                     OR description LIKE :keyword
                     ORDER BY status DESC, date_reported DESC';

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $searchTerm = '%' . $keyword . '%';
        $statement->execute(['keyword' => $searchTerm]);

        $pets = [];
        while ($row = $statement->fetch()) {
            $pets[] = new PetData($row);
        }

        return $pets;
    }

    public function addPet($user_id, $data)
    {
        $sql = "INSERT INTO pets(name, species, breed, color, status, description, date_reported, user_id)
                VALUES (:name, :species, :breed, :color, :status, :description, DATE('now'), :user_id)";

        $statement = $this->_dbHandle->prepare($sql);
        $statement->bindValue(':name', $data['name']);
        $statement->bindValue(':species', $data['species']);
        $statement->bindValue(':breed', $data['breed']);
        $statement->bindValue(':color', $data['color']);
        $statement->bindValue(':status', $data['status']);
        $statement->bindValue(':description', $data['description']);
        $statement->bindValue(':user_id', $user_id);

        $statement->execute();
    }

    public function deletePet($pet_id, $user_id)
    {
        $sql = "DELETE FROM pets WHERE id = :id AND user_id = :user_id";
        $statement = $this->_dbHandle->prepare($sql);
        $statement->bindValue(':id', $pet_id, PDO::PARAM_INT);
        $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        $statement->execute();
    }

    public function updatePet($pet_id, $user_id, $data)
    {
        $sql = "UPDATE pets
                SET name = :name,
                    species = :species,
                    breed = :breed,
                    color = :color,
                    status = :status,
                    description = :description
                WHERE id = :id AND user_id = :user_id";

        $statement = $this->_dbHandle->prepare($sql);

        $statement->bindValue(':name', $data['name']);
        $statement->bindValue(':species', $data['species']);
        $statement->bindValue(':breed', $data['breed']);
        $statement->bindValue(':color', $data['color']);
        $statement->bindValue(':status', $data['status']);
        $statement->bindValue(':description', $data['description']);
        $statement->bindValue(':id', $pet_id, PDO::PARAM_INT);
        $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        $statement->execute();
    }

    public function fetchPetById($pet_id)
    {
        $sql = "SELECT * FROM pets WHERE id = :id";
        $statement = $this->_dbHandle->prepare($sql);
        $statement->bindValue(':id', $pet_id, PDO::PARAM_INT);
        $statement->execute();

        $row = $statement->fetch();
        return $row ? new PetData($row) : null;
    }

    public function fetchPetsByOwner($user_id)
    {
        $sql = "SELECT * FROM pets WHERE user_id = :user_id";
        $statement = $this->_dbHandle->prepare($sql);
        $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $statement->execute();

        $pets = [];
        while ($row = $statement->fetch()) {
            $pets[] = new PetData($row);
        }

        return $pets;
    }
}