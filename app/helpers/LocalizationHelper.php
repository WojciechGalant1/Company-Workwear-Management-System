<?php

/**
 * Simple Localization Helper for PHP 5.3
 * Provides translation functionality without external dependencies
 */
class LocalizationHelper {
    
    private static $currentLanguage = 'en';
    private static $translations = array();
    private static $fallbackLanguage = 'en';
    private static $initialized = false;
    
    /**
     * Initialize the localization system
     * @param string $language Language code (e.g., 'en', 'pl')
     */
    public static function initialize($language = 'en') {
        if (self::$initialized) {
            return;
        }
        
        self::$currentLanguage = $language;
        self::loadTranslations();
        self::$initialized = true;
    }
    
    /**
     * Set the current language
     * @param string $language Language code
     */
    public static function setLanguage($language) {
        if (self::$currentLanguage !== $language) {
            self::$currentLanguage = $language;
            self::loadTranslations();
        }
    }
    
    /**
     * Get the current language
     * @return string
     */
    public static function getCurrentLanguage() {
        return self::$currentLanguage;
    }
    
    /**
     * Load translations for the current language
     */
    private static function loadTranslations() {
        $translationFile = __DIR__ . '/../config/translations/' . self::$currentLanguage . '.php';
        
        if (file_exists($translationFile)) {
            self::$translations = include $translationFile;
        } else {
            // Fallback to English if translation file doesn't exist
            $fallbackFile = __DIR__ . '/../config/translations/' . self::$fallbackLanguage . '.php';
            if (file_exists($fallbackFile)) {
                self::$translations = include $fallbackFile;
            } else {
                self::$translations = array();
            }
        }
    }
    
    /**
     * Translate a key
     * @param string $key Translation key
     * @param array $params Parameters for string replacement
     * @return string Translated text
     */
    public static function translate($key, $params = array()) {
        if (!self::$initialized) {
            self::initialize();
        }
        
        $translation = isset(self::$translations[$key]) ? self::$translations[$key] : $key;
        
        // Replace parameters in the translation
        if (!empty($params)) {
            foreach ($params as $param => $value) {
                $translation = str_replace(':' . $param, $value, $translation);
            }
        }
        
        return $translation;
    }
    
    /**
     * Short alias for translate
     * @param string $key Translation key
     * @param array $params Parameters for string replacement
     * @return string Translated text
     */
    public static function t($key, $params = array()) {
        return self::translate($key, $params);
    }
    
    /**
     * Get all translations for the current language
     * @return array
     */
    public static function getAllTranslations() {
        if (!self::$initialized) {
            self::initialize();
        }
        
        return self::$translations;
    }
    
    /**
     * Check if a translation key exists
     * @param string $key Translation key
     * @return bool
     */
    public static function hasTranslation($key) {
        if (!self::$initialized) {
            self::initialize();
        }
        
        return isset(self::$translations[$key]);
    }
    
    /**
     * Get available languages
     * @return array
     */
    public static function getAvailableLanguages() {
        $languages = array();
        $translationDir = __DIR__ . '/../config/translations/';
        
        if (is_dir($translationDir)) {
            $files = glob($translationDir . '*.php');
            foreach ($files as $file) {
                $languages[] = basename($file, '.php');
            }
        }
        
        return $languages;
    }
    
    /**
     * Get language name in its own language
     * @param string $languageCode Language code
     * @return string
     */
    public static function getLanguageName($languageCode) {
        $languageNames = array(
            'en' => 'English',
            'pl' => 'Polski',
            'de' => 'Deutsch',
            'fr' => 'Français',
            'es' => 'Español'
        );
        
        return isset($languageNames[$languageCode]) ? $languageNames[$languageCode] : $languageCode;
    }
}
?>
