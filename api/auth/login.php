<?php
// api/auth/login.php
require_once '../../vendor/autoload.php'; // Caminho para o autoload do Composer
use Firebase\JWT\JWT;

include_once '../../config/database.php';
include_once '../../models/Admin.php'; // Você precisará criar uma classe Admin

$data = json_decode(file_get_contents("php://input"));

if (empty($data->email) || empty($data->senha)) {
    http_response_code(400);
    echo json_encode(array("message" => "Email e senha são obrigatórios."));
    exit;
}

$stmt = $conn->prepare("SELECT id, nome, email, senha_hash FROM admins WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $data->email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    
    // Verificar a senha
    if (password_verify($data->senha, $admin['senha_hash'])) {
        $secret_key = "SUA_CHAVE_SECRETA_SUPER_SECRETA"; // Coloque uma chave segura e guarde-a bem
        $issuer_claim = "localhost"; // O domínio que está emitindo o token
        $audience_claim = "localhost";
        $issuedat_claim = time(); // issued at
        $notbefore_claim = $issuedat_claim; //not before in seconds
        $expire_claim = $issuedat_claim + 3600; // expira em 1 hora

        $token = array(
            "iss" => $issuer_claim,
            "aud" => $audience_claim,
            "iat" => $issuedat_claim,
            "nbf" => $notbefore_claim,
            "exp" => $expire_claim,
            "data" => array(
                "id" => $admin['id'],
                "nome" => $admin['nome'],
                "email" => $admin['email']
            )
        );

        http_response_code(200);
        
        $jwt = JWT::encode($token, $secret_key, 'HS256');
        echo json_encode(
            array(
                "message" => "Login bem-sucedido.",
                "token" => $jwt
            )
        );
    } else {
        http_response_code(401);
        echo json_encode(array("message" => "Login falhou. Senha incorreta."));
    }
} else {
    http_response_code(401);
    echo json_encode(array("message" => "Login falhou. Usuário não encontrado."));
}

$stmt->close();
$conn->close();
?>