<?php
// api/pedidos/criar.php
include_once '../../config/database.php';
header("Access-Control-Allow-Methods: POST");

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gato_id = $_POST['gato_id'];
    $nome = $_POST['nome_interessado'];
    $email = $_POST['email_interessado'];
    $telefone = $_POST['telefone_interessado'];
    $mensagem = $_POST['mensagem'];

    if (empty($gato_id) || empty($nome) || empty($email) || empty($telefone)) {
        $response['message'] = 'Por favor, preencha todos os campos obrigatórios.';
        echo json_encode($response);
        exit;
    }

    // Prepara a query para evitar SQL Injection
    $stmt = $conn->prepare("INSERT INTO pedidos_adocao (gato_id, nome_interessado, email_interessado, telefone_interessado, mensagem) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $gato_id, $nome, $email, $telefone, $mensagem);

    if ($stmt->execute()) {
        // Altera o status do gato para 'Processando Adoção'
        $stmt_update_gato = $conn->prepare("UPDATE gatos SET status = 'Processando Adoção' WHERE id = ?");
        $stmt_update_gato->bind_param("i", $gato_id);
        $stmt_update_gato->execute();
        
        $response['success'] = true;
        $response['message'] = 'Pedido criado com sucesso.';
    } else {
        $response['message'] = 'Erro ao criar o pedido: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();

} else {
    $response['message'] = 'Método de requisição inválido.';
}

echo json_encode($response);
?>