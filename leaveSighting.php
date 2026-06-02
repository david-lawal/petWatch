<?php

if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once ('models/SightingDataSet.php');

$view = new stdClass();
$view->pageTitle = "Leave a Sighting";
$error = '';
$success = '';

if (!isset($_GET['pet_id'])) {
    $error = 'Pet not selected';
}
else {
    $pet_id = intval($_GET['pet_id']);
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment = trim($_POST['comment']);
    $latitude = trim($_POST['latitude']);
    $longitude = trim($_POST['longitude']);
    $user_id = $_SESSION['user_id'];

    if(!empty($comment) && !empty($latitude) && !empty($longitude)) {
        $sightingDataSet = new SightingDataSet();
        $sightingDataSet->addSighting($pet_id, $user_id, $comment, $latitude, $longitude);
    }
}

require_once('views/leaveSighting.phtml');