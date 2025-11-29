<?php
// functions.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Forçar HTTPS (opcional)
function ensure_https() {
    if (defined('FORCE_HTTPS') && FORCE_HTTPS) {
        if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
            $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            header('Location: ' . $redirect);
            exit;
        }
    }
}

// Segurança básica de sessão
function secure_session_start() {
    if (session_status() === PHP_SESSION_NONE) {
        $cookieParams = session_get_cookie_params();
        session_set_cookie_params([
            'lifetime' => $cookieParams['lifetime'],
            'path' => $cookieParams['path'],
            'domain' => $cookieParams['domain'],
            'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off', // true se HTTPS
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        session_start();
    }
}

// Gera e valida CSRF token (simples)
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_check($token) {
    return !empty($token) && hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

// Verifica se usuário logado
function is_logged_in() {
    return !empty($_SESSION['user_id']);
}

// Redireciona se não estiver logado
function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}
