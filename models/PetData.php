<?php

class PetData
{
    //variables
    private $id, $name, $species, $breed, $color, $status, $description, $date_reported, $user_id;

    public function __construct($row) //make a table
    {
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->species = $row['species'];
        $this->breed = $row['breed'];
        $this->color = $row['color'];
        $this->status = $row['status'];
        $this->description = $row['description'];
        $this->date_reported = $row['date_reported'];
        $this->user_id = $row['user_id'];
    }

    //getters
    public function getId() {return $this->id;}
    public function getName() {return $this->name;}
    public function getSpecies() {return $this->species;}
    public function getBreed() {return $this->breed;}
    public function getColor() {return $this->color;}
    public function getStatus() {return $this->status;}
    public function getDescription() {return $this->description;}
    public function getDateReported() {return $this->date_reported;}
    public function getUserId() {return $this->user_id;}
}
