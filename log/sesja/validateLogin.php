<?php
include_once '../../app/models/User.php';
include_once '../../app/database/Database.php';
include_once 'SessionManager.php';

header('Content-Type: application/json');

try {
    $db = new Database();
    $pdo = $db->getPdo(); 

    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $kodID = isset($_POST['kodID']) ? trim($_POST['kodID']) : '';

    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare('SELECT * FROM uzytkownicy WHERE nazwa = :username');
        $stmt->execute(array(':username' => $username));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $hashed_password = $user['password'];
            if (crypt($password, $hashed_password) == $hashed_password) {
                $sessionManager = new SessionManager();
                $sessionManager->login($user['id'], $user['status']);

                echo json_encode(array('status' => 'success', 'message' => 'Poprawne dane'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Błędne dane 0'));
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Błędne dane'));
        }
    } elseif (!empty($kodID)) {
        $stmt = $pdo->prepare('SELECT * FROM uzytkownicy WHERE id_id = :kodID');
        $stmt->execute(array(':kodID' => $kodID));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $sessionManager = new SessionManager();
            $sessionManager->login($user['id'], $user['status']);

            echo json_encode(array('status' => 'success', 'message' => 'Poprawne dane'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Błędny kod'));
        }
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Nie podano danych logowania'));
    }

} catch (PDOException $e) {
    echo json_encode(array('status' => 'error', 'message' => 'Connection failed: ' . $e->getMessage()));
}
?>
