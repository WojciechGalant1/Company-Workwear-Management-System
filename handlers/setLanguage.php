<?php
/**
 * Language Setting Handler
 * Handles AJAX language switching requests
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../app/helpers/LanguageSwitcher.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['lang']) && LanguageSwitcher::isValidLanguage($input['lang'])) {
        $language = $input['lang'];
        LanguageSwitcher::setLanguage($language);
        
        echo json_encode(array(
            'success' => true,
            'language' => $language,
            'message' => 'Language switched successfully'
        ));
    } else {
        echo json_encode(array(
            'success' => false,
            'message' => 'Invalid language code'
        ));
    }
} else {
    echo json_encode(array(
        'success' => false,
        'message' => 'Method not allowed'
    ));
}
?>
