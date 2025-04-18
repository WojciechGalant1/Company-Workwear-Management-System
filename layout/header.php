<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include helpers
include_once __DIR__ . '/../app/helpers/UrlHelper.php';

// Get base URL for assets and current URI
$baseUrl = UrlHelper::getBaseUrl();
$uri = UrlHelper::getCleanUri();

// Get current page for module loading
$current_page = UrlHelper::getCurrentPage($uri);

echo '
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="base-url" content="' . $baseUrl . '">
    <title>Ubrania</title>
    <link rel="icon" href="' . $baseUrl . '/img/protectve-equipment.png" type="image/png">
    <link rel="stylesheet" href="' . $baseUrl . '/styl/css/custom.css">
    <link rel="stylesheet" href="' . $baseUrl . '/layout/navbar.css">
    <link rel="stylesheet" href="' . $baseUrl . '/styl/bootstrap-select/css/bootstrap-select.css">

    <script src="' . $baseUrl . '/styl/js/jquery-3.3.1.min.js"></script>
    <script src="' . $baseUrl . '/styl/js/jquery-ui-1.13.1.min.js"></script>

    <script src="' . $baseUrl . '/styl/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="' . $baseUrl . '/styl/bootstrap-select/js/bootstrap-select.js"></script>
    <link rel="stylesheet" href="' . $baseUrl . '/styl/bootstrap/icons/bootstrap-icons.css">
    <link rel="stylesheet" href="' . $baseUrl . '/styl/DataTables/datatables.css">
    <script src="' . $baseUrl . '/styl/DataTables/datatables.min.js"></script>
</head>
<style>
    .tooltip-inner {
        font-size: 1.2rem;
    }
</style>
';

include_once 'ClassMenu.php';
$nav = new ClassMenu();

// Load modules configuration
$modulesConfig = include __DIR__ . '/../app/config/modules.php';

// Get required modules for current page
$modules = isset($modulesConfig[$current_page]) ? $modulesConfig[$current_page] : $modulesConfig['default'];

echo "<body data-modules='$modules'>";
$nav->navBar($current_page);
echo '<div class="container border border-secondary border-opacity-50 mt-5 shadow mb-5 p-4 bg-body rounded">';

if (!isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-info text-center">Nie jesteś zalogowany.</div>
    <div class="text-center">Zaloguj się do systemu!<a target="_blank" href="' . $baseUrl . '/log/in/">ZALOGUJ</a></div>';
    header("Location: " . $baseUrl . "/log/logowanie.php");
    die();
}

?>
