<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/jwt.php';

$config = require __DIR__ . '/config.php';

function admin_login(PDO $pdo, $email, $password) {
    $stmt = $pdo->prepare('SELECT id, name, email, password_hash FROM admins WHERE email = :email LIMIT 1');
    $stmt->execute([':email' => $email]);
    $admin = $stmt->fetch();
    if (!$admin) return null;
    if (!password_verify($password, $admin['password_hash'])) return null;

    $payload = ['sub' => $admin['id'], 'email' => $admin['email'], 'name' => $admin['name']];
    $cfg = $GLOBALS['config']['jwt'] ?? $config['jwt'];
    $token = jwt_encode($payload, $cfg['secret'], 3600, $cfg['algo']);
    return $token;
}

function admin_protect() {
    // Espera token em Authorization: Bearer <token> ou cookie 'auth_token'
    // getallheaders() não existe em algumas SAPI; usar fallback via $_SERVER
    if (function_exists('getallheaders')) {
        $headers = getallheaders();
    } else {
        $headers = [];
        foreach ($_SERVER as $k => $v) {
            if (strpos($k, 'HTTP_') === 0) {
                $name = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($k,5)))));
                $headers[$name] = $v;
            }
        }
    }
    $token = null;
    if (!empty($headers['Authorization'])) {
        if (preg_match('/Bearer\s+(.*)$/i', $headers['Authorization'], $m)) $token = trim($m[1]);
    }
    if (!$token && !empty($_COOKIE['auth_token'])) $token = $_COOKIE['auth_token'];
    if (!$token) return null;

    $cfg = $GLOBALS['config']['jwt'] ?? $config['jwt'];
    $payload = jwt_decode($token, $cfg['secret']);
    return $payload; // null se inválido
}

function admin_set_cookie($token) {
    setcookie('auth_token', $token, time() + 3600, '/', '', false, true);
}

function admin_clear_cookie() {
    setcookie('auth_token', '', time() - 3600, '/', '', false, true);
}
