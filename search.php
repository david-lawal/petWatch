<?php

//load required class
require_once('models/PetDataSet.php');

//make a view class
$view = new stdClass();
$view->pageTitle = "Search";

//create dataset object
$petDataSet = new PetDataSet();

//read parameter from search
if (isset($_GET['q']) && !empty(trim($_GET['q'])))
{
    $keyword = trim($_GET['q']);
    $view->pets = $petDataSet->searchPets($keyword);
    $view->searchTerm = htmlspecialchars($keyword);
}
else
{
    $view->pets = [];
    $view->searchTerm = '';
}

//include the view
require_once('views/search.phtml');