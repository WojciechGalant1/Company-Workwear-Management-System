<?php
include_once __DIR__ . '/../controllers/UserC.php';

function checkAccess($requiredStatus) {

    if (!isset($_SESSION['user_id'])) {
        header("Location: ./log/logowanie.php");
        exit();
    }

    $userController = new UserC();
    $user = $userController->getUserById($_SESSION['user_id']);

    if (!$user || $user['status'] < $requiredStatus) {
        echo '<div class="alert alert-danger text-center">Nie masz uprawnień do tej strony.</div>';
        die();
    }
    
}
?>
