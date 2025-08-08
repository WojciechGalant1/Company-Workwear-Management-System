<?php
include_once __DIR__ . '/../../app/auth/SessionManager.php';
include_once __DIR__ . '/../../app/helpers/UrlHelper.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$baseUrl = UrlHelper::getAppBaseUrl();

$sessionManager = new SessionManager();
$sessionManager->logout();

header('Location: ' . $baseUrl . '/login');
exit;


