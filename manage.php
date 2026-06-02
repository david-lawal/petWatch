<?php

if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once('models/Database.php');
require_once('models/PetDataSet.php');

$view = new stdClass();
$view->pageTitle = "Manage Pets";

$petDataSet = new PetDataSet();

//CRUD actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $petDataSet->addPet($_SESSION['user_id'], $_POST);
    } elseif (isset($_POST['delete'])) {
        $petDataSet->deletePet($_POST['pet_id'], $_SESSION['user_id']);
    }
}

//load all the pets according to the user
$view->petDataSet = $petDataSet->fetchPetsByOwner($_SESSION['user_id']);

require_once('views/manage.phtml');