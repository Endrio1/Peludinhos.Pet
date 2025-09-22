<?php
require __DIR__ . '/../includes/auth.php';
admin_clear_cookie();
header('Location: /testes/index.php');
exit;
