<?php

include_once 'SessionManager.php';

header('Content-Type: application/json');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=ubrania', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sessionManager = new SessionManager();
    $userId = $sessionManager->getUserId();

    if ($userId) {

        $sessionManager->logout();

        echo json_encode(['status' => 'success', 'message' => 'Wylogowano poprawnie']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Nie jesteś zalogowany']);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
}

header("Location: ../logowanie.php");

?>
