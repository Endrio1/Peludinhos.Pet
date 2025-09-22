<?php
// Implementação simples de JWT (HMAC SHA-256)
function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
    $remainder = strlen($data) % 4;
    if ($remainder) $data .= str_repeat('=', 4 - $remainder);
    return base64_decode(strtr($data, '-_', '+/'));
}

function jwt_encode(array $payload, string $secret, int $expSeconds = 3600, string $algo = 'HS256') : string {
    $header = ['typ' => 'JWT', 'alg' => $algo];
    $now = time();
    $payload['iat'] = $now;
    if (!isset($payload['exp'])) $payload['exp'] = $now + $expSeconds;

    $header_b64 = base64url_encode(json_encode($header));
    $payload_b64 = base64url_encode(json_encode($payload));
    $data = $header_b64 . '.' . $payload_b64;

    $sig = hash_hmac('sha256', $data, $secret, true);
    $sig_b64 = base64url_encode($sig);
    return $data . '.' . $sig_b64;
}

function jwt_decode(string $token, string $secret) {
    $parts = explode('.', $token);
    if (count($parts) !== 3) return null;
    list($header_b64, $payload_b64, $sig_b64) = $parts;

    $data = $header_b64 . '.' . $payload_b64;
    $sig = base64url_decode($sig_b64);
    $expected = hash_hmac('sha256', $data, $secret, true);
    if (!hash_equals($expected, $sig)) return null;

    $payload = json_decode(base64url_decode($payload_b64), true);
    if (!is_array($payload)) return null;
    if (isset($payload['exp']) && time() > $payload['exp']) return null;
    return $payload;
}
