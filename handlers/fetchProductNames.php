<?php
include_once __DIR__ . '/../app/controllers/UbranieC.php';

$query = isset($_GET['query']) ? $_GET['query'] : '';

$ubranieC = new UbranieC();
$ubrania = $ubranieC->searchByName($query);

header('Content-Type: application/json');
echo json_encode($ubrania);
?>
