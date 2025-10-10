<?php
header('Content-Type: application/json');
include_once __DIR__ . '/../app/services/ServiceContainer.php';

try {
    $query = isset($_GET['query']) ? $_GET['query'] : '';

    $serviceContainer = ServiceContainer::getInstance();
    $rozmiarC = $serviceContainer->getController('RozmiarC');
    $rozmiary = $rozmiarC->searchByName($query);

    header('Content-Type: application/json');
    echo json_encode($rozmiary);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
