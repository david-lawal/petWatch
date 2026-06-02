<?php
header('Content-Type: application/json');
require_once('models/Database.php');

$db = Database::getInstance()->getdbConnection();

$term = isset($_GET['term']) ? trim($_GET['term']) : '';

$sql = "
    SELECT id, name, species, breed, color, status, photo_url
    FROM pets
    WHERE name LIKE :term
       OR species LIKE :term
       OR breed LIKE :term
       OR color LIKE :term
       OR status LIKE :term
    ORDER BY name ASC
    LIMIT 50
";

$stmt = $db->prepare($sql);
$stmt->execute([
    ':term' => '%' . $term . '%'
]);

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);
