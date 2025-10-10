<?php
include_once __DIR__ . '/../app/services/ServiceContainer.php';
include_once __DIR__ . '/../app/helpers/CsrfHelper.php';

header('Content-Type: application/json');

// Basic security check - ensure user is logged in
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

try {
    $serviceContainer = ServiceContainer::getInstance();
    $stanMagazynuC = $serviceContainer->getController('StanMagazynuC');
    
    // Get current inventory data
    $inventoryData = $stanMagazynuC->readAll();
    
    // Format data for frontend consumption
    $formattedData = array();
    foreach ($inventoryData as $item) {
        $formattedData[] = array(
            'id' => $item['id'],
            'nazwa_ubrania' => $item['nazwa_ubrania'],
            'nazwa_rozmiaru' => $item['nazwa_rozmiaru'],
            'ilosc' => (int)$item['ilosc'],
            'iloscMin' => (int)$item['iloscMin'],
            'status' => $item['ilosc'] < $item['iloscMin'] ? 'low' : 'ok'
        );
    }
    
    echo json_encode($formattedData);
    
} catch (Exception $e) {
    error_log("Error fetching inventory data: " . $e->getMessage());
    echo json_encode(['error' => 'Failed to fetch inventory data']);
}
?>
