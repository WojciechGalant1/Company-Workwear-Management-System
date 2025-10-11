/**
 * JavaScript Localization Helper
 * Provides client-side translation functionality
 */
export const LocalizationHelper = (() => {
    let translations = {};
    let currentLanguage = 'en';
    
    /**
     * Initialize the localization system
     * @param {string} language - Language code
     * @param {Object} translationData - Translation data
     */
    const initialize = (language, translationData) => {
        currentLanguage = language;
        translations = translationData || {};
    };
    
    /**
     * Get current language
     * @returns {string}
     */
    const getCurrentLanguage = () => {
        return currentLanguage;
    };
    
    /**
     * Translate a key
     * @param {string} key - Translation key
     * @param {Object} params - Parameters for string replacement
     * @returns {string} Translated text
     */
    const translate = (key, params = {}) => {
        let translation = translations[key] || key;
        
        // Replace parameters in the translation
        if (params && typeof params === 'object') {
            Object.keys(params).forEach(param => {
                translation = translation.replace(new RegExp(':' + param, 'g'), params[param]);
            });
        }
        
        return translation;
    };
    
    /**
     * Short alias for translate
     * @param {string} key - Translation key
     * @param {Object} params - Parameters for string replacement
     * @returns {string} Translated text
     */
    const t = (key, params = {}) => {
        return translate(key, params);
    };
    
    /**
     * Load translations from server
     * @param {string} language - Language code
     * @returns {Promise<Object>} Translation data
     */
    const loadTranslations = async (language) => {
        try {
            const response = await fetch(`/app/config/translations/${language}.php`);
            if (!response.ok) {
                throw new Error('Failed to load translations');
            }
            const data = await response.text();
            // Parse PHP array as JSON (this is a simplified approach)
            // In a real implementation, you'd want a proper API endpoint
            return {};
        } catch (error) {
            console.error('Error loading translations:', error);
            return {};
        }
    };
    
    /**
     * Get all translations
     * @returns {Object}
     */
    const getAllTranslations = () => {
        return translations;
    };
    
    /**
     * Check if translation exists
     * @param {string} key - Translation key
     * @returns {boolean}
     */
    const hasTranslation = (key) => {
        return key in translations;
    };
    
    /**
     * Get available languages from meta tag
     * @returns {string[]}
     */
    const getAvailableLanguages = () => {
        const metaTag = document.querySelector('meta[name="available-languages"]');
        if (metaTag) {
            return metaTag.getAttribute('content').split(',');
        }
        return ['en', 'pl'];
    };
    
    /**
     * Get current language from meta tag
     * @returns {string}
     */
    const getLanguageFromMeta = () => {
        const metaTag = document.querySelector('meta[name="current-language"]');
        return metaTag ? metaTag.getAttribute('content') : 'en';
    };
    
    /**
     * Initialize from page meta tags
     */
    const initializeFromPage = () => {
        currentLanguage = getLanguageFromMeta();
        // Translations are loaded server-side, so we don't need to fetch them
    };
    
    return {
        initialize,
        getCurrentLanguage,
        translate,
        t,
        loadTranslations,
        getAllTranslations,
        hasTranslation,
        getAvailableLanguages,
        getLanguageFromMeta,
        initializeFromPage
    };
})();

// Auto-initialize when module loads
LocalizationHelper.initializeFromPage();
