<?php
session_start();
include_once __DIR__ . '/../controllers/HistoriaZamowienC.php';
include_once __DIR__ . '/../controllers/SzczegolyZamowieniaC.php';
include_once __DIR__ . '/../controllers/UbranieC.php';
include_once __DIR__ . '/../controllers/RozmiarC.php';
include_once __DIR__ . '/../controllers/KodC.php';
include_once __DIR__ . '/../controllers/UserC.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data_zamowienia_obj = new DateTime();
    $status = 1;
    $uwagi = isset($_POST['uwagi']) ? $_POST['uwagi'] : '';

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

    $zamowienie = new HistoriaZamowien($data_zamowienia_obj, $current_user_id, $uwagi, $status);
    $zamowienieC = new HistoriaZamowienC();
    $szczegolyZamowieniaC = new SzczegolyZamowieniaC();
    $kodC = new KodC();


    if ($zamowienieC->create($zamowienie)) {
        $zamowienieId = $zamowienieC->getLastInsertId();
        $zamowienie->setId($zamowienieId); 
        $ubrania = isset($_POST['ubrania']) ? $_POST['ubrania'] : array();

        if (!empty($ubrania) && is_array($ubrania)) {
            foreach ($ubrania as $ubranie) {
                $nazwa = $ubranie['nazwa'];
                $rozmiar = $ubranie['rozmiar'];
                $firma = $ubranie['firma'];
                $ilosc = $ubranie['ilosc'];
                $iloscMin = isset($ubranie['iloscMin']) ? $ubranie['iloscMin'] : 0; 
                $kod_nazwa = $ubranie['kod'];

                $ubranieC = new UbranieC();
                $rozmiarC = new RozmiarC();

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
                    $response['message'] = "Wystąpił problem podczas dodawania szczegółów zamówienia.";
                    echo json_encode($response);
                    exit;
                }
            }
        } else {
            $response['success'] = false;
            $response['message'] = "Brak danych o ubraniach w zamówieniu.";
            echo json_encode($response);
            exit;
        }

        if ($status == 1) {
            $zamowienieC->dodajDoMagazynu($zamowienie);
        }

        $response['success'] = true;
        $response['message'] = "Zamówienie dodano pomyślnie, i stan magazynu został zaktualizowany.";
    } else {
        $response['success'] = false;
        $response['message'] = "Wystąpił problem podczas dodawania zamówienia.";
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
