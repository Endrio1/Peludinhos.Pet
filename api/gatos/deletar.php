<?php
// api/gatos/deletar.php
//require_once '../../config/auth_check.php';
require_once '../../config/database.php';

$data = json_decode(file_get_contents("php://input"));
$response = ['success' => false, 'message' => ''];

if (isset($data->id)) {
    $id = intval($data->id);

    // Opcional: deletar a foto do servidor
    $stmt_select = $conn->prepare("SELECT foto_url FROM gatos WHERE id = ?");
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    if($row = $result->fetch_assoc()){
        if(file_exists('../../' . $row['foto_url'])){
            unlink('../../' . $row['foto_url']);
        }
    }
    $stmt_select->close();

    // Deletar do banco de dados
    $stmt_delete = $conn->prepare("DELETE FROM gatos WHERE id = ?");
    $stmt_delete->bind_param("i", $id);

    if ($stmt_delete->execute()) {
        $response['success'] = true;
        $response['message'] = 'Gato excluído com sucesso.';
    } else {
        $response['message'] = 'Erro ao excluir o gato.';
    }
    $stmt_delete->close();
} else {
    $response['message'] = 'ID do gato não fornecido.';
}

$conn->close();
echo json_encode($response);
?>