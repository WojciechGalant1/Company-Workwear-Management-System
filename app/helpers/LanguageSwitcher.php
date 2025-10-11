<?php

/**
 * Language Switcher Helper
 * Handles language switching and persistence
 */
class LanguageSwitcher {
    
    const SESSION_KEY = 'selected_language';
    const COOKIE_KEY = 'language_preference';
    const DEFAULT_LANGUAGE = 'en';
    const COOKIE_EXPIRE = 31536000; // 1 year
    
    /**
     * Initialize language from session, cookie, or default
     * @return string Current language code
     */
    public static function initialize() {
        $language = self::DEFAULT_LANGUAGE;
        
        // Check if language is set in URL parameter
        if (isset($_GET['lang']) && self::isValidLanguage($_GET['lang'])) {
            $language = $_GET['lang'];
            self::setLanguage($language);
        }
        // Check session
        elseif (isset($_SESSION[self::SESSION_KEY])) {
            $language = $_SESSION[self::SESSION_KEY];
        }
        // Check cookie
        elseif (isset($_COOKIE[self::COOKIE_KEY])) {
            $language = $_COOKIE[self::COOKIE_KEY];
            self::setLanguage($language);
        }
        // Use browser language detection
        else {
            $browserLang = self::detectBrowserLanguage();
            if ($browserLang) {
                $language = $browserLang;
                self::setLanguage($language);
            }
        }
        
        return $language;
    }
    
    /**
     * Set the current language
     * @param string $language Language code
     */
    public static function setLanguage($language) {
        if (!self::isValidLanguage($language)) {
            $language = self::DEFAULT_LANGUAGE;
        }
        
        $_SESSION[self::SESSION_KEY] = $language;
        setcookie(self::COOKIE_KEY, $language, time() + self::COOKIE_EXPIRE, '/');
        
        // Initialize localization with the new language
        LocalizationHelper::setLanguage($language);
    }
    
    /**
     * Get current language
     * @return string
     */
    public static function getCurrentLanguage() {
        return isset($_SESSION[self::SESSION_KEY]) ? $_SESSION[self::SESSION_KEY] : self::DEFAULT_LANGUAGE;
    }
    
    /**
     * Check if language is valid
     * @param string $language Language code
     * @return bool
     */
    public static function isValidLanguage($language) {
        $availableLanguages = LocalizationHelper::getAvailableLanguages();
        return in_array($language, $availableLanguages);
    }
    
    /**
     * Detect browser language preference
     * @return string|null
     */
    public static function detectBrowserLanguage() {
        if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return null;
        }
        
        $languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        $availableLanguages = LocalizationHelper::getAvailableLanguages();
        
        foreach ($languages as $lang) {
            $lang = trim(explode(';', $lang)[0]);
            $lang = strtolower(substr($lang, 0, 2));
            
            if (in_array($lang, $availableLanguages)) {
                return $lang;
            }
        }
        
        return null;
    }
    
    /**
     * Generate language switcher HTML
     * @return string
     */
    public static function generateSwitcher() {
        $currentLang = self::getCurrentLanguage();
        $availableLanguages = LocalizationHelper::getAvailableLanguages();
        $currentUrl = $_SERVER['REQUEST_URI'];
        
        // Remove existing lang parameter
        $currentUrl = preg_replace('/[?&]lang=[^&]*/', '', $currentUrl);
        $separator = strpos($currentUrl, '?') !== false ? '&' : '?';
        
        $html = '<div class="language-switcher dropdown">';
        $html .= '<button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">';
        $html .= '<i class="bi bi-translate me-1"></i>';
        $html .= LocalizationHelper::getLanguageName($currentLang);
        $html .= '</button>';
        $html .= '<ul class="dropdown-menu">';
        
        foreach ($availableLanguages as $lang) {
            $isActive = $lang === $currentLang ? 'active' : '';
            $langName = LocalizationHelper::getLanguageName($lang);
            $langUrl = $currentUrl . $separator . 'lang=' . $lang;
            
            $html .= '<li>';
            $html .= '<a class="dropdown-item ' . $isActive . '" href="' . htmlspecialchars($langUrl) . '">';
            $html .= $langName;
            if ($isActive) {
                $html .= ' <i class="bi bi-check float-end"></i>';
            }
            $html .= '</a>';
            $html .= '</li>';
        }
        
        $html .= '</ul>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Get language flag emoji
     * @param string $language Language code
     * @return string
     */
    public static function getLanguageFlag($language) {
        $flags = array(
            'en' => '🇺🇸',
            'pl' => '🇵🇱',
            'de' => '🇩🇪',
            'fr' => '🇫🇷',
            'es' => '🇪🇸'
        );
        
        return isset($flags[$language]) ? $flags[$language] : '🌐';
    }
    
    /**
     * Generate simple language links
     * @return string
     */
    public static function generateSimpleLinks() {
        $currentLang = self::getCurrentLanguage();
        $availableLanguages = LocalizationHelper::getAvailableLanguages();
        $currentUrl = $_SERVER['REQUEST_URI'];
        
        // Remove existing lang parameter
        $currentUrl = preg_replace('/[?&]lang=[^&]*/', '', $currentUrl);
        $separator = strpos($currentUrl, '?') !== false ? '&' : '?';
        
        $html = '<div class="language-links">';
        
        foreach ($availableLanguages as $lang) {
            $isActive = $lang === $currentLang ? 'active' : '';
            $langName = LocalizationHelper::getLanguageName($lang);
            $langUrl = $currentUrl . $separator . 'lang=' . $lang;
            
            $html .= '<a href="' . htmlspecialchars($langUrl) . '" class="lang-link ' . $isActive . '">';
            $html .= self::getLanguageFlag($lang) . ' ' . $langName;
            $html .= '</a>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
}
?>
