<?php
header("Content-Type: application/json; charset=UTF-8");

include_once __DIR__ . '/../app/controllers/WydaneUbraniaC.php';

try {
    $wydaneUbraniaC = new WydaneUbraniaC();
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->id, $data->currentStatus)) {
        throw new Exception('Invalid input data');
    }

    $id = $data->id;
    $currentStatus = $data->currentStatus;
    $newStatus = ($currentStatus == 1) ? 0 : 1;

    if ($wydaneUbraniaC->updateStatus($id, $newStatus)) {
        echo json_encode(['success' => true, 'newStatus' => $newStatus]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
