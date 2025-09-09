<?php
// /peludinhos-ufopa/create_admin.php

// Inclui o arquivo de configuração do banco de dados
// O 'config/database.php' deve estar no caminho correto
require __DIR__ . '/config/database.php';

echo "--- Criação do Usuário Administrador ---\n";

// Coleta as informações do terminal
$nome = readline("Digite o nome completo do admin: ");
$email = readline("Digite o email do admin: ");
$senha = readline("Digite a senha do admin: ");

// Validação simples
if (empty($nome) || empty($email) || empty($senha)) {
    echo "\n[ERRO] Todos os campos são obrigatórios. Operação cancelada.\n";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "\n[ERRO] O email fornecido não é válido. Operação cancelada.\n";
    exit;
}

// Criptografa a senha com o método mais seguro do PHP
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// Prepara a query SQL para inserir os dados de forma segura (evita SQL Injection)
$stmt = $conn->prepare("INSERT INTO admins (nome, email, senha_hash) VALUES (?, ?, ?)");

// Verifica se a preparação da query falhou
if ($stmt === false) {
    die("[ERRO] Falha ao preparar a query: " . $conn->error . "\n");
}

// 'sss' significa que estamos passando três strings como parâmetros
$stmt->bind_param("sss", $nome, $email, $senha_hash);

// Executa a query e verifica o resultado
if ($stmt->execute()) {
    echo "\n[SUCESSO] Administrador '" . $nome . "' criado com sucesso!\n";
    echo "Você já pode fazer login com o email '" . $email . "' e a senha que você definiu.\n";
} else {
    // Verifica se o erro é de email duplicado
    if ($conn->errno == 1062) { // 1062 é o código de erro para entrada duplicada
        echo "\n[ERRO] Não foi possível criar o administrador. O email '" . $email . "' já existe no banco de dados.\n";
    } else {
        echo "\n[ERRO] Falha ao executar a query: " . $stmt->error . "\n";
    }
}

// Fecha a conexão com o banco de dados
$stmt->close();
$conn->close();

?>