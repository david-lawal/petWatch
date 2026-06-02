<?php
header('Content-Type: application/json');

require_once('models/Database.php');

$db = Database::getInstance()->getdbConnection();

$sql = "
    SELECT 
        sightings.id,
        sightings.pet_id,
        sightings.comment,
        sightings.latitude,
        sightings.longitude,
        sightings.timestamp,
        pets.name AS pet_name
    FROM sightings
    JOIN pets ON sightings.pet_id = pets.id
    WHERE sightings.latitude IS NOT NULL
      AND sightings.longitude IS NOT NULL
";

$stmt = $db->prepare($sql);
$stmt->execute();

$sightings = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($sightings);
