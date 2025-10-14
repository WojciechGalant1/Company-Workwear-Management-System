export const LocalizationHelper = (() => {
    let translations = {};
    let currentLanguage = 'en';
    
    const initialize = (language, translationData) => {
        currentLanguage = language;
        translations = translationData || {};
    };
    
    const getCurrentLanguage = () => {
        return currentLanguage;
    };
    
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
        
    const t = (key, params = {}) => {
        return translate(key, params);
    };
    
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
    
    const getAllTranslations = () => {
        return translations;
    };
   
    const hasTranslation = (key) => {
        return key in translations;
    };
    
    const getAvailableLanguages = () => {
        const metaTag = document.querySelector('meta[name="available-languages"]');
        if (metaTag) {
            return metaTag.getAttribute('content').split(',');
        }
        return ['en', 'pl'];
    };
    

    const getLanguageFromMeta = () => {
        const metaTag = document.querySelector('meta[name="current-language"]');
        return metaTag ? metaTag.getAttribute('content') : 'en';
    };
    
    const initializeFromPage = () => {
        currentLanguage = getLanguageFromMeta();
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

LocalizationHelper.initializeFromPage();
