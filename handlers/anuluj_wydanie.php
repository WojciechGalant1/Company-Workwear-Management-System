<?php
include_once __DIR__ . '/../app/controllers/WydaneUbraniaC.php';
include_once __DIR__ . '/../app/controllers/StanMagazynuC.php';
include_once __DIR__ . '/../app/controllers/WydaniaC.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id']) || !is_numeric($data['id'])) {
        throw new Exception('Nieprawidłowe dane wejściowe.');
    }

    $ubranieId = $data['id'];

    $wydaneUbraniaC = new WydaneUbraniaC();
    $stanMagazynuC = new StanMagazynuC();
    $wydaniaC = new WydaniaC();

    $wydaneUbranie = $wydaneUbraniaC->getUbraniaById($ubranieId);
    if (!$wydaneUbranie) {
        throw new Exception('Nie znaleziono wydanego ubrania.');
    }

    $idWydania = $wydaneUbranie['id_wydania'];
    $ilosc = $wydaneUbranie['ilosc'];
    $idUbrania = $wydaneUbranie['id_ubrania'];
    $idRozmiaru = $wydaneUbranie['id_rozmiaru'];

    if ($wydaneUbraniaC->deleteWydaneUbranieStatus($ubranieId)) {
        $stanMagazynuC->updateIlosc($idUbrania, $idRozmiaru, $ilosc, true);
        /* 
        $pozostaleUbrania = $wydaneUbraniaC->getUbraniaByWydanieId($idWydania);
        if (empty($pozostaleUbrania)) {
            $wydaniaC->deleteWydanie($idWydania);
        }
 */
        echo json_encode(['success' => true]);
    } else {
        throw new Exception('Nie udało się anulować wydania.');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}
