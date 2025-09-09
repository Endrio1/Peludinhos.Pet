<?php
// api/gatos/listar.php

// 1. INCLUIR A CONEXÃO
// O PHP vai procurar o arquivo subindo dois níveis de diretório ('../../')
// e depois entrando na pasta 'config'.
// A partir desta linha, a variável $conn está disponível e pronta para uso.
// =========================================================================
require_once '../../config/database.php';
// =========================================================================


// 2. USAR A CONEXÃO PARA FAZER UMA CONSULTA
// Agora que temos $conn, podemos usá-la para executar comandos no banco.
// A linha abaixo usa o método query() do objeto de conexão ($conn).
// =========================================================================
$query = "SELECT id, nome, idade, sexo, descricao, foto_url, status FROM gatos ORDER BY data_cadastro DESC";
$result = $conn->query($query);
// =========================================================================


// O resto do script processa os resultados...
$gatos = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $gatos[] = $row;
    }
}

// Fecha a conexão quando não for mais necessária
$conn->close();

echo json_encode($gatos);
?>