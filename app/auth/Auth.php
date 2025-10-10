<?php
include_once __DIR__ . '/../services/ServiceContainer.php';
include_once __DIR__ . '/../helpers/UrlHelper.php';

function checkAccess($requiredStatus) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        $baseUrl = UrlHelper::getBaseUrl();
        header('Location: ' . $baseUrl . '/login');
        exit();
    }

    $serviceContainer = ServiceContainer::getInstance();
    $userController = $serviceContainer->getController('UserC');
    $user = $userController->getUserById($_SESSION['user_id']);

    if (!$user || $user['status'] < $requiredStatus) {
        echo '<div class="alert alert-danger text-center">Nie masz uprawnień do tej strony.</div>';
        die();
    }
}
?>


