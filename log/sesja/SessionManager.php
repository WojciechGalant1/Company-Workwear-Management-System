<?php

class SessionManager {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login($userId, $status) {
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_status'] = $status;
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