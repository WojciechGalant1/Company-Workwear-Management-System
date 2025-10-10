<?php
header('Content-Type: application/json');
include_once __DIR__ . '/../app/services/ServiceContainer.php';

try {
    $query = isset($_GET['query']) ? $_GET['query'] : '';

    $serviceContainer = ServiceContainer::getInstance();
    $ubranieC = $serviceContainer->getController('UbranieC');
    $ubrania = $ubranieC->searchByName($query);

    if ($ubrania === false) {
        throw new Exception('Failed to fetch data');
    }

    echo json_encode($ubrania);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
