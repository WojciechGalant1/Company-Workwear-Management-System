/**
 * JavaScript Language Switcher
 * Provides client-side language switching functionality
 */
export const LanguageSwitcher = (() => {
    
    /**
     * Switch language using URL parameter
     * @param {string} language - Language code
     */
    const switchLanguage = (language) => {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('lang', language);
        
        // Force a full page reload to ensure server-side translations are applied
        window.location.href = currentUrl.toString();
    };
    
    /**
     * Switch language using fetch (AJAX method)
     * @param {string} language - Language code
     */
    const switchLanguageAjax = async (language) => {
        try {
            const response = await fetch('/handlers/setLanguage.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ lang: language })
            });
            
            if (response.ok) {
                // Reload the page to apply the new language
                window.location.reload();
            } else {
                console.error('Failed to switch language');
            }
        } catch (error) {
            console.error('Error switching language:', error);
            // Fallback to URL method
            switchLanguage(language);
        }
    };
    
    /**
     * Initialize language switcher
     */
    const initialize = () => {
        // Add click handlers to language links
        document.addEventListener('click', (event) => {
            if (event.target.matches('.lang-link') || event.target.closest('.lang-link')) {
                event.preventDefault();
                const link = event.target.matches('.lang-link') ? event.target : event.target.closest('.lang-link');
                const href = link.getAttribute('href');
                
                if (href) {
                    // Extract language from href
                    const url = new URL(href, window.location.origin);
                    const language = url.searchParams.get('lang');
                    
                    if (language) {
                        switchLanguage(language);
                    }
                }
            }
        });
        
        // Add click handlers to dropdown items
        document.addEventListener('click', (event) => {
            if (event.target.matches('.dropdown-item[data-lang]')) {
                event.preventDefault();
                const language = event.target.getAttribute('data-lang');
                if (language) {
                    switchLanguage(language);
                }
            }
        });
    };
    
    /**
     * Get current language from URL or meta tag
     * @returns {string}
     */
    const getCurrentLanguage = () => {
        // Try to get from URL
        const urlParams = new URLSearchParams(window.location.search);
        const urlLang = urlParams.get('lang');
        if (urlLang) {
            return urlLang;
        }
        
        // Try to get from meta tag
        const metaTag = document.querySelector('meta[name="current-language"]');
        if (metaTag) {
            return metaTag.getAttribute('content');
        }
        
        return 'en'; // Default
    };
    
    /**
     * Create language switcher HTML
     * @param {string[]} languages - Available languages
     * @returns {string}
     */
    const createSwitcherHTML = (languages) => {
        const currentLang = getCurrentLanguage();
        let html = '<div class="language-switcher-js">';
        
        languages.forEach(lang => {
            const isActive = lang === currentLang ? 'active' : '';
            const flag = getLanguageFlag(lang);
            const name = getLanguageName(lang);
            
            html += `<a href="?lang=${lang}" class="lang-link ${isActive}" data-lang="${lang}">`;
            html += `${flag} ${name}`;
            html += '</a>';
        });
        
        html += '</div>';
        return html;
    };
    
    /**
     * Get language flag emoji
     * @param {string} language - Language code
     * @returns {string}
     */
    const getLanguageFlag = (language) => {
        const flags = {
            'en': '🇺🇸',
            'pl': '🇵🇱',
            'de': '🇩🇪',
            'fr': '🇫🇷',
            'es': '🇪🇸'
        };
        return flags[language] || '🌐';
    };
    
    /**
     * Get language name
     * @param {string} language - Language code
     * @returns {string}
     */
    const getLanguageName = (language) => {
        const names = {
            'en': 'English',
            'pl': 'Polski',
            'de': 'Deutsch',
            'fr': 'Français',
            'es': 'Español'
        };
        return names[language] || language;
    };
    
    return {
        switchLanguage,
        switchLanguageAjax,
        initialize,
        getCurrentLanguage,
        createSwitcherHTML,
        getLanguageFlag,
        getLanguageName
    };
})();

// Auto-initialize when module loads
document.addEventListener('DOMContentLoaded', () => {
    LanguageSwitcher.initialize();
});
