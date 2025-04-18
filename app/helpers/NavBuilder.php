<?php

class NavBuilder {
    /**
     *  navigation item with active state
     */
    public static function navItem($url, $label, $activeUri, $baseUrl, $extraClass = '') {
        $isActive = ($activeUri === $url) ? 'active' : '';
        $extraClass = $extraClass ? ' ' . $extraClass : '';
        
        return '<li class="nav-item">
                    <a class="nav-link' . $extraClass . ' ' . $isActive . '" href="' . $baseUrl . $url . '">' . $label . '</a>
                </li>';
    }
    

    public static function separator() {
        return '<a class="nav-link text-light">|</a>';
    }
    
    /**
     * Group navigation items by user status level
     */
    public static function buildNavGroups($activeUri, $baseUrl, $userStatus, $hasShortages = false) {
        $output = '';
        
        if ($userStatus >= 3) {
            $output .= self::navItem('/dodaj-zamowienie', 'Dodaj zamówienie', $activeUri, $baseUrl);
            $output .= self::navItem('/historia', 'Historia zamówień', $activeUri, $baseUrl);
        }
        
        if ($userStatus >= 1) {
            $output .= self::separator();
            $output .= self::navItem('/wydaj-ubranie', 'Wydaj ubrania', $activeUri, $baseUrl);
        }
        
        if ($userStatus >= 3) {
            $shortageCls = $hasShortages ? 'text-danger fw-bold text-uppercase' : '';
            $output .= self::navItem('/magazyn', 'Stany magazynowe', $activeUri, $baseUrl, $shortageCls);
        }
        
        if ($userStatus >= 5) {
            $output .= self::navItem('/historia-wydawania', 'Historia wydawania', $activeUri, $baseUrl);
            $output .= self::navItem('/historia-ubran', 'Historia ubrań', $activeUri, $baseUrl);
            $output .= self::navItem('/raport', 'Raport wydawania', $activeUri, $baseUrl);
            $output .= self::separator();
            $output .= self::navItem('/dodaj-pracownika', 'Dodaj pracownika', $activeUri, $baseUrl);
            $output .= self::navItem('/pracownicy', 'Lista pracowników', $activeUri, $baseUrl);
        }
        
        $output .= self::separator();
        $output .= '<li class="nav-item">
                        <a class="nav-link text-warning" href="' . $baseUrl . '/log/sesja/logout.php">
                            Wyloguj
                        </a>
                    </li>';
        
        return $output;
    }
} 