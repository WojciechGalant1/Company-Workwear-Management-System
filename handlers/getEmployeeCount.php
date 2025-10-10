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
    $pracownikC = $serviceContainer->getController('PracownikC');
    
    // Get total employee count
    $employees = $pracownikC->getAll();
    $count = count($employees);
    
    echo json_encode(['count' => $count]);
    
} catch (Exception $e) {
    error_log("Error fetching employee count: " . $e->getMessage());
    echo json_encode(['error' => 'Failed to fetch employee count']);
}
?>
