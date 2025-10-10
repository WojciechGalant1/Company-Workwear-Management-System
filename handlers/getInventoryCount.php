<?php
include_once __DIR__ . '/../app/services/ServiceContainer.php';

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
    
    // Get total inventory count
    $inventory = $stanMagazynuC->readAll();
    $count = count($inventory);
    
    echo json_encode(['count' => $count]);
    
} catch (Exception $e) {
    error_log("Error fetching inventory count: " . $e->getMessage());
    echo json_encode(['error' => 'Failed to fetch inventory count']);
}
?>
