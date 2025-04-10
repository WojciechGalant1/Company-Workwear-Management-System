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
            '/' => './app/views/wydaj_ubranie.php',
            '/wydaj-ubranie' => './app/views/wydaj_ubranie.php',
            '/historia' => './app/views/historia_zamowien.php',
            '/historia-ubran' => './app/views/historia_ubran.php',
            '/historia-wydawania' => './app/views/historia.php',
            '/pracownicy' => './app/views/prac_lista.php',
            '/magazyn' => './app/views/mag_lista.php',
            '/dodaj-zamowienie' => './app/views/dodaj_zamowienie.php',
            '/raport' => './app/views/raport.php',
            '/dodaj-pracownika' => './app/views/dodaj_pracownika.php'
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