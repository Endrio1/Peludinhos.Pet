<?php
// config/database.php
header("Access-Control-Allow-Origin: *"); // Permite requisições de qualquer origem (ajustar em produção)
header("Content-Type: application/json; charset=UTF-8");

$host = "localhost";
$db_name = "peludinhos_ufopa";
$username = "root"; // seu usuário
$password = ""; // sua senha

$conn = new mysqli($host, $username, $password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define o charset para UTF8 para evitar problemas com acentuação
$conn->set_charset("utf8mb4");
?>