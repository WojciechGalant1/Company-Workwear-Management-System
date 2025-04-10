<?php
require_once __DIR__ . '/../config/RouteConfig.php';

class UrlHelper {
    /**
     * Get base URL for the application
     */
    public static function getBaseUrl() {
        $basePath = dirname($_SERVER['SCRIPT_NAME']);
        return $basePath === '/' ? '' : $basePath;
    }
    
    /**
     * Get clean URI without query string and basePath
     */
    public static function getCleanUri() {
        $uri = $_SERVER['REQUEST_URI'];
        
        // Remove query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        // Remove basePath if it exists
        $basePath = dirname($_SERVER['SCRIPT_NAME']);
        if ($basePath !== '/' && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        
        // Default path for home page
        if ($uri === '' || $uri === '/') {
            $uri = '/wydaj-ubranie';
        }
        
        return $uri;
    }
    
    /**
     * Get current page from URI using route mapping
     */
    public static function getCurrentPage($uri = null) {
        if ($uri === null) {
            $uri = self::getCleanUri();
        }
        
        // Use centralized route configuration
        $pageMap = RouteConfig::getPageMap();
        
        return isset($pageMap[$uri]) ? $pageMap[$uri] : basename($_SERVER['PHP_SELF']);
    }
    
    /**
     * Convert file name to clean URL
     */
    public static function getCleanUrl($fileName) {
        // Use centralized route configuration
        $urlMap = RouteConfig::getUrlMap();
        
        return isset($urlMap[$fileName]) ? $urlMap[$fileName] : $fileName;
    }
} 