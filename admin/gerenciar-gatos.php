<?php
// Futuramente, aqui entrará o script de verificação de login (token JWT)
// session_start();
// if(!isset($_SESSION['admin_logged_in'])) {
//     header('Location: ../login.php');
//     exit();
// }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Gatos - Admin Peludinhos</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/modal.css"> </head>
<body>
    <div class="admin-wrapper">
        <aside class="sidebar">
            <h3>Admin Peludinhos</h3>
            <ul>
                <li><a href="dashboard.php">Pedidos de Adoção</a></li>
                <li class="active"><a href="gerenciar-gatos.php">Gerenciar Gatos</a></li>
                <li><a href="perfil.php">Meu Perfil</a></li>
                <li><a href="#" id="logout-btn">Sair</a></li>
            </ul>
        </aside>
        <main class="admin-content">
            <div class="content-header">
                <h1>Gerenciar Gatos</h1>
                <button id="add-cat-btn" class="btn btn-success">Adicionar Novo Gato</button>
            </div>
            
            <table id="gatos-table">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nome</th>
                        <th>Idade</th>
                        <th>Sexo</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="gatos-table-body">
                    </tbody>
            </table>
        </main>
    </div>

    <div id="gato-modal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2 id="modal-title">Adicionar Novo Gato</h2>
            <form id="gato-form" enctype="multipart/form-data">
                <input type="hidden" id="gato_id" name="id">
                
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="idade">Idade (ex: 2 meses, 1 ano)</label>
                    <input type="text" id="idade" name="idade" required>
                </div>
                <div class="form-group">
                    <label for="sexo">Sexo</label>
                    <select id="sexo" name="sexo" required>
                        <option value="Macho">Macho</option>
                        <option value="Fêmea">Fêmea</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea id="descricao" name="descricao" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="Disponível">Disponível</option>
                        <option value="Processando Adoção">Processando Adoção</option>
                        <option value="Adotado">Adotado</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="foto">Foto</label>
                    <input type="file" id="foto" name="foto" accept="image/*">
                    <small>Se não selecionar uma nova foto ao editar, a foto atual será mantida.</small>
                </div>
                
                <button type="submit" class="btn btn-success" id="save-gato-btn">Salvar</button>
            </form>
        </div>
    </div>

    <script src="../admin/auth.js"></script> <script src="../admin/gatos.js"></script> </body>
</html>