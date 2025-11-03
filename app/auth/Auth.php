<?php
include_once __DIR__ . '/../services/ServiceContainer.php';
include_once __DIR__ . '/../helpers/UrlHelper.php';
include_once __DIR__ . '/../helpers/LocalizationHelper.php';
include_once __DIR__ . '/../helpers/LanguageSwitcher.php';

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
    $userController = $serviceContainer->getController('UserController');
    $user = $userController->getUserById($_SESSION['user_id']);

    if (!$user || $user['status'] < $requiredStatus) {
        // Initialize language if not already set
        if (!isset($_SESSION['current_language'])) {
            LanguageSwitcher::initializeWithRouting();
        }
        $currentLanguage = LanguageSwitcher::getCurrentLanguage();
        LocalizationHelper::setLanguage($currentLanguage);
        
        echo '<div class="alert alert-danger text-center">' . LocalizationHelper::translate('access_denied') . '</div>';
        die();
    }
}
?>


