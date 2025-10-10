<?php
include_once __DIR__ . '/../app/services/ServiceContainer.php';

$ubranie_id  = isset($_GET['ubranie_id']) ? $_GET['ubranie_id'] : '';

$serviceContainer = ServiceContainer::getInstance();
$ubranieC = $serviceContainer->getController('UbranieC');
$rozmiary = $ubranieC->getRozmiaryByUbranieId($ubranie_id);

header('Content-Type: application/json');
echo json_encode($rozmiary);
?>
