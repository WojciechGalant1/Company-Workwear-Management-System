<?php
include_once __DIR__ . '/../services/ServiceContainer.php';
include_once __DIR__ . '/../helpers/CsrfHelper.php';
include_once __DIR__ . '/../helpers/LocalizationHelper.php';
include_once __DIR__ . '/../helpers/LanguageSwitcher.php';

// Initialize language system (compatible with routing)
$currentLanguage = LanguageSwitcher::initializeWithRouting();

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!CsrfHelper::validateToken()) {
        $response['success'] = false;
        $response['message'] = LocalizationHelper::translate('error_csrf');
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    
    $imie = isset($_POST['imie']) ? trim($_POST['imie']) : '';
    $nazwisko = isset($_POST['nazwisko']) ? trim($_POST['nazwisko']) : '';
    $stanowisko = isset($_POST['stanowisko']) ? trim($_POST['stanowisko']) : '';
    $status = 1;

    if (empty($imie) || empty($nazwisko) || empty($stanowisko)) {
        $response['success'] = false;
        $response['message'] = LocalizationHelper::translate('employee_required_fields');
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    $pracownik = new Pracownik($imie, $nazwisko, $stanowisko, $status);
    $serviceContainer = ServiceContainer::getInstance();
    $pracownikC = $serviceContainer->getController('PracownikC');

    if ($pracownikC->create($pracownik)) {
        $response['success'] = true;
        $response['message'] = LocalizationHelper::translate('employee_add_success');
    } else {
        $response['success'] = false;
        $response['message'] = LocalizationHelper::translate('error_general');
    }
} else {
    $response['success'] = false;
    $response['message'] = LocalizationHelper::translate('error_general'); 
}

header('Content-Type: application/json');
echo json_encode($response);
?> 