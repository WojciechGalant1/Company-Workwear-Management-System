<?php
header('Content-Type: application/json');
include_once __DIR__ . '/../app/controllers/StanMagazynuC.php';

try {
    if (isset($_GET['nazwa']) && isset($_GET['rozmiar'])) {
        $nazwa = $_GET['nazwa'];
        $rozmiar = $_GET['rozmiar'];

        $stanMagazynuC = new StanMagazynuC();
        $ubranieExists = $stanMagazynuC->findByUbranieAndRozmiarByName($nazwa, $rozmiar);

        $response = ['exists' => (bool)$ubranieExists];
        echo json_encode($response);
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing required parameters: nazwa and rozmiar']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
