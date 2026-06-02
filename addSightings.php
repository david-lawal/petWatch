<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode([
        'success' => false,
        'message' => 'You must be logged in to add a sighting.'
    ]);
    exit;
}

require_once('models/Database.php');

$db = Database::getInstance()->getdbConnection();

$pet_id = isset($_POST['pet_id']) ? (int)$_POST['pet_id'] : 0;
$comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
$latitude = isset($_POST['latitude']) ? (float)$_POST['latitude'] : null;
$longitude = isset($_POST['longitude']) ? (float)$_POST['longitude'] : null;

if ($pet_id <= 0 || $comment === '' || $latitude === null || $longitude === null) {
    echo json_encode([
        'success' => false,
        'message' => 'Please complete all fields.'
    ]);
    exit;
}

if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid coordinates.'
    ]);
    exit;
}

$comment = strip_tags($comment);

$userStmt = $db->prepare("SELECT id FROM users WHERE username = :username LIMIT 1");
$userStmt->execute([':username' => $_SESSION['username']]);
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode([
        'success' => false,
        'message' => 'User not found.'
    ]);
    exit;
}

$user_id = (int)$user['id'];

$petStmt = $db->prepare("SELECT id FROM pets WHERE id = :pet_id LIMIT 1");
$petStmt->execute([':pet_id' => $pet_id]);
$pet = $petStmt->fetch(PDO::FETCH_ASSOC);

if (!$pet) {
    echo json_encode([
        'success' => false,
        'message' => 'Pet not found.'
    ]);
    exit;
}

$insertStmt = $db->prepare("
    INSERT INTO sightings (pet_id, user_id, comment, latitude, longitude)
    VALUES (:pet_id, :user_id, :comment, :latitude, :longitude)
");

$success = $insertStmt->execute([
    ':pet_id' => $pet_id,
    ':user_id' => $user_id,
    ':comment' => $comment,
    ':latitude' => $latitude,
    ':longitude' => $longitude
]);

if ($success) {
    echo json_encode([
        'success' => true,
        'message' => 'Sighting added successfully.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to add sighting.'
    ]);
}