<?php
include_once __DIR__ . '/../app/services/ServiceContainer.php';
include_once __DIR__ . '/../app/auth/SessionManager.php';
include_once __DIR__ . '/../app/helpers/CsrfHelper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!CsrfHelper::validateToken()) {
        http_response_code(403);
        echo json_encode(CsrfHelper::getErrorResponse());
        exit;
    }

    // Ensure session and get current user id
    $sessionManager = new SessionManager();
    $currentUserId = $sessionManager->getUserId();

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $nazwa = isset($_POST['nazwa']) ? trim($_POST['nazwa']) : '';
    $rozmiar = isset($_POST['rozmiar']) ? trim($_POST['rozmiar']) : '';
    $ilosc = isset($_POST['ilosc']) ? intval($_POST['ilosc']) : 0;
    $iloscMin = isset($_POST['iloscMin']) ? intval($_POST['iloscMin']) : 0;
    $uwagi = isset($_POST['uwagi']) ? trim($_POST['uwagi']) : '';

    // Basic validation
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Nieprawidłowe ID.']);
        exit;
    }

    if (empty($nazwa) || empty($rozmiar)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Nazwa i rozmiar są wymagane.']);
        exit;
    }

    if ($ilosc < 0 || $iloscMin < 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Ilość nie może być ujemna.']);
        exit;
    }

    $serviceContainer = ServiceContainer::getInstance();
    $stanMagazynuC = $serviceContainer->getController('StanMagazynuC');

    $result = $stanMagazynuC->updateStanMagazynu($id, $nazwa, $rozmiar, $ilosc, $iloscMin, $uwagi, $currentUserId);

    if ($result['status'] === 'success') {
        http_response_code(200);
    } elseif ($result['status'] === 'not_found') {
        http_response_code(404);
    } else {
        http_response_code(500);
    }
    echo json_encode($result);
} else {
    http_response_code(405);
    echo json_encode(['status' => 'method_not_allowed', 'message' => 'Metoda niedozwolona.']);
}
?>
