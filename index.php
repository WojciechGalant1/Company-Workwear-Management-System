<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Include router and route configuration
require_once './app/Router.php';
require_once './app/config/RouteConfig.php';

// Initialize router
$router = new Router();

// Add routes from configuration
$routes = RouteConfig::getRoutes();
foreach ($routes as $path => $viewFile) {
    $router->add($path, $viewFile);
}

// 404 
$router->setNotFound(function() {
    include_once './layout/header.php';
    echo '<div class="container mt-5">';
    echo '<div class="alert alert-danger" role="alert">';
    echo '<h4 class="alert-heading">Strona nie znaleziona!</h4>';
    echo '<p>Przepraszamy, ale strona której szukasz nie istnieje.</p>';
    echo '<hr>';
    echo '<p class="mb-0"><a href="/" class="btn btn-primary">Wróć do strony głównej</a></p>';
    echo '</div>';
    echo '</div>';
    include_once './layout/footer.php';
});

// Dispatch request
try {
    $uri = $_SERVER['REQUEST_URI'];
    $router->dispatch($uri);
} catch (Exception $e) {
    // Log the error
    error_log($e->getMessage());
    
    //error page
    include_once './layout/header.php';
    echo '<div class="container mt-5">';
    echo '<div class="alert alert-danger" role="alert">';
    echo '<h4 class="alert-heading">Wystąpił błąd!</h4>';
    echo '<p>Przepraszamy, wystąpił błąd podczas przetwarzania żądania.</p>';
    echo '</div>';
    echo '</div>';
    include_once './layout/footer.php';
}
?>