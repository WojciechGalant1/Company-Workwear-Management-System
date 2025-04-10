<?php
include_once __DIR__ . '/../app/controllers/StanMagazynuC.php';

if (isset($_GET['nazwa']) && isset($_GET['rozmiar'])) {
    $nazwa = $_GET['nazwa'];
    $rozmiar = $_GET['rozmiar'];

    $stanMagazynuC = new StanMagazynuC();
    $ubranieExists = $stanMagazynuC->findByUbranieAndRozmiarByName($nazwa, $rozmiar);

    if ($ubranieExists) {
        $response = ['exists' => true];
    } else {
        $response = ['exists' => false];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
