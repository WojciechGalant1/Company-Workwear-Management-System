<?php

/**
 * Central configuration for all route mappings in the application
 */
class RouteConfig {
    /**
     * Get routes mapping clean URLs to view files
     */
    public static function getRoutes() {
        return array(
            '/' => './views/wydaj_ubranie.php',
            '/wydaj-ubranie' => './views/wydaj_ubranie.php',
            '/historia' => './views/historia_zamowien.php',
            '/historia-ubran' => './views/historia_ubran.php',
            '/historia-wydawania' => './views/historia.php',
            '/pracownicy' => './views/prac_lista.php',
            '/magazyn' => './views/mag_lista.php',
            '/dodaj-zamowienie' => './views/dodaj_zamowienie.php',
            '/raport' => './views/raport.php',
            '/dodaj-pracownika' => './views/dodaj_pracownika.php'
        );
    }
    
    /**
     * Get mapping from clean URLs to page filenames (without path)
     */
    public static function getPageMap() {
        return array(
            '/' => 'wydaj_ubranie.php',
            '/wydaj-ubranie' => 'wydaj_ubranie.php',
            '/historia' => 'historia_zamowien.php',
            '/historia-ubran' => 'historia_ubran.php',
            '/historia-wydawania' => 'historia.php',
            '/pracownicy' => 'prac_lista.php',
            '/magazyn' => 'mag_lista.php',
            '/dodaj-zamowienie' => 'dodaj_zamowienie.php',
            '/raport' => 'raport.php',
            '/dodaj-pracownika' => 'dodaj_pracownika.php'
        );
    }
    
    /**
     * Get mapping from page filenames to clean URLs
     */
    public static function getUrlMap() {
        return array(
            'historia.php' => '/historia-wydawania',
            'historia_zamowien.php' => '/historia',
            'historia_ubran.php' => '/historia-ubran',
            'prac_lista.php' => '/pracownicy',
            'mag_lista.php' => '/magazyn',
            'dodaj_zamowienie.php' => '/dodaj-zamowienie',
            'wydaj_ubranie.php' => '/wydaj-ubranie',
            'raport.php' => '/raport',
            'dodaj_pracownika.php' => '/dodaj-pracownika'
        );
    }
} 