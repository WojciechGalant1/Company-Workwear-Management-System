# Localization System Guide

## Overview
This document describes the lightweight localization system implemented for the clothing management application. The system provides full multilingual support without requiring external packages or extensions, maintaining PHP 5.3 compatibility.

## Features

### ✅ **Core Features**
- **No External Dependencies** - Pure PHP 5.3 implementation
- **Multiple Language Support** - Easy to add new languages
- **Server-Side Translation** - Fast and secure
- **Client-Side Integration** - JavaScript support
- **Language Persistence** - Remembers user preference
- **Browser Language Detection** - Automatic language selection
- **Parameter Replacement** - Dynamic content in translations
- **Fallback Support** - Graceful degradation

### 🌍 **Supported Languages**
- **English (en)** - Default language
- **Polish (pl)** - Original language
- **Extensible** - Easy to add more languages

## Architecture

### 1. Core Components

#### `LocalizationHelper.php`
- Main translation engine
- Handles translation loading and caching
- Provides translation methods
- Manages language switching

#### `LanguageSwitcher.php`
- Language persistence (session/cookie)
- Browser language detection
- URL parameter handling
- UI generation for language selection

#### `script/LocalizationHelper.js`
- Client-side translation support
- JavaScript integration
- Dynamic content translation

### 2. Translation Files
Located in `app/config/translations/`:
- `en.php` - English translations
- `pl.php` - Polish translations
- Easy to add new language files

## Usage Examples

### Server-Side Translation

#### Basic Translation
```php
// In PHP templates
echo __('app_title'); // Outputs: "Clothing Management System" (EN) or "System Zarządzania Ubraniami" (PL)

// With parameters
echo __('copyright', array('year' => date('Y')));
// Outputs: "© 2024 Clothing Management System" or "© 2024 System Zarządzania Ubraniami"
```

#### In Controllers/Handlers
```php
// Include localization
include_once __DIR__ . '/../helpers/LocalizationHelper.php';
include_once __DIR__ . '/../helpers/LanguageSwitcher.php';

// Initialize language system
$currentLanguage = LanguageSwitcher::initialize();

// Use translations
$response['message'] = LocalizationHelper::translate('employee_add_success');
```

### Client-Side Translation

#### JavaScript Integration
```javascript
// Import the helper
import { LocalizationHelper } from './LocalizationHelper.js';

// Use translations
const message = LocalizationHelper.translate('success_saved');
const welcomeMessage = LocalizationHelper.translate('welcome_message', { name: 'John' });
```

### Language Switching

#### URL Parameter Method
```
https://yoursite.com/page?lang=en
https://yoursite.com/page?lang=pl
```

#### Programmatic Switching
```php
// Switch language
LanguageSwitcher::setLanguage('en');

// Get current language
$currentLang = LanguageSwitcher::getCurrentLanguage();
```

## Implementation Guide

### 1. Adding New Languages

#### Step 1: Create Translation File
Create `app/config/translations/de.php` for German:

```php
<?php
return array(
    'app_title' => 'Kleidungsverwaltungssystem',
    'login_title' => 'ANMELDEN',
    'employee_add_title' => 'Mitarbeiter hinzufügen',
    // ... more translations
);
?>
```

#### Step 2: Update Language Names
Add to `LanguageSwitcher.php`:

```php
private static function getLanguageName($languageCode) {
    $languageNames = array(
        'en' => 'English',
        'pl' => 'Polski',
        'de' => 'Deutsch', // Add this line
        // ... more languages
    );
    return isset($languageNames[$languageCode]) ? $languageNames[$languageCode] : $languageCode;
}
```

### 2. Adding New Translation Keys

#### Step 1: Add to All Language Files
In `en.php`:
```php
'new_feature_title' => 'New Feature',
'new_feature_description' => 'This is a new feature with :count items',
```

In `pl.php`:
```php
'new_feature_title' => 'Nowa funkcja',
'new_feature_description' => 'To jest nowa funkcja z :count elementami',
```

#### Step 2: Use in Templates
```php
<h2><?php echo __('new_feature_title'); ?></h2>
<p><?php echo __('new_feature_description', array('count' => 5)); ?></p>
```

### 3. Updating Existing Views

#### Before (Hardcoded Polish)
```php
<h2 class="mb-4">Dodawanie pracownika</h2>
<label for="imie" class="form-label">Imię:</label>
<button type="submit" class="btn btn-primary">Dodaj pracownika</button>
```

#### After (Translated)
```php
<h2 class="mb-4"><?php echo __('employee_add_title'); ?></h2>
<label for="imie" class="form-label"><?php echo __('employee_first_name'); ?>:</label>
<button type="submit" class="btn btn-primary"><?php echo __('employee_add_title'); ?></button>
```

## Configuration

### 1. Default Language
Set in `LanguageSwitcher.php`:
```php
const DEFAULT_LANGUAGE = 'en';
```

### 2. Language Detection Order
1. URL parameter (`?lang=en`)
2. Session storage
3. Cookie storage
4. Browser language detection
5. Default language

### 3. Translation File Location
```
app/config/translations/
├── en.php
├── pl.php
└── de.php (future)
```

## Best Practices

### 1. Translation Key Naming
Use descriptive, hierarchical keys:
```php
// Good
'employee_add_title'
'employee_edit_title'
'employee_delete_confirm'

// Avoid
'title1'
'text'
'msg'
```

### 2. Parameter Usage
Use parameters for dynamic content:
```php
// Translation file
'welcome_user' => 'Welcome, :name! You have :count messages.',

// Usage
echo __('welcome_user', array('name' => $userName, 'count' => $messageCount));
```

### 3. Pluralization
Handle different number forms:
```php
// Translation file
'item_count' => ':count item',
'item_count_plural' => ':count items',

// Usage
$key = $count === 1 ? 'item_count' : 'item_count_plural';
echo __($key, array('count' => $count));
```

### 4. Context-Aware Translations
Use context-specific keys:
```php
// Different contexts
'button_save' => 'Save',
'button_save_changes' => 'Save Changes',
'button_save_draft' => 'Save Draft',
```

## Performance Considerations

### 1. Translation Caching
- Translations are loaded once per request
- No database queries for translations
- Minimal memory footprint

### 2. File-Based Storage
- Fast file system access
- Easy to maintain and version control
- No database dependencies

### 3. Lazy Loading
- Translations loaded only when needed
- Automatic fallback to default language
- Graceful error handling

## Migration from Polish

### 1. Gradual Migration
You can migrate gradually by:
1. Adding English translations alongside Polish
2. Using language switcher to test both languages
3. Updating views one by one
4. Eventually making English the default

### 2. Maintaining Polish
The system maintains full Polish support:
- All existing Polish text preserved
- Easy to switch between languages
- No loss of functionality

### 3. Testing Both Languages
Use the language switcher to test:
- All functionality in both languages
- UI layout with different text lengths
- Form validation messages
- Error messages

## Troubleshooting

### Common Issues

#### 1. Translation Not Found
```php
// Check if key exists
if (LocalizationHelper::hasTranslation('my_key')) {
    echo __('my_key');
} else {
    echo 'my_key'; // Fallback to key name
}
```

#### 2. Language Not Switching
- Check if translation file exists
- Verify language code is valid
- Clear browser cookies/session

#### 3. Parameters Not Replaced
```php
// Make sure parameters are passed as array
echo __('welcome', array('name' => 'John')); // Correct
echo __('welcome', 'John'); // Incorrect
```

### Debug Mode
Enable debug logging:
```php
// In LocalizationHelper.php
error_log("Translation missing: " . $key);
```

## Advanced Features

### 1. Dynamic Language Loading
```php
// Load specific language
LocalizationHelper::setLanguage('de');
$translations = LocalizationHelper::getAllTranslations();
```

### 2. Translation Validation
```php
// Check if all required keys exist
$requiredKeys = array('app_title', 'login_title', 'employee_add_title');
foreach ($requiredKeys as $key) {
    if (!LocalizationHelper::hasTranslation($key)) {
        error_log("Missing translation key: " . $key);
    }
}
```

### 3. Custom Language Detection
```php
// Override browser detection
class CustomLanguageSwitcher extends LanguageSwitcher {
    public static function detectBrowserLanguage() {
        // Custom logic here
        return 'en';
    }
}
```

## Conclusion

This localization system provides:
- **Complete multilingual support** without external dependencies
- **Easy maintenance** with file-based translations
- **High performance** with minimal overhead
- **PHP 5.3 compatibility** for legacy environments
- **Extensible architecture** for future enhancements

The system is production-ready and can handle the transition from Polish to English (or any other language) seamlessly while maintaining all existing functionality.
