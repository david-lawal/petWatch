<?php

//load required class
require_once('models/PetDataSet.php');

//make a view class
$view = new stdClass();
$view->pageTitle = "Browse";

//create pet dataset object
$petDataSet = new PetDataSet();
$view->petDataSet = $petDataSet->fetchAllPets();

//send result count
if (count($view->petDataSet) == 0)
{
    $view->dbMessage = "No pets found";
}
else
{
    $view->dbMessage = count($view->petDataSet) . " pets found";
}

//include the view
require_once("views/browse.phtml");
