<?php
include_once __DIR__ . '/../app/controllers/UbranieC.php';

$ubranie_id  = isset($_GET['ubranie_id']) ? $_GET['ubranie_id'] : '';

$ubranieC = new UbranieC();
$rozmiary = $ubranieC->getRozmiaryByUbranieId($ubranie_id);

header('Content-Type: application/json');
echo json_encode($rozmiary);
?>
