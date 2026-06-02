<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once("models/SightingDataSet.php");
require_once("models/PetDataSet.php");

$view = new stdClass();
$view->pageTitle = "View Sightings";

if (!isset($_GET['pet_id']))
{
    die("Pet not selected.");
}

$pet_id = intval($_GET['pet_id']);
$sightingDataSet = new SightingDataSet();
$petDataSet = new PetDataSet();

$view->pet = $petDataSet->fetchPetById($pet_id);
$view->sightings = $sightingDataSet->fetchSightingsById($pet_id);

require_once("views/viewSighting.phtml");