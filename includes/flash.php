<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

function flash_set(string $message, string $type = 'success') {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if (!isset($_SESSION['flash'])) $_SESSION['flash'] = [];
    $_SESSION['flash'][] = ['msg' => $message, 'type' => $type];
}

function flash_get_all(): array {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $f = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $f;
}

function flash_render(): string {
    $items = flash_get_all();
    if (empty($items)) return '';
    $html = '';
    foreach ($items as $it) {
        $cls = $it['type'] === 'error' ? 'background:#fee;padding:10px;border:1px solid #fdd;border-radius:6px;margin-bottom:12px' : 'background:#e6ffed;padding:10px;border:1px solid #cfc;border-radius:6px;margin-bottom:12px';
        $html .= '<div class="card" style="' . $cls . '">' . htmlspecialchars($it['msg']) . '</div>' . "\n";
    }
    return $html;
}
