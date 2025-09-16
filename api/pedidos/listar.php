<?php
// api/pedidos/listar.php
//require_once '../../config/auth_check.php';
require_once '../../config/database.php';

// A query usa um JOIN para buscar o nome do gato na tabela 'gatos'
$query = "
    SELECT 
        p.id, 
        p.gato_id,
        p.nome_interessado, 
        p.email_interessado, 
        p.telefone_interessado,
        p.status,
        p.data_pedido,
        g.nome AS gato_nome 
    FROM 
        pedidos_adocao AS p
    JOIN 
        gatos AS g ON p.gato_id = g.id
    ORDER BY 
        p.data_pedido DESC
";

$result = $conn->query($query);
$pedidos = [];

if ($result) {
    $pedidos = $result->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
echo json_encode($pedidos);

?>