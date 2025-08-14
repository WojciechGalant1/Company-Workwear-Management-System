<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../controllers/PracownikC.php';
include_once __DIR__ . '/../controllers/UbranieC.php';
include_once __DIR__ . '/../controllers/RozmiarC.php';
include_once __DIR__ . '/../controllers/WydaniaC.php';
include_once __DIR__ . '/../controllers/WydaneUbraniaC.php';
include_once __DIR__ . '/../controllers/StanMagazynuC.php';
include_once __DIR__ . '/../controllers/UserC.php';

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pracownikID = isset($_POST['pracownikID']) ? $_POST['pracownikID'] : '';
    $uwagi = isset($_POST['uwagi']) ? $_POST['uwagi'] : '';

    $pracownikC = new PracownikC();
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

    $dbRstUsers = new UserC();
    $currentUser = $dbRstUsers->getUserById($current_user_id);

    if (!$currentUser) {
        $response['success'] = false;
        $response['message'] = "Nie znaleziono zalogowanego użytkownika.";
        header("Content-Type: application/json");
        echo json_encode($response);
        exit;
    }

    $wydaniaC = new WydaniaC();
    $wydaneUbraniaC = new WydaneUbraniaC();
    $stanMagazynuC = new StanMagazynuC();

    $wydanie = new Wydania($current_user_id, $pracownik['id_pracownik'], $data_wydania_obj, $uwagi);
    $id_wydania = $wydaniaC->create($wydanie);

    $all_items_valid = true;

    foreach ($_POST['ubrania'] as $ubranie) {
        $idUbrania = isset($ubranie['id_ubrania']) ? $ubranie['id_ubrania'] : null;
        $idRozmiar = isset($ubranie['id_rozmiar']) ? $ubranie['id_rozmiar'] : null;

        //$kod = isset($ubranie['kod']) ? $ubranie['kod'] : null;
        $ilosc = isset($ubranie['ilosc']) ? $ubranie['ilosc'] : 0;
        $iloscDostepna = $stanMagazynuC->getIlosc($idUbrania, $idRozmiar);

        if ($idUbrania == 0 || $idRozmiar == 0) {
            $response['success'] = false;
            $response['message'] = "Kod nie został wprowadzony lub został wprowadzony niepoprawnie";
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
            $idUbrania = $ubranie['id_ubrania'];
            $idRozmiar = $ubranie['id_rozmiar'];
            $ilosc = $ubranie['ilosc'];
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
        $response['success'] = true;
        $response['message'] = "Ubrania zostały wydane pomyślnie, stan magazynu został zaktualizowany.";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Wystąpił błąd podczas przetwarzania żądania. Spróbuj ponownie później lub skontaktuj się z pomocą techniczną (oczekiwano metody POST).";
}

header("Content-Type: application/json");
echo json_encode($response);
