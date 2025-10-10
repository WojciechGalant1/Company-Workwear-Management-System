<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../services/ServiceContainer.php';
include_once __DIR__ . '/../helpers/CsrfHelper.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token
    if (!CsrfHelper::validateToken()) {
        $response['success'] = false;
        $response['message'] = "Błąd bezpieczeństwa. Odśwież stronę i spróbuj ponownie.";
        header("Content-Type: application/json");
        echo json_encode($response);
        exit;
    }
    
    $pracownikID = isset($_POST['pracownikID']) ? trim($_POST['pracownikID']) : '';
    $uwagi = isset($_POST['uwagi']) ? trim($_POST['uwagi']) : '';

    // Validate required fields
    if (empty($pracownikID)) {
        $response['success'] = false;
        $response['message'] = "Pracownik jest wymagany.";
        header("Content-Type: application/json");
        echo json_encode($response);
        exit;
    }

    $serviceContainer = ServiceContainer::getInstance();
    $pracownikC = $serviceContainer->getController('PracownikC');
    $pracownik = $pracownikC->getById($pracownikID);

    if (!$pracownik) {
        $response['success'] = false;
        $response['message'] = "Nie znaleziono pracownika o podanym imieniu i nazwisku.";
        header("Content-Type: application/json");
        echo json_encode($response);
        exit;
    }

    $data_wydania_obj = new DateTime();

    $current_user_id = $_SESSION['user_id'];

    $userC = $serviceContainer->getController('UserC');
    $currentUser = $userC->getUserById($current_user_id);

    if (!$currentUser) {
        $response['success'] = false;
        $response['message'] = "Nie znaleziono zalogowanego użytkownika.";
        header("Content-Type: application/json");
        echo json_encode($response);
        exit;
    }

    $wydaniaC = $serviceContainer->getController('WydaniaC');
    $wydaneUbraniaC = $serviceContainer->getController('WydaneUbraniaC');
    $stanMagazynuC = $serviceContainer->getController('StanMagazynuC');

    $wydanie = new Wydania($current_user_id, $pracownik['id_pracownik'], $data_wydania_obj, $uwagi);
    $id_wydania = $wydaniaC->create($wydanie);

    $all_items_valid = true;

    // Validate ubrania data
    if (!isset($_POST['ubrania']) || !is_array($_POST['ubrania'])) {
        $response['success'] = false;
        $response['message'] = "Brak danych o ubraniach.";
        header("Content-Type: application/json");
        echo json_encode($response);
        exit;
    }

    foreach ($_POST['ubrania'] as $ubranie) {
        $idUbrania = isset($ubranie['id_ubrania']) ? intval($ubranie['id_ubrania']) : 0;
        $idRozmiar = isset($ubranie['id_rozmiar']) ? intval($ubranie['id_rozmiar']) : 0;
        $ilosc = isset($ubranie['ilosc']) ? intval($ubranie['ilosc']) : 0;
        
        $iloscDostepna = $stanMagazynuC->getIlosc($idUbrania, $idRozmiar);

        if ($idUbrania == 0 || $idRozmiar == 0) {
            $response['success'] = false;
            $response['message'] = "Kod nie został wprowadzony lub został wprowadzony niepoprawnie";
            $all_items_valid = false;
            break;
        }

        if ($ilosc <= 0) {
            $response['success'] = false;
            $response['message'] = "Ilość musi być większa od zera";
            $all_items_valid = false;
            break;
        }

        if ($ilosc > $iloscDostepna) {
            $response['success'] = false;
            $response['message'] = "Nie można wydać więcej ubrań niż jest dostępnych w magazynie lub kod wprowadzono niepoprawnie";
            $all_items_valid = false;
            break;
        }
    }

    if ($all_items_valid) {
        foreach ($_POST['ubrania'] as $ubranie) {
            $idUbrania = intval($ubranie['id_ubrania']);
            $idRozmiar = intval($ubranie['id_rozmiar']);
            $ilosc = intval($ubranie['ilosc']);
            $status = 1;

            $data_waznosci_miesiace = isset($ubranie['data_waznosci']) ? intval($ubranie['data_waznosci']) : 0;
            $data_waznosci_obj = new DateTime();
            $data_waznosci_obj->modify("+{$data_waznosci_miesiace} months");
            $data_waznosci = $data_waznosci_obj->format('Y-m-d H:i:s');

            $wydaneUbrania = new WydaneUbrania($data_waznosci, $id_wydania, $idUbrania, $idRozmiar, $ilosc, $status);
            if ($wydaneUbraniaC->create($wydaneUbrania)) {
                $stanMagazynuC->updateIlosc($idUbrania, $idRozmiar, $ilosc);
            } else {
                $response['success'] = false;
                $response['message'] = "Wystąpił problem podczas wydawania ubrania.";
                break;
            }
        }
        if (!isset($response['success']) || $response['success'] !== false) {
            $response['success'] = true;
            $response['message'] = "Ubrania zostały wydane pomyślnie, stan magazynu został zaktualizowany.";
        }
    }
} else {
    $response['success'] = false;
    $response['message'] = "Wystąpił błąd podczas przetwarzania żądania. Spróbuj ponownie później lub skontaktuj się z pomocą techniczną (oczekiwano metody POST).";
}

header("Content-Type: application/json");
echo json_encode($response);
?>