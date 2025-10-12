<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../app/services/ServiceContainer.php';
include_once __DIR__ . '/../app/helpers/CsrfHelper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate CSRF token
    if (!CsrfHelper::validateTokenFromJson($data)) {
        http_response_code(403);
        echo json_encode(CsrfHelper::getErrorResponse());
        exit;
    }
    
    $id = isset($data['id']) ? $data['id'] : null;

    if ($id) {
        $serviceContainer = ServiceContainer::getInstance();
        $wydaneUbraniaC = $serviceContainer->getController('WydaneUbraniaC');
        $success = $wydaneUbraniaC->destroyStatus($id);

        if ($success) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Nie udało się zaktualizować statusu.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Nie podano ID ubrania.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Metoda niedozwolona.']);
    exit;
}

