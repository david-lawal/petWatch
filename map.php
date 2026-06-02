<?php
require_once('models/Database.php');

$db = Database::getInstance()->getdbConnection();

$stmt = $db->prepare("SELECT id, name FROM pets ORDER BY name ASC");
$stmt->execute();
$pets = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once('views/map.phtml');