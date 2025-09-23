<?php
// Defense: detect malformed or control-character cookies that may cause server to reject the request.
// This runs before session_start() so that we can clear problematic cookies client-side.
if (!empty($_SERVER['HTTP_COOKIE'])) {
    // If cookie header contains control characters, remove sensitive cookies and force reload.
    if (preg_match('/[\x00-\x1F\x7F]/', $_SERVER['HTTP_COOKIE'])) {
        // Expire common cookies that may cause issues (auth_token, PHPSESSID) and any others if needed
        if (!empty($_COOKIE)) {
            foreach ($_COOKIE as $k => $v) {
                // expire cookie for root path
                setcookie($k, '', time() - 3600, '/');
            }
        }
        // Try to redirect to same URI without cookies
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        header('Location: ' . $uri);
        exit;
    }
}

if (session_status() !== PHP_SESSION_ACTIVE) session_start();

function csrf_token(): string {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string {
    $t = csrf_token();
    return '<input type="hidden" name="_csrf" value="' . htmlspecialchars($t) . '">';
}

function csrf_check(): bool {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $posted = $_POST['_csrf'] ?? null;
    if (!$posted) return false;
    return hash_equals($_SESSION['csrf_token'] ?? '', $posted);
}
