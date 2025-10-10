<?php
include_once __DIR__ . '/../services/ServiceContainer.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $stanowisko = $_POST['stanowisko'];
    $status = 1;

    $pracownik = new Pracownik($imie, $nazwisko, $stanowisko, $status);
    $serviceContainer = ServiceContainer::getInstance();
    $pracownikC = $serviceContainer->getController('PracownikC');

    if ($pracownikC->create($pracownik)) {
        $response['success'] = true;
        $response['message'] = "Pracownik został dodany pomyślnie.";
    } else {
        $response['success'] = false;
        $response['message'] = "Wystąpił problem podczas dodawania pracownika.";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Wystąpił błąd podczas przetwarzania żądania. Spróbuj ponownie później lub skontaktuj się z pomocą techniczną (oczekiwano metody POST)."; 
}

header('Content-Type: application/json');
echo json_encode($response);
?> 