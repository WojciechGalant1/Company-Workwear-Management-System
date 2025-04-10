<?php
header("Content-Type: application/json; charset=UTF-8");

include_once __DIR__ . '/../app/controllers/WydaneUbraniaC.php';

$wydaneUbraniaC = new WydaneUbraniaC();
$data = json_decode(file_get_contents("php://input"));
$id = $data->id;
$currentStatus = $data->currentStatus;

if ($currentStatus === 1) {
    $newStatus = 0;
} else {
    $newStatus = $currentStatus == 1 ? 0 : 1;
}

if ($wydaneUbraniaC->updateStatus($id, $newStatus)) {
    echo json_encode(['success' => true, 'newStatus' => $newStatus]);
} else {
    echo json_encode(['success' => false]);
}
