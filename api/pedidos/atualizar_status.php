<?php
// api/pedidos/atualizar_status.php
//require_once '../../config/auth_check.php';
require_once '../../config/database.php';

$data = json_decode(file_get_contents("php://input"));
$response = ['success' => false, 'message' => ''];

if (!isset($data->id) || !isset($data->status)) {
    $response['message'] = 'Dados incompletos.';
    echo json_encode($response);
    exit;
}

$pedido_id = intval($data->id);
$gato_id = intval($data->gato_id); // Recebemos o ID do gato para a lógica de aprovação
$new_status = $data->status;

// Inicia uma transação para garantir que ambas as tabelas sejam atualizadas corretamente
$conn->begin_transaction();

try {
    // 1. Atualiza o status do pedido de adoção
    $stmt1 = $conn->prepare("UPDATE pedidos_adocao SET status = ? WHERE id = ?");
    $stmt1->bind_param("si", $new_status, $pedido_id);
    $stmt1->execute();
    $stmt1->close();

    // 2. Se o pedido foi aprovado, atualiza o status do gato para 'Adotado'
    if ($new_status === 'Aprovado' && $gato_id > 0) {
        $stmt2 = $conn->prepare("UPDATE gatos SET status = 'Adotado' WHERE id = ?");
        $stmt2->bind_param("i", $gato_id);
        $stmt2->execute();
        $stmt2->close();
    }

    // Se tudo deu certo, confirma as alterações no banco de dados
    $conn->commit();
    $response['success'] = true;
    $response['message'] = 'Status do pedido atualizado com sucesso.';

} catch (Exception $e) {
    // Se algo deu errado, desfaz todas as alterações
    $conn->rollback();
    $response['message'] = 'Erro ao atualizar o status: ' . $e->getMessage();
}

$conn->close();
echo json_encode($response);

?>