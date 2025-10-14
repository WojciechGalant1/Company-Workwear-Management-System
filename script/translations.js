/**
 * JavaScript Translation Helper
 * Provides client-side translations for JavaScript alerts and messages
 */

export const Translations = (() => {
    let currentLanguage = 'en';
    let translations = {};

    // English translations
    const enTranslations = {
        // Success messages
        'edit_success': 'Edit completed successfully',
        'operation_success': 'Operation completed successfully',
        'data_saved': 'Data saved successfully',
        'data_updated': 'Data updated successfully',
        'data_deleted': 'Data deleted successfully',
        
        // Error messages
        'edit_error': 'Error during editing',
        'operation_error': 'Error during operation',
        'save_error': 'Error during saving',
        'delete_error': 'Error during deletion',
        'update_error': 'Error during update',
        'network_error': 'Network error occurred',
        'server_error': 'Server error occurred',
        
        // Status messages
        'status_changed': 'Status changed',
        'status_updated': 'Status updated',
        'status_cancelled': 'Status cancelled',
        'status_destroyed': 'Status destroyed',
        
        // Validation messages
        'validation_required': 'This field is required',
        'validation_invalid': 'Invalid data provided',
        'validation_code_invalid': 'Code not entered or entered incorrectly',
        'validation_quantity_positive': 'Quantity must be greater than zero',
        
        // Clothing messages
        'clothing_found': 'Clothing found',
        'clothing_not_found': 'Clothing not found with given code',
        'clothing_code_empty': 'Code field cannot be empty',
        'clothing_insufficient_stock': 'Insufficient stock available',
        
        // Employee messages
        'employee_found': 'Employee found',
        'employee_not_found': 'Employee not found',
        
        // General messages
        'loading': 'Loading...',
        'processing': 'Processing...',
        'please_wait': 'Please wait...',
        'try_again': 'Try again',
        'refresh_page': 'Refresh page and try again',
        'contact_support': 'Contact technical support',
        
        // Confirmation messages
        'confirm_delete': 'Are you sure you want to delete this item?',
        'confirm_cancel': 'Are you sure you want to cancel this action?',
        'confirm_destroy': 'Are you sure you want to destroy this clothing?',
        
        // Button texts
        'cancel': 'Cancel',
        'confirm': 'Confirm',
        'delete': 'Delete',
        'edit': 'Edit',
        'save': 'Save',
        'close': 'Close',
        'yes': 'Yes',
        'no': 'No',
        
        // Additional messages
        'status_update_failed': 'Failed to update status',
        'clothing_search_error': 'Error searching for clothing',

        // issue clothing
        'select_size_name': 'Select Size',


    };

    // Polish translations
    const plTranslations = {
        // Success messages
        'edit_success': 'Edycja zakończona sukcesem',
        'operation_success': 'Operacja zakończona sukcesem',
        'data_saved': 'Dane zostały zapisane pomyślnie',
        'data_updated': 'Dane zostały zaktualizowane pomyślnie',
        'data_deleted': 'Dane zostały usunięte pomyślnie',
        
        // Error messages
        'edit_error': 'Błąd podczas edycji',
        'operation_error': 'Błąd podczas operacji',
        'save_error': 'Błąd podczas zapisywania',
        'delete_error': 'Błąd podczas usuwania',
        'update_error': 'Błąd podczas aktualizacji',
        'network_error': 'Wystąpił błąd sieci',
        'server_error': 'Wystąpił błąd serwera',
        
        // Status messages
        'status_changed': 'Status zmieniony',
        'status_updated': 'Status zaktualizowany',
        'status_cancelled': 'Status anulowany',
        'status_destroyed': 'Status zniszczony',
        
        // Validation messages
        'validation_required': 'To pole jest wymagane',
        'validation_invalid': 'Nieprawidłowe dane',
        'validation_code_invalid': 'Kod nie został wprowadzony lub został wprowadzony niepoprawnie',
        'validation_quantity_positive': 'Ilość musi być większa od zera',
        
        // Clothing messages
        'clothing_found': 'Znaleziono ubranie',
        'clothing_not_found': 'Nie znaleziono ubrania o podanym kodzie',
        'clothing_code_empty': 'Pole kodu nie może być puste',
        'clothing_insufficient_stock': 'Niewystarczający stan magazynowy',
        
        // Employee messages
        'employee_found': 'Znaleziono pracownika',
        'employee_not_found': 'Nie znaleziono pracownika',
        
        // General messages
        'loading': 'Ładowanie...',
        'processing': 'Przetwarzanie...',
        'please_wait': 'Proszę czekać...',
        'try_again': 'Spróbuj ponownie',
        'refresh_page': 'Odśwież stronę i spróbuj ponownie',
        'contact_support': 'Skontaktuj się z pomocą techniczną',
        
        // Confirmation messages
        'confirm_delete': 'Czy na pewno chcesz usunąć ten element?',
        'confirm_cancel': 'Czy na pewno chcesz anulować tę akcję?',
        'confirm_destroy': 'Czy na pewno chcesz zniszczyć to ubranie?',
        
        // Button texts
        'cancel': 'Anuluj',
        'confirm': 'Potwierdź',
        'delete': 'Usuń',
        'edit': 'Edytuj',
        'save': 'Zapisz',
        'close': 'Zamknij',
        'yes': 'Tak',
        'no': 'Nie',
        
        // Additional messages
        'status_update_failed': 'Nie udało się zaktualizować statusu',
        'clothing_search_error': 'Wystąpił błąd podczas wyszukiwania ubrania',

        // issue clothing
        'select_size_name': 'Wybierz rozmiar',
    };

    const initialize = () => {
        // Get current language from meta tag
        const metaLang = document.querySelector('meta[name="current-language"]');
        if (metaLang) {
            currentLanguage = metaLang.getAttribute('content');
        }
        
        // Set translations based on current language
        translations = currentLanguage === 'pl' ? plTranslations : enTranslations;
    };

    const translate = (key, params = {}) => {
        let translation = translations[key] || key;
        
        // Replace parameters in translation
        Object.keys(params).forEach(param => {
            translation = translation.replace(`{${param}}`, params[param]);
        });
        
        return translation;
    };

    const getCurrentLanguage = () => {
        return currentLanguage;
    };

    const setLanguage = (lang) => {
        currentLanguage = lang;
        translations = lang === 'pl' ? plTranslations : enTranslations;
    };

    return {
        initialize,
        translate,
        getCurrentLanguage,
        setLanguage
    };
})();

// Auto-initialize when module loads
Translations.initialize();
