<?php
include_once __DIR__ . '/../services/ServiceContainer.php';

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $imie = isset($_POST['imie']) ? $_POST['imie'] : '';
    $nazwisko = isset($_POST['nazwisko']) ? $_POST['nazwisko'] : '';
    $stanowisko = isset($_POST['stanowisko']) ? $_POST['stanowisko'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    if (!empty($id) && !empty($imie) && !empty($nazwisko) && !empty($stanowisko) && $status !== '') {
        $serviceContainer = ServiceContainer::getInstance();
        $pracownikC = $serviceContainer->getController('PracownikC');

        if ($pracownikC->update($id, $imie, $nazwisko, $stanowisko, $status)) {
            $response['success'] = true;
            $response['message'] = "Pracownik został zaktualizowany pomyślnie.";
        } else {
            $response['success'] = false;
            $response['message'] = "Wystąpił problem podczas aktualizacji pracownika.";
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Wszystkie pola są wymagane.";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Wystąpił błąd podczas przetwarzania żądania. Spróbuj ponownie później lub skontaktuj się z pomocą techniczną (oczekiwano metody POST).";
}

header('Content-Type: application/json');
echo json_encode($response);
?>
