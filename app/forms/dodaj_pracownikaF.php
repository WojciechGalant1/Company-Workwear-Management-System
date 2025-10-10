<?php
include_once __DIR__ . '/../services/ServiceContainer.php';
include_once __DIR__ . '/../helpers/CsrfHelper.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!CsrfHelper::validateToken()) {
        $response['success'] = false;
        $response['message'] = "Błąd bezpieczeństwa. Odśwież stronę i spróbuj ponownie.";
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    
    $imie = isset($_POST['imie']) ? trim($_POST['imie']) : '';
    $nazwisko = isset($_POST['nazwisko']) ? trim($_POST['nazwisko']) : '';
    $stanowisko = isset($_POST['stanowisko']) ? trim($_POST['stanowisko']) : '';
    $status = 1;

    // Basic input validation
    if (empty($imie) || empty($nazwisko) || empty($stanowisko)) {
        $response['success'] = false;
        $response['message'] = "Wszystkie pola są wymagane.";
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    $pracownik = new Pracownik($imie, $nazwisko, $stanowisko, $status);
    $serviceContainer = ServiceContainer::getInstance();
    $pracownikC = $serviceContainer->getController('PracownikC');

    if ($pracownikC->create($pracownik)) {
        $response['success'] = true;
        $response['message'] = "Pracownik został dodany pomyślnie.";
        $response['newEmployee'] = array(
            'imie' => $imie,
            'nazwisko' => $nazwisko,
            'stanowisko' => $stanowisko,
            'timestamp' => date('Y-m-d H:i:s')
        );
    } else {
        $response['success'] = false;
        $response['message'] = "Wystąpił problem podczas dodawania pracownika.";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Wystąpił błąd podczas przetwarzania żądania. Spróbuj ponownie później lub skontaktuj się z pomocą techniczną (oczekiwano metody POST)."; 
}

header('Content-Type: application/json');
echo json_encode($response);
?> 