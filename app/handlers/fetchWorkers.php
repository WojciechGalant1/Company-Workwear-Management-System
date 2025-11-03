<?php
include_once __DIR__ . '/../services/ServiceContainer.php';

$query = isset($_GET['query']) ? $_GET['query'] : '';

$serviceContainer = ServiceContainer::getInstance();
$pracownikC = $serviceContainer->getController('EmployeeController');
$pracownicy = $pracownikC->searchByName($query);

header('Content-Type: application/json');
echo json_encode($pracownicy);
?>