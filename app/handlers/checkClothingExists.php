<?php
header('Content-Type: application/json');
include_once __DIR__ . '/../services/ServiceContainer.php';
include_once __DIR__ . '/../helpers/LocalizationHelper.php';
include_once __DIR__ . '/../helpers/LanguageSwitcher.php';

// Initialize language system
LanguageSwitcher::initializeWithRouting();

try {
    if (isset($_GET['nazwa']) && isset($_GET['rozmiar'])) {
        $nazwa = $_GET['nazwa'];
        $rozmiar = $_GET['rozmiar'];

        $serviceContainer = ServiceContainer::getInstance();
        $stanMagazynuC = $serviceContainer->getController('WarehouseController');
        $ubranieExists = $stanMagazynuC->findByUbranieAndRozmiarByName($nazwa, $rozmiar);

        $response = ['exists' => (bool)$ubranieExists];
        echo json_encode($response);
    } else {
        echo json_encode(['success' => false, 'error' => LocalizationHelper::translate('validation_required')]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

