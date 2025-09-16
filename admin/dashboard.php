<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Pedidos de Adoção</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <aside class="sidebar">
            <h3>Admin Peludinhos</h3>
            <ul>
                <li class="active"><a href="dashboard.php">Pedidos de Adoção</a></li>
                <li><a href="gerenciar-gatos.php">Gerenciar Gatos</a></li>
                <li><a href="perfil.php">Meu Perfil</a></li>
                <li><a href="#" id="logout-btn">Sair</a></li>
            </ul>
        </aside>
        <main class="admin-content">
            <h1>Pedidos de Adoção</h1> <table id="pedidos-table">
                <thead>
                    <tr>
                        <th>Gato</th>
                        <th>Interessado</th>
                        <th>Contato</th>
                        <th>Data do Pedido</th> <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="pedidos-table-body">
                    </tbody>
            </table>
        </main>
    </div>
    <script src="../admin/auth.js"></script>
    <script src="../admin/dashboard.js"></script> 
</body>
</html>