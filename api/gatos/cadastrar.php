<?php
// api/gatos/cadastrar.php
require_once '../../config/auth_check.php';
require_once '../../config/database.php';

$response = ['success' => false, 'message' => ''];
$id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;

$nome = $_POST['nome'];
$idade = $_POST['idade'];
$sexo = $_POST['sexo'];
$descricao = $_POST['descricao'];
$status = $_POST['status'];

$foto_url = '';
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $target_dir = "../../uploads/";
    $file_extension = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
    $new_filename = uniqid('gato_', true) . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
        $foto_url = "uploads/" . $new_filename;
    } else {
        $response['message'] = 'Erro ao fazer upload da foto.';
        echo json_encode($response);
        exit;
    }
}

if ($id > 0) { // --- UPDATE ---
    if (!empty($foto_url)) {
        $stmt = $conn->prepare("UPDATE gatos SET nome=?, idade=?, sexo=?, descricao=?, status=?, foto_url=? WHERE id=?");
        $stmt->bind_param("ssssssi", $nome, $idade, $sexo, $descricao, $status, $foto_url, $id);
    } else {
        $stmt = $conn->prepare("UPDATE gatos SET nome=?, idade=?, sexo=?, descricao=?, status=? WHERE id=?");
        $stmt->bind_param("sssssi", $nome, $idade, $sexo, $descricao, $status, $id);
    }
    $action = "atualizado";
} else { // --- INSERT ---
    if (empty($foto_url)) {
         $response['message'] = 'A foto é obrigatória para um novo cadastro.';
         echo json_encode($response);
         exit;
    }
    $stmt = $conn->prepare("INSERT INTO gatos (nome, idade, sexo, descricao, status, foto_url) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nome, $idade, $sexo, $descricao, $status, $foto_url);
    $action = "cadastrado";
}

if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = "Gato {$action} com sucesso!";
} else {
    $response['message'] = 'Erro no banco de dados: ' . $stmt->error;
}

$stmt->close();
$conn->close();
echo json_encode($response);
?>