<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include helpers
include_once __DIR__ . '/../app/helpers/UrlHelper.php';
include_once __DIR__ . '/../app/helpers/CsrfHelper.php';
include_once __DIR__ . '/../app/helpers/LocalizationHelper.php';
include_once __DIR__ . '/../app/helpers/LanguageSwitcher.php';

// Initialize language system
$currentLanguage = LanguageSwitcher::initialize();

// Get base URL for assets and current URI
$baseUrl = UrlHelper::getBaseUrl();
$uri = UrlHelper::getCleanUri();

// Get current page for module loading
$current_page = UrlHelper::getCurrentPage($uri);

// Access control: redirect unauthenticated users before any output
if (!isset($_SESSION['user_id'])) {
    header("Location: " . $baseUrl . "/login");
    exit;
}

// Get CSRF token for JavaScript
$csrfToken = CsrfHelper::getToken();
if (!$csrfToken) {
    $csrfToken = CsrfHelper::generateToken();
}

// Translation helper function for templates
function __($key, $params = array()) {
    return LocalizationHelper::translate($key, $params);
}

echo '
<!DOCTYPE html>
<html lang="' . $currentLanguage . '">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="base-url" content="' . $baseUrl . '">
    <meta name="csrf-token" content="' . htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') . '">
    <meta name="current-language" content="' . $currentLanguage . '">
    <title>' . __('app_title') . '</title>
    <link rel="icon" href="' . $baseUrl . '/img/protective-equipement.png" type="image/png">
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
    .language-switcher {
        position: fixed;
        top: 10px;
        right: 10px;
        z-index: 1000;
    }
    .language-links {
        position: fixed;
        top: 10px;
        right: 10px;
        z-index: 1000;
    }
    .language-links .lang-link {
        display: inline-block;
        margin-left: 5px;
        padding: 5px 10px;
        text-decoration: none;
        border-radius: 3px;
        background: #f8f9fa;
        color: #495057;
        font-size: 0.9em;
    }
    .language-links .lang-link.active {
        background: #007bff;
        color: white;
    }
    .language-links .lang-link:hover {
        background: #e9ecef;
        color: #495057;
    }
    .language-links .lang-link.active:hover {
        background: #0056b3;
        color: white;
    }
</style>
';

include_once 'ClassMenu.php';
$nav = new ClassMenu();

// Load modules configuration
$modulesConfig = include __DIR__ . '/../app/config/modules.php';

// Get required modules for current page
$modules = isset($modulesConfig[$current_page]) ? $modulesConfig[$current_page] : $modulesConfig['default'];

$containerId = ($uri === '/historia-wydawania') ? 'id="historia-page"' : '';
echo "<body data-modules='$modules'>";

// Add language switcher
echo LanguageSwitcher::generateSimpleLinks();

$nav->navBar($current_page);
echo '
<div ' . $containerId . ' class="container border border-secondary border-opacity-50 mt-5 shadow mb-5 p-4 bg-body rounded">';

// user is authenticated at this point

?>
