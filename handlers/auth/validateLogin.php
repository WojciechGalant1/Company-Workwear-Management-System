<?php
include_once __DIR__ . '/../../app/models/User.php';
include_once __DIR__ . '/../../app/database/Database.php';
include_once __DIR__ . '/../../app/auth/SessionManager.php';

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CSRF basic check 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['csrf']) && isset($_SESSION['csrf']) && $_POST['csrf'] !== $_SESSION['csrf']) {
        echo json_encode(array('status' => 'error', 'message' => 'Błąd CSRF'));
        exit;
    }
}

try {
    $db = new Database();
    $pdo = $db->getPdo();

    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $kodID = isset($_POST['kodID']) ? trim($_POST['kodID']) : '';

    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare('SELECT * FROM uzytkownicy WHERE nazwa = :username LIMIT 1');
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
        // Very basic rate-limit using session (optional)
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
        }
        $_SESSION['login_attempts']++;
        if ($_SESSION['login_attempts'] > 20) {
            usleep(500000); // 0.5s delay
        }

        $stmt = $pdo->prepare('SELECT * FROM uzytkownicy WHERE id_id = :kodID LIMIT 1');
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


