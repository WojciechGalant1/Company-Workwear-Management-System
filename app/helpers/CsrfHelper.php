<?php

/**
 * CSRF Protection Helper Class
 * Provides comprehensive CSRF token generation, validation, and management
 */
class CsrfHelper {
    
    const TOKEN_LENGTH = 32;
    const SESSION_KEY = 'csrf_token';
    const FORM_FIELD_NAME = 'csrf_token';
    
    /**
     * Generate a new CSRF token
     * @return string
     */
    public static function generateToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Generate cryptographically secure random token
        $token = bin2hex(random_bytes(self::TOKEN_LENGTH));
        
        // Store token in session with timestamp
        $_SESSION[self::SESSION_KEY] = array(
            'token' => $token,
            'timestamp' => time()
        );
        
        return $token;
    }
    
    /**
     * Get current CSRF token
     * @return string|null
     */
    public static function getToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION[self::SESSION_KEY]['token'])) {
            return $_SESSION[self::SESSION_KEY]['token'];
        }
        
        return null;
    }
    
    /**
     * Generate CSRF token HTML input field
     * @return string
     */
    public static function getTokenField() {
        $token = self::getToken();
        if (!$token) {
            $token = self::generateToken();
        }
        
        return '<input type="hidden" name="' . self::FORM_FIELD_NAME . '" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
    
    /**
     * Validate CSRF token from POST data
     * @param string $token Token to validate (optional, will use POST data if not provided)
     * @return bool
     */
    public static function validateToken($token = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Get token from parameter or POST data
        if ($token === null) {
            $token = isset($_POST[self::FORM_FIELD_NAME]) ? $_POST[self::FORM_FIELD_NAME] : null;
        }
        
        // Check if token exists in session
        if (!isset($_SESSION[self::SESSION_KEY]['token'])) {
            return false;
        }
        
        $sessionToken = $_SESSION[self::SESSION_KEY]['token'];
        $sessionTimestamp = $_SESSION[self::SESSION_KEY]['timestamp'];
        
        // Validate token
        if (!hash_equals($sessionToken, $token)) {
            return false;
        }
        
        // Check token age (optional - tokens expire after 1 hour)
        $maxAge = 3600; // 1 hour
        if ((time() - $sessionTimestamp) > $maxAge) {
            self::regenerateToken();
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate CSRF token from JSON request
     * @param array $data JSON data array
     * @return bool
     */
    public static function validateTokenFromJson($data) {
        if (!isset($data[self::FORM_FIELD_NAME])) {
            return false;
        }
        
        return self::validateToken($data[self::FORM_FIELD_NAME]);
    }
    
    /**
     * Regenerate CSRF token
     * @return string
     */
    public static function regenerateToken() {
        return self::generateToken();
    }
    
    /**
     * Get CSRF token for JavaScript/AJAX requests
     * @return array
     */
    public static function getTokenForAjax() {
        $token = self::getToken();
        if (!$token) {
            $token = self::generateToken();
        }
        
        return array(
            'token' => $token,
            'field_name' => self::FORM_FIELD_NAME
        );
    }
    
    /**
     * Check if request method requires CSRF validation
     * @return bool
     */
    public static function requiresValidation() {
        return in_array($_SERVER['REQUEST_METHOD'], array('POST', 'PUT', 'PATCH', 'DELETE'));
    }
    
    /**
     * Validate CSRF token for current request
     * @return bool
     */
    public static function validateCurrentRequest() {
        if (!self::requiresValidation()) {
            return true;
        }
        
        return self::validateToken();
    }
    
    /**
     * Get CSRF error response
     * @return array
     */
    public static function getErrorResponse() {
        return array(
            'success' => false,
            'error' => 'CSRF token validation failed',
            'message' => 'Błąd bezpieczeństwa. Odśwież stronę i spróbuj ponownie.'
        );
    }
}
?>
