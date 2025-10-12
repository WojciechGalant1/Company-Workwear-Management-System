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
     * Initialize language from URL, session, cookie, or default
     * @return string Current language code
     */
    public static function initializeWithRouting() {
        $language = self::DEFAULT_LANGUAGE;
        
        // Check URL parameter first (highest priority)
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
            // Set in session without cookie to avoid header issues
            $_SESSION[self::SESSION_KEY] = $language;
            LocalizationHelper::setLanguage($language);
        }
        // Use browser language detection
        else {
            $browserLang = self::detectBrowserLanguage();
            if ($browserLang) {
                $language = $browserLang;
                // Set in session without cookie to avoid header issues
                $_SESSION[self::SESSION_KEY] = $language;
                LocalizationHelper::setLanguage($language);
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
        
        // Only set cookie if headers haven't been sent yet
        if (!headers_sent()) {
            setcookie(self::COOKIE_KEY, $language, time() + self::COOKIE_EXPIRE, '/');
        }
        
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
    
}
?>
