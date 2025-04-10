<?php
include_once __DIR__ . '/../controllers/PracownikC.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $stanowisko = $_POST['stanowisko'];
    $status = 1;

    $pracownik = new Pracownik($imie, $nazwisko, $stanowisko, $status);
    $pracownikC = new PracownikC();

    if ($pracownikC->create($pracownik)) {
        $response['success'] = true;
        $response['message'] = "Pracownik został dodany pomyślnie.";
    } else {
        $response['success'] = false;
        $response['message'] = "Wystąpił problem podczas dodawania pracownika.";
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?> 