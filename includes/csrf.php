<?php
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
