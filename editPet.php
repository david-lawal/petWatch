<?php

session_start();

require_once('models/Database.php');
require_once('models/PetDataSet.php');

$view = new stdClass();
$view->pageTitle = "Edit Pet";

$petDataSet = new PetDataSet();

if (!isset($_SESSION['user_id']))
{
    header('Location: manage.php');
    exit;
}

$pet_id = (int)$_GET['pet_id'];
$pet = $petDataSet->fetchPetById($pet_id);

if (!$pet)
{
    header('Location: manage.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $petDataSet->updatePet($pet_id, $_SESSION['user_id'], $_POST);
    header('Location: manage.php');
    exit;
}

$view->pet = $pet;
require_once('views/editPet.phtml');