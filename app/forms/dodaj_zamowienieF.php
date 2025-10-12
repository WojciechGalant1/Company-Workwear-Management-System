<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../services/ServiceContainer.php';
include_once __DIR__ . '/../helpers/CsrfHelper.php';
include_once __DIR__ . '/../helpers/LocalizationHelper.php';
include_once __DIR__ . '/../helpers/LanguageSwitcher.php';

// Initialize language system (compatible with routing)
$currentLanguage = LanguageSwitcher::initializeWithRouting();

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token
    if (!CsrfHelper::validateToken()) {
        $response['success'] = false;
        $response['message'] = LocalizationHelper::translate('error_csrf');
        header("Content-Type: application/json");
        echo json_encode($response);
        exit;
    }
    
    $data_zamowienia_obj = new DateTime();
    $status = 1;
    $uwagi = isset($_POST['uwagi']) ? trim($_POST['uwagi']) : '';

    $current_user_id = $_SESSION['user_id'];
    $serviceContainer = ServiceContainer::getInstance();
    $userC = $serviceContainer->getController('UserC');
    $currentUser = $userC->getUserById($current_user_id);

    if (!$currentUser) {
        $response['success'] = false;
        $response['message'] = LocalizationHelper::translate('error_user_not_found');
        header("Content-Type: application/json");
        echo json_encode($response);
        exit;
    }

    $zamowienie = new HistoriaZamowien($data_zamowienia_obj, $current_user_id, $uwagi, $status);
    $zamowienieC = $serviceContainer->getController('HistoriaZamowienC');
    $szczegolyZamowieniaC = $serviceContainer->getController('SzczegolyZamowieniaC');
    $kodC = $serviceContainer->getController('KodC');

    if ($zamowienieC->create($zamowienie)) {
        $zamowienieId = $zamowienieC->getLastInsertId();
        $zamowienie->setId($zamowienieId); 
        $ubrania = isset($_POST['ubrania']) ? $_POST['ubrania'] : array();

        if (!empty($ubrania) && is_array($ubrania)) {
            foreach ($ubrania as $ubranie) {
                $nazwa = isset($ubranie['nazwa']) ? trim($ubranie['nazwa']) : '';
                $rozmiar = isset($ubranie['rozmiar']) ? trim($ubranie['rozmiar']) : '';
                $firma = isset($ubranie['firma']) ? trim($ubranie['firma']) : '';
                $ilosc = isset($ubranie['ilosc']) ? intval($ubranie['ilosc']) : 0;
                $iloscMin = isset($ubranie['iloscMin']) ? intval($ubranie['iloscMin']) : 0; 
                $kod_nazwa = isset($ubranie['kod']) ? trim($ubranie['kod']) : '';

                // Basic validation
                if (empty($nazwa) || empty($rozmiar) || empty($firma) || $ilosc <= 0) {
                    $response['success'] = false;
                    $response['message'] = LocalizationHelper::translate('order_required_fields');
                    echo json_encode($response);
                    exit;
                }

                $ubranieC = $serviceContainer->getController('UbranieC');
                $rozmiarC = $serviceContainer->getController('RozmiarC');

                $idUbrania = $ubranieC->firstOrCreate(new Ubranie($nazwa));
                $idRozmiaru = $rozmiarC->firstOrCreate(new Rozmiar($rozmiar));

                $kod = $kodC->findKodByNazwa($kod_nazwa);  

                if (!$kod) {
                    $nowyKod = new Kod($kod_nazwa, $idUbrania, $idRozmiaru, $status); 
                    $kodId = $kodC->create($nowyKod);  
                } else {
                    $kodId = $kod->getIdKod();
                }

                $szczegol = new SzczegolyZamowienia($zamowienieId, $idUbrania, $idRozmiaru, $ilosc, $iloscMin, $firma, $kodId);

                if (!$szczegolyZamowieniaC->create($szczegol)) {
                    $response['success'] = false;
                    $response['message'] = LocalizationHelper::translate('order_details_error');
                    echo json_encode($response);
                    exit;
                }
            }
        } else {
            $response['success'] = false;
            $response['message'] = LocalizationHelper::translate('order_no_items');
            echo json_encode($response);
            exit;
        }

        if ($status == 1) {
            $zamowienieC->dodajDoMagazynu($zamowienie);
        }

        $response['success'] = true;
        $response['message'] = LocalizationHelper::translate('order_add_success');
    } else {
        $response['success'] = false;
        $response['message'] = LocalizationHelper::translate('order_create_error');
    }
} else {
    $response['success'] = false;
    $response['message'] = LocalizationHelper::translate('error_general');
}

header('Content-Type: application/json');
echo json_encode($response);
?>
