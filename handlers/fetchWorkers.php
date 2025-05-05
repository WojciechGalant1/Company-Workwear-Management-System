<?php
include_once __DIR__ . '/../app/controllers/PracownikC.php';

$query = isset($_GET['query']) ? $_GET['query'] : '';

$pracownikC = new PracownikC();
$pracownicy = $pracownikC->searchByName($query);

header('Content-Type: application/json');
echo json_encode($pracownicy);
?>