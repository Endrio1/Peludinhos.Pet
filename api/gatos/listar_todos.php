<?php
// api/gatos/listar_todos.php
require_once '../../config/auth_check.php'; // VERIFICA O TOKEN
require_once '../../config/database.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Busca um gato específico
    $stmt = $conn->prepare("SELECT * FROM gatos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $gato = $result->fetch_assoc();
    echo json_encode($gato);
    $stmt->close();
} else {
    // Lista todos os gatos
    $result = $conn->query("SELECT * FROM gatos ORDER BY data_cadastro DESC");
    $gatos = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($gatos);
}

$conn->close();
?>