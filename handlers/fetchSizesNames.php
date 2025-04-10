<?php
include_once __DIR__ . '/../app/controllers/RozmiarC.php';

$query = isset($_GET['query']) ? $_GET['query'] : '';

$rozmiarC = new RozmiarC();
$rozmiary = $rozmiarC->searchByName($query);

header('Content-Type: application/json');
echo json_encode($rozmiary);
?>
