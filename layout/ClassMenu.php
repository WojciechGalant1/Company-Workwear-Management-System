<?php

include_once __DIR__ . '/../app/controllers/StanMagazynuC.php'; 
include_once __DIR__ . '/../app/auth/SessionManager.php';
include_once __DIR__ . '/../app/helpers/UrlHelper.php';
include_once __DIR__ . '/../app/helpers/NavBuilder.php';

class ClassMenu {
    public function navBar($currentPage) {
        
        $sessionManager = new SessionManager();
        $userStatus = $sessionManager->getUserStatus(); 

        $stanMagazynuC = new StanMagazynuC();
        $hasShortages = $stanMagazynuC->checkIlosc();

        // Get base URL and current URI
        $baseUrl = UrlHelper::getBaseUrl();
        $uri = UrlHelper::getCleanUri();
        
        // Get active URI for highlighting
        $activeUri = UrlHelper::getCleanUrl($currentPage);
        
        echo '
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
            <div class="container-fluid">
                <ul class="navbar-nav ms-2 d-flex align-items-center">    
                    <a class="navbar-brand" href="' . $baseUrl . '/">
                        <img src="' . $baseUrl . '/img/protectve-equipment.png" class="logo-image" alt="Logo" height="30">
                    </a>';
        
        // navigation items based on user status
        echo NavBuilder::buildNavGroups($activeUri, $baseUrl, $userStatus, $hasShortages);
                
        echo '
                </ul>
            </div>
        </nav>';
    }
}
?>
