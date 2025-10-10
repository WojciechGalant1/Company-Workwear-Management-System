<?php
include_once __DIR__ . '/../helpers/CsrfHelper.php';

class SessionManager {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Initialize CSRF token using the new helper
        if (!isset($_SESSION['csrf_token'])) {
            CsrfHelper::generateToken();
        }
        
        // Keep backward compatibility with old CSRF implementation
        if (!isset($_SESSION['csrf'])) {
            $_SESSION['csrf'] = CsrfHelper::getToken();
        }
    }

    public function login($userId, $status) {
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_status'] = $status;
        
        // Regenerate CSRF token on login for security
        CsrfHelper::regenerateToken();
        $_SESSION['csrf'] = CsrfHelper::getToken();
    }

    public function logout() {
        session_unset();
        session_destroy();
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function getUserId() {
        return (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null);
    }

    public function getUserStatus() {
        return isset($_SESSION['user_status']) ? $_SESSION['user_status'] : 0;
    }
}

?>


