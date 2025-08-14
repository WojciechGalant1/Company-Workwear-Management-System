<?php

class SessionManager {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['csrf'])) {
            $_SESSION['csrf'] = md5(uniqid(mt_rand(), true));
        }
    }

    public function login($userId, $status) {
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_status'] = $status;
        if (!isset($_SESSION['csrf'])) {
            $_SESSION['csrf'] = md5(uniqid(mt_rand(), true));
        }
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

// for new php v
// public function __construct()
//     {
//         $this->configureSession();
//         $this->startSession();

//         // Jeśli brak tokena CSRF — generujemy nowy
//         if (!isset($_SESSION['csrf'])) {
//             $_SESSION['csrf'] = bin2hex(random_bytes(32));
//         }
//     }

//     /**
//      * Konfiguracja bezpiecznych ustawień sesji
//      */
//     private function configureSession()
//     {
//         // Ustawienia przed session_start()
//         ini_set('session.use_strict_mode', 1);
//         ini_set('session.cookie_httponly', 1);
//         ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
//         ini_set('session.use_only_cookies', 1);
//         ini_set('session.cookie_samesite', 'Strict');

//         // Można ustawić własną nazwę sesji
//         session_name('MYAPPSESSION');
//     }

//     /**
//      * Rozpoczyna sesję, jeśli jeszcze nie działa
//      */
//     private function startSession()
//     {
//         if (session_status() === PHP_SESSION_NONE) {
//             session_start();
//         }
//     }

//     /**
//      * Loguje użytkownika i regeneruje ID sesji
//      */
//     public function login($userId, $status)
//     {
//         session_regenerate_id(true); // Chroni przed session fixation
//         $_SESSION['user_id'] = $userId;
//         $_SESSION['user_status'] = $status;

//         // Nowy token CSRF po zalogowaniu
//         $_SESSION['csrf'] = bin2hex(random_bytes(32));
//     }

//     /**
//      * Wylogowuje użytkownika
//      */
//     public function logout()
//     {
//         $_SESSION = [];

//         if (ini_get("session.use_cookies")) {
//             $params = session_get_cookie_params();
//             setcookie(session_name(), '', time() - 42000,
//                 $params["path"], $params["domain"],
//                 $params["secure"], $params["httponly"]
//             );
//         }

//         session_destroy();
//     }

//     /**
//      * Sprawdza, czy użytkownik jest zalogowany
//      */
//     public function isLoggedIn(): bool
//     {
//         return isset($_SESSION['user_id']);
//     }

//     /**
//      * Pobiera ID zalogowanego użytkownika
//      */
//     public function getUserId(): ?int
//     {
//         return $_SESSION['user_id'] ?? null;
//     }

//     /**
//      * Pobiera status użytkownika
//      */
//     public function getUserStatus(): int
//     {
//         return $_SESSION['user_status'] ?? 0;
//     }

//     /**
//      * Pobiera aktualny token CSRF
//      */
//     public function getCsrfToken(): string
//     {
//         return $_SESSION['csrf'];
//     }

//     /**
//      * Weryfikuje token CSRF
//      */
//     public function verifyCsrfToken(string $token): bool
//     {
//         return hash_equals($_SESSION['csrf'], $token);
//     }
?>


