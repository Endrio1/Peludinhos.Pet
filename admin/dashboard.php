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
            <h1>Pedidos de Adoção Pendentes</h1>
            <table id="pedidos-table">
                <thead>
                    <tr>
                        <th>Gato</th>
                        <th>Interessado</th>
                        <th>Contato</th>
                        <th>Data</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    </tbody>
            </table>
        </main>
    </div>
    <script src="auth.js"></script>
    <script>
        // Lógica para carregar os pedidos na tabela
        // e para os botões de Aprovar/Rejeitar
        // A lógica completa estaria em um arquivo como `admin/dashboard.js`
    </script>
</body>
</html>